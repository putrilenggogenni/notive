@extends('layouts.app')
@section('title', 'Tulis Entri Baru')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar { border: none !important; border-bottom: 1px solid #E8E8E8 !important; padding: 8px 0 !important; }
    .ql-container { border: none !important; font-family: 'Lora', serif !important; font-size: 18px !important; }
    .ql-editor { padding: 16px 0 !important; min-height: 320px; line-height: 1.85 !important; color: #2a2a2a; }
    .ql-editor.ql-blank::before { font-style: normal !important; color: #bbb !important; font-family: 'Lora', serif; font-size: 18px; left: 0 !important; }
    .ql-editor img { max-width: 100%; border-radius: 8px; margin: 16px 0; display: block; }
    .ql-snow .ql-picker { font-family: 'Inter', sans-serif; font-size: 13px; }
    .ql-snow.ql-toolbar button:hover, .ql-snow .ql-toolbar button:hover { color: #5C5BE5; }
    .ql-snow.ql-toolbar button.ql-active { color: #5C5BE5; }
    .upload-image-btn { cursor: pointer; padding: 3px 6px; }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width: 720px;">

    <div class="mb-4">
        <a href="{{ route('entries.index') }}" style="font-size: 13px; color: #999; text-decoration: none;">Kembali ke semua entri</a>
    </div>

    <form method="POST" action="{{ route('entries.store') }}" enctype="multipart/form-data" id="entryForm">
        @csrf
        <input type="hidden" name="content" id="hiddenContent">

        <div class="mb-3">
            <input type="text" name="title"
                class="form-control @error('title') is-invalid @enderror"
                placeholder="Judul entri..."
                value="{{ old('title') }}"
                style="border: none; border-bottom: 1px solid #E8E8E8; border-radius: 0; font-family: 'Lora', serif; font-size: 26px; font-weight: 500; padding: 8px 0; background: transparent; letter-spacing: -0.5px;">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="mood" class="form-select" style="border-radius: 8px; font-size: 13px; border-color: #E8E8E8; color: #666;">
                    <option value="">Mood hari ini...</option>
                    @foreach(['Tenang','Senang','Bersemangat','Lelah','Sedih','Marah'] as $mood)
                        <option value="{{ $mood }}" {{ old('mood') == $mood ? 'selected' : '' }}>{{ $mood }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <div id="tagContainer" class="d-flex flex-wrap gap-2 align-items-center"
                    style="border: 1px solid #E8E8E8; border-radius: 8px; padding: 6px 12px; min-height: 40px; cursor: text;"
                    onclick="document.getElementById('tagInput').focus()">
                    <input type="text" id="tagInput" placeholder="Tambah tag, tekan Enter..."
                        style="border: none; outline: none; font-size: 13px; min-width: 160px; flex: 1; background: transparent; color: #666;">
                </div>
                <div id="tagHiddenInputs"></div>
            </div>
        </div>

        {{-- Quill Editor --}}
        <div style="border-top: 1px solid #E8E8E8; margin-bottom: 16px;">
            <div id="quillEditor"></div>
        </div>

        @error('content')
            <div class="text-danger mb-2" style="font-size: 13px;">{{ $message }}</div>
        @enderror

        {{-- Cover photo (opsional) --}}
        <div class="mb-4">
            <label style="font-size: 13px; color: #999; display: block; margin-bottom: 6px;">Foto cover (opsional)</label>
            <input type="file" name="photo" id="photoInput" accept="image/*"
                class="form-control @error('photo') is-invalid @enderror"
                style="border-radius: 8px; font-size: 13px; border-color: #E8E8E8;">
            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div id="photoPreview" class="mt-2"></div>
        </div>

        {{-- Hidden file input for inline image upload --}}
        <input type="file" id="inlineImageInput" accept="image/*" style="display: none;">

        <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-n" style="padding: 10px 24px;">Simpan Entri</button>
            <a href="{{ route('entries.index') }}" class="btn-outline-n" style="padding: 10px 24px;">Batal</a>
        </div>

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// Custom inline image upload handler
function insertInlineImage(quill) {
    const input = document.getElementById('inlineImageInput');
    input.click();
    input.onchange = async () => {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const res = await fetch('{{ route("entries.upload-image") }}', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();
            const range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'image', data.url);
            quill.setSelection(range.index + 1);
        } catch (e) {
            alert('Gagal upload gambar. Coba lagi.');
        }
        input.value = '';
    };
}

const quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Ceritakan harimu...',
    modules: {
        toolbar: {
            container: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ],
            handlers: {
                image: function() {
                    insertInlineImage(this.quill);
                }
            }
        }
    }
});

// Submit form
document.getElementById('entryForm').addEventListener('submit', function(e) {
    const content = quill.root.innerHTML;
    if (quill.getText().trim().length === 0) {
        e.preventDefault();
        alert('Konten tidak boleh kosong.');
        return;
    }
    document.getElementById('hiddenContent').value = content;
});

// Tag system
const tagInput = document.getElementById('tagInput');
const tagContainer = document.getElementById('tagContainer');
const tagHiddenInputs = document.getElementById('tagHiddenInputs');
let tags = [];

function addTag(name) {
    name = name.trim().toLowerCase().replace(/[^a-z0-9\-]/g, '');
    if (!name || tags.includes(name)) return;
    tags.push(name);
    const pill = document.createElement('span');
    pill.className = 'tag-pill d-flex align-items-center gap-1';
    pill.dataset.tag = name;
    pill.innerHTML = `#${name} <span style="cursor:pointer; font-size:15px; line-height:1;" onclick="removeTag('${name}')">×</span>`;
    tagContainer.insertBefore(pill, tagInput);
    const hidden = document.createElement('input');
    hidden.type = 'hidden'; hidden.name = 'tags[]'; hidden.value = name; hidden.id = 'tag_' + name;
    tagHiddenInputs.appendChild(hidden);
}

function removeTag(name) {
    tags = tags.filter(t => t !== name);
    const pill = tagContainer.querySelector(`[data-tag="${name}"]`);
    if (pill) pill.remove();
    const hidden = document.getElementById('tag_' + name);
    if (hidden) hidden.remove();
}

tagInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); addTag(tagInput.value); tagInput.value = ''; }
});

// Cover photo preview
document.getElementById('photoInput').addEventListener('change', function() {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    if (this.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(this.files[0]);
        img.style = 'max-height: 200px; border-radius: 8px; object-fit: cover;';
        preview.appendChild(img);
    }
});
</script>
@endpush
@endsection