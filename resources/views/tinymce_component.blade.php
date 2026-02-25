{{--
    Composant TinyMCE Editor
    Usage: @include('components.tinymce', ['name' => 'description', 'value' => $model->description ?? ''])
--}}

<div class="mb-3">
    <label class="form-label">{{ $label ?? 'Description' }} @if($required ?? false)<span class="text-danger">*</span>@endif</label>
    <textarea
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-control tinymce-editor @error($name) is-invalid @enderror"
        {{ ($required ?? false) ? 'required' : '' }}>{{ old($name, $value ?? '') }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($help ?? false)
        <small class="text-muted">{{ $help }}</small>
    @endif
</div>

@once
@push('styles')
<style>
    .tox-tinymce {
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.mce.com/1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '.tinymce-editor',
        height: 400,
        menubar: true,

        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],

        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image media link | code fullscreen | help',

        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; }',

        // Configuration images
        images_upload_url: '{{ route("admin.upload.image") }}', // Route pour upload
        images_upload_handler: function (blobInfo, success, failure) {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("admin.upload.image") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.location) {
                    success(result.location);
                } else {
                    failure('Erreur lors de l\'upload de l\'image');
                }
            })
            .catch(error => {
                failure('Erreur réseau: ' + error);
            });
        },

        // Configuration en français
        language: 'fr_FR',
        language_url: 'https://cdn.tiny.mce.com/1/tinymce/6/langs/fr_FR.js',

        // Options supplémentaires
        branding: false,
        promotion: false,
        resize: true,
        elementpath: false,

        // Formats personnalisés
        style_formats: [
            { title: 'Titres', items: [
                { title: 'Titre 1', format: 'h1' },
                { title: 'Titre 2', format: 'h2' },
                { title: 'Titre 3', format: 'h3' },
                { title: 'Titre 4', format: 'h4' },
            ]},
            { title: 'Texte', items: [
                { title: 'Paragraphe', format: 'p' },
                { title: 'Citation', format: 'blockquote' },
                { title: 'Code', format: 'code' },
            ]}
        ],

        // Validation
        invalid_elements: 'script,iframe',

        // Configuration médias
        media_alt_source: false,
        media_poster: false,

        setup: function(editor) {
            editor.on('init', function() {
                console.log('TinyMCE initialisé');
            });
        }
    });
});
</script>
@endpush
@endonce
