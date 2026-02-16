{{--
    Composant d'upload d'image avec prévisualisation

    Usage:
    @include('partials.image-upload', [
        'name' => 'image',
        'label' => 'Image principale',
        'required' => true,
        'currentImage' => $hotel->image ?? null,
        'help' => 'Taille maximale: 10MB. Formats acceptés: JPG, PNG, GIF'
    ])
--}}

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input
        type="file"
        class="form-control @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
        accept="image/*"
        onchange="previewImage(this, '{{ $name }}_preview')"
        {{ ($required ?? false) ? 'required' : '' }}
    >

    @if($help ?? false)
        <div class="form-text">{{ $help }}</div>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    {{-- Prévisualisation --}}
    <div id="{{ $name }}_preview_container" class="mt-3" style="{{ isset($currentImage) && $currentImage ? '' : 'display: none;' }}">
        <p class="text-muted small mb-2">Prévisualisation :</p>
        <img
            id="{{ $name }}_preview"
            src="{{ isset($currentImage) && $currentImage ? asset('storage/' . $currentImage) : '' }}"
            alt="Prévisualisation"
            class="img-thumbnail"
            style="max-width: 300px; max-height: 300px;"
        >
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const container = document.getElementById(previewId + '_container');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
