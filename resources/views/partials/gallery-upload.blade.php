{{--
    Composant d'upload de galerie multiple

    Usage:
    @include('partials.gallery-upload', [
        'name' => 'gallery',
        'label' => 'Galerie d\'images',
        'currentGallery' => $hotel->gallery ?? null,
        'help' => 'Vous pouvez sélectionner plusieurs images'
    ])
--}}

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <input
        type="file"
        class="form-control @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}[]"
        accept="image/*"
        multiple
        onchange="previewGallery(this, '{{ $name }}_preview')"
    >

    @if($help ?? false)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    {{-- Prévisualisation de la galerie existante --}}
    @if(isset($currentGallery) && $currentGallery)
        <div class="mt-3">
            <p class="text-muted small mb-2">Galerie actuelle :</p>
            <div class="row g-2">
                @php
                    $gallery = is_array($currentGallery) ? $currentGallery : json_decode($currentGallery, true);
                @endphp
                @if($gallery)
                    @foreach($gallery as $image)
                        <div class="col-md-3">
                            <img
                                src="{{ asset('storage/' . $image) }}"
                                alt="Image galerie"
                                class="img-thumbnail"
                                style="width: 100%; height: 150px; object-fit: cover;"
                            >
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    {{-- Prévisualisation des nouvelles images --}}
    <div id="{{ $name }}_preview" class="mt-3 row g-2" style="display: none;"></div>
</div>

@push('scripts')
<script>
function previewGallery(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (input.files) {
        const files = Array.from(input.files);

        if (files.length > 0) {
            preview.style.display = 'flex';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.width = '100%';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';

                        col.appendChild(img);
                        preview.appendChild(col);
                    }

                    reader.readAsDataURL(file);
                }
            });
        }
    }
}
</script>
@endpush
