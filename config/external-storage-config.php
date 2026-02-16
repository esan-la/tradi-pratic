<?php

/**
 * Configuration du Stockage Externe pour TradiPratic
 *
 * IMPORTANT: Les médias (images et vidéos) sont stockés SANS compression
 * sur un système de fichiers externe pour préserver la qualité originale.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration à ajouter dans config/filesystems.php
    |--------------------------------------------------------------------------
    */

    'disks' => [
        // ... autres disques existants

        /**
         * Stockage externe pour les médias (images et vidéos)
         *
         * Configuration pour un serveur de stockage externe dédié
         */
        'external_media' => [
            'driver' => 'local',
            'root' => env('EXTERNAL_MEDIA_PATH', '/var/www/external-media'),
            'url' => env('EXTERNAL_MEDIA_URL', 'https://media.tradipratic.com'),
            'visibility' => 'public',
            'throw' => false,
        ],

        /**
         * Alternative: Stockage sur serveur distant via SFTP
         * Décommenter si vous utilisez un serveur distant
         */
        // 'external_media' => [
        //     'driver' => 'sftp',
        //     'host' => env('EXTERNAL_MEDIA_HOST', 'media-server.com'),
        //     'username' => env('EXTERNAL_MEDIA_USERNAME'),
        //     'password' => env('EXTERNAL_MEDIA_PASSWORD'),
        //     'port' => env('EXTERNAL_MEDIA_PORT', 22),
        //     'root' => env('EXTERNAL_MEDIA_ROOT', '/var/www/media'),
        //     'timeout' => 30,
        //     'directoryPerm' => 0755,
        //     'url' => env('EXTERNAL_MEDIA_URL'),
        // ],

        /**
         * Alternative: Stockage sur S3 ou service cloud
         * Décommenter si vous utilisez AWS S3 ou compatible
         */
        // 'external_media' => [
        //     'driver' => 's3',
        //     'key' => env('AWS_ACCESS_KEY_ID'),
        //     'secret' => env('AWS_SECRET_ACCESS_KEY'),
        //     'region' => env('AWS_DEFAULT_REGION'),
        //     'bucket' => env('AWS_MEDIA_BUCKET'),
        //     'url' => env('AWS_URL'),
        //     'endpoint' => env('AWS_ENDPOINT'),
        //     'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        // ],
    ],
];

/**
 * =============================================================================
 * CONFIGURATION .ENV
 * =============================================================================
 *
 * Ajoutez ces variables dans votre fichier .env:
 *
 * # Stockage Local Externe
 * EXTERNAL_MEDIA_PATH=/var/www/external-media
 * EXTERNAL_MEDIA_URL=https://media.tradipratic.com
 *
 * # OU Stockage SFTP
 * EXTERNAL_MEDIA_HOST=media-server.com
 * EXTERNAL_MEDIA_USERNAME=user
 * EXTERNAL_MEDIA_PASSWORD=password
 * EXTERNAL_MEDIA_PORT=22
 * EXTERNAL_MEDIA_ROOT=/var/www/media
 * EXTERNAL_MEDIA_URL=https://media.tradipratic.com
 *
 * # OU Stockage S3/Cloud
 * AWS_ACCESS_KEY_ID=your-key
 * AWS_SECRET_ACCESS_KEY=your-secret
 * AWS_DEFAULT_REGION=us-east-1
 * AWS_MEDIA_BUCKET=tradipratic-media
 * AWS_URL=https://s3.amazonaws.com
 */

/**
 * =============================================================================
 * STRUCTURE DES DOSSIERS RECOMMANDÉE
 * =============================================================================
 *
 * /var/www/external-media/
 * ├── images/
 * │   ├── 2024/
 * │   │   ├── 01/
 * │   │   ├── 02/
 * │   │   └── ...
 * │   ├── 2025/
 * │   └── ...
 * ├── videos/
 * │   ├── 2024/
 * │   └── ...
 * ├── products/
 * ├── hotels/
 * ├── realisations/
 * ├── recipes/
 * └── pub-services/
 */

/**
 * =============================================================================
 * NGINX CONFIGURATION (Serveur de médias)
 * =============================================================================
 *
 * server {
 *     listen 80;
 *     server_name media.tradipratic.com;
 *
 *     root /var/www/external-media;
 *     index index.html;
 *
 *     location / {
 *         try_files $uri $uri/ =404;
 *
 *         # Headers pour le cache
 *         expires 1y;
 *         add_header Cache-Control "public, immutable";
 *
 *         # CORS si nécessaire
 *         add_header Access-Control-Allow-Origin "*";
 *     }
 *
 *     # Sécurité: empêcher l'exécution de scripts
 *     location ~* \.(php|pl|py|jsp|asp|sh|cgi)$ {
 *         deny all;
 *     }
 *
 *     # Logs
 *     access_log /var/log/nginx/media-access.log;
 *     error_log /var/log/nginx/media-error.log;
 * }
 */

/**
 * =============================================================================
 * APACHE CONFIGURATION (Alternative à Nginx)
 * =============================================================================
 *
 * <VirtualHost *:80>
 *     ServerName media.tradipratic.com
 *     DocumentRoot /var/www/external-media
 *
 *     <Directory /var/www/external-media>
 *         Options -Indexes +FollowSymLinks
 *         AllowOverride None
 *         Require all granted
 *
 *         # Cache
 *         <FilesMatch "\.(jpg|jpeg|png|gif|ico|svg|mp4|webm|ogg)$">
 *             Header set Cache-Control "max-age=31536000, public, immutable"
 *         </FilesMatch>
 *
 *         # CORS
 *         Header set Access-Control-Allow-Origin "*"
 *
 *         # Sécurité
 *         <FilesMatch "\.(php|pl|py|jsp|asp|sh|cgi)$">
 *             Require all denied
 *         </FilesMatch>
 *     </Directory>
 *
 *     ErrorLog ${APACHE_LOG_DIR}/media-error.log
 *     CustomLog ${APACHE_LOG_DIR}/media-access.log combined
 * </VirtualHost>
 */

/**
 * =============================================================================
 * PERMISSIONS SYSTÈME
 * =============================================================================
 *
 * # Créer le dossier de stockage
 * sudo mkdir -p /var/www/external-media
 *
 * # Définir les permissions
 * sudo chown -R www-data:www-data /var/www/external-media
 * sudo chmod -R 755 /var/www/external-media
 *
 * # Pour les nouveaux fichiers
 * sudo chmod g+s /var/www/external-media
 */

/**
 * =============================================================================
 * UTILISATION DANS LES CONTRÔLEURS
 * =============================================================================
 *
 * use App\Services\MediaStorageService;
 *
 * class ProductController extends Controller
 * {
 *     protected $mediaService;
 *
 *     public function __construct(MediaStorageService $mediaService)
 *     {
 *         $this->mediaService = $mediaService;
 *     }
 *
 *     public function store(Request $request)
 *     {
 *         // Upload une image
 *         $imagePath = $this->mediaService->uploadImage(
 *             $request->file('image'),
 *             'products'
 *         );
 *
 *         // Upload plusieurs images
 *         $galleryPaths = $this->mediaService->uploadMultipleImages(
 *             $request->file('gallery'),
 *             'products/gallery'
 *         );
 *
 *         // Upload une vidéo
 *         $videoPath = $this->mediaService->uploadVideo(
 *             $request->file('video'),
 *             'products/videos'
 *         );
 *
 *         // Obtenir l'URL publique
 *         $imageUrl = $this->mediaService->getUrl($imagePath);
 *     }
 * }
 */

/**
 * =============================================================================
 * AVANTAGES DU STOCKAGE EXTERNE
 * =============================================================================
 *
 * 1. QUALITÉ PRÉSERVÉE
 *    - Aucune compression des images
 *    - Aucune perte de qualité vidéo
 *    - Fichiers originaux conservés
 *
 * 2. PERFORMANCE
 *    - Séparation du trafic média/application
 *    - Cache optimisé pour les médias statiques
 *    - Possibilité d'utiliser un CDN
 *
 * 3. SCALABILITÉ
 *    - Facilité d'ajout de serveurs de médias
 *    - Distribution géographique possible
 *    - Backup indépendant
 *
 * 4. SÉCURITÉ
 *    - Impossibilité d'exécuter du code côté média
 *    - Isolation des fichiers
 *    - Gestion des accès simplifiée
 */
