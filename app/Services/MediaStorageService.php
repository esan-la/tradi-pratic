<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorageService
{
    protected string $externalDisk;

    public function __construct()
    {
        // Utiliser 'external_media' si configuré, sinon fallback sur 'public'
        $this->externalDisk = $this->isDiskConfigured('external_media') ? 'external_media' : 'public';
    }

    /**
     * Vérifier si un disque est correctement configuré
     */
    protected function isDiskConfigured(string $disk): bool
    {
        try {
            $config = config("filesystems.disks.{$disk}");
            return $config !== null && isset($config['driver']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Upload une image sans compression
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $customName
     * @return string Chemin relatif du fichier
     * @throws \InvalidArgumentException
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images', ?string $customName = null): string
    {
        // Valider que c'est bien une image
        if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
            throw new \InvalidArgumentException('Le fichier doit être une image valide');
        }

        // Générer un nom unique si non fourni
        $filename = $customName ?? $this->generateUniqueFilename($file);

        // Chemin complet avec date
        $path = $directory . '/' . date('Y/m') . '/' . $filename;

        // Stocker sans compression
        Storage::disk($this->externalDisk)->put(
            $path,
            file_get_contents($file->getRealPath())
        );

        return $path;
    }

    /**
     * Upload plusieurs images (galerie)
     *
     * @param array $files
     * @param string $directory
     * @return array
     */
    public function uploadMultiple(array $files, string $directory = 'images'): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file, $directory);
            }
        }

        return $paths;
    }

    /**
     * Supprimer un fichier
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (Storage::disk($this->externalDisk)->exists($path)) {
            return Storage::disk($this->externalDisk)->delete($path);
        }

        return false;
    }

    /**
     * Supprimer plusieurs fichiers
     *
     * @param array $paths
     * @return bool
     */
    public function deleteMultiple(array $paths): bool
    {
        $success = true;

        foreach ($paths as $path) {
            if (!$this->delete($path)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Vérifier si un fichier existe
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->externalDisk)->exists($path);
    }

    /**
     * Obtenir l'URL publique d'un fichier
     *
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        return Storage::disk($this->externalDisk)->url($path);
    }

    /**
     * Générer un nom de fichier unique
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $uniqueId = Str::uuid();

        return $uniqueId . '.' . $extension;
    }

    /**
     * Obtenir le disque de stockage utilisé
     *
     * @return string
     */
    public function getDiskName(): string
    {
        return $this->externalDisk;
    }

    /**
     * Obtenir des informations sur le fichier
     *
     * @param string $path
     * @return array|null
     */
    public function getFileInfo(string $path): ?array
    {
        if (!$this->exists($path)) {
            return null;
        }

        $disk = Storage::disk($this->externalDisk);

        return [
            'path' => $path,
            'size' => $disk->size($path),
            'mime_type' => $disk->mimeType($path),
            'last_modified' => $disk->lastModified($path),
            'url' => $this->url($path),
        ];
    }
}
