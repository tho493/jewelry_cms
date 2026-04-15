@extends('layouts.admin')

@section('page-title', 'Sửa: ' . $product->name)

@section('topbar-actions')
    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm">&#128065;
        Xem</a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">&larr; Danh sách</a>
@endsection

@push('styles')
    <style>
        .tox-tinymce {
            border-radius: 8px !important;
            border-color: var(--border) !important;
        }

        /* Toast notification */
        #upload-toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            transition: opacity 0.3s, transform 0.3s;
            opacity: 0;
            transform: translateY(12px);
            pointer-events: none;
            max-width: 340px;
        }

        #upload-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        #upload-toast.toast-success {
            background: rgba(76, 175, 125, 0.95);
            color: #fff;
        }

        #upload-toast.toast-error {
            background: rgba(224, 82, 82, 0.95);
            color: #fff;
        }

        /* Upload drop zone */
        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            margin-bottom: 16px;
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: var(--gold);
            background: rgba(201, 168, 76, 0.04);
            color: var(--gold);
        }

        .upload-zone .zone-icon {
            font-size: 28px;
            margin-bottom: 6px;
            display: block;
        }

        /* Progress bar */
        .progress-bar-wrap {
            background: var(--surface2);
            border-radius: 99px;
            height: 6px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, var(--gold-dark), var(--gold));
            height: 6px;
            border-radius: 99px;
            transition: width 0.3s ease;
        }

        /* Media grid items */
        .media-item {
            cursor: grab;
        }

        .media-item:active {
            cursor: grabbing;
        }

        .media-type-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Upload section buttons */
        .upload-btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('admin.products.update', $product) }}" id="product-form">
        @csrf @method('PUT')

        <div class="grid-2">

            {{-- Left column --}}
            <div>
                {{-- Thông tin --}}
                <div class="card" style="margin-bottom:20px">
                    <div class="card-header">
                        <h3>Thông tin sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Tên sản phẩm <span class="req">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                                required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" value="{{ $product->slug }}" disabled
                                style="opacity:.5;cursor:default">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả ngắn</label>
                            <input type="text" name="short_description" class="form-control"
                                value="{{ old('short_description', $product->short_description) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea name="description" id="editor" class="form-control"
                                rows="12">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card" style="margin-bottom:20px">
                    <div class="card-header">
                        <h3>SEO</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">SEO Title</label>
                            <input type="text" name="seo_title" class="form-control"
                                value="{{ old('seo_title', $product->seo_title) }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">SEO Description</label>
                            <textarea name="seo_description" class="form-control"
                                rows="3">{{ old('seo_description', $product->seo_description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ═══════ Media Manager ═══════ --}}
                <div class="card" x-data="mediaManager({{ $product->id }})">
                    <div class="card-header">
                        <h3>
                            &#128248; Media
                            <span style="font-weight:400;color:var(--muted);font-size:13px"
                                x-text="'(' + ({{ $product->media->count() }} + newMedia.length) + ' files)'">
                                ({{ $product->media->count() }} files)
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">

                        {{-- Upload buttons --}}
                        <div class="upload-btn-group">
                            <label class="btn btn-secondary btn-sm" style="cursor:pointer"
                                title="Hỗ trợ: JPG, PNG, WEBP, GIF">
                                &#128444; Ảnh
                                <input type="file" multiple accept="image/jpeg,image/png,image/webp,image/gif"
                                    @change="uploadFiles($event)" style="display:none">
                            </label>
                            <label class="btn btn-secondary btn-sm" style="cursor:pointer"
                                title="Hỗ trợ: MP4, MOV, AVI, WEBM">
                                &#127909; Video
                                <input type="file" multiple accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                    @change="uploadFiles($event)" style="display:none">
                            </label>
                            <label class="btn btn-secondary btn-sm" style="cursor:pointer" title="Hỗ trợ: MP3, WAV, M4A">
                                &#127925; Audio
                                <input type="file" multiple accept="audio/mpeg,audio/wav,audio/mp4,audio/x-m4a"
                                    @change="uploadFiles($event)" style="display:none">
                            </label>
                        </div>

                        {{-- Drop zone --}}
                        <div class="upload-zone" @dragover.prevent="$el.classList.add('drag-over')"
                            @dragleave="$el.classList.remove('drag-over')"
                            @drop.prevent="$el.classList.remove('drag-over'); uploadFiles($event, true)"
                            @click="$el.querySelector('input').click()">
                            <span class="zone-icon">&#128229;</span>
                            Kéo thả file vào đây hoặc click để chọn
                            <input type="file" multiple accept="image/*,video/*,audio/*" @change="uploadFiles($event)"
                                style="display:none">
                        </div>

                        {{-- Progress bar --}}
                        <div x-show="uploading" style="margin-bottom:16px" x-cloak>
                            <div
                                style="font-size:13px;color:var(--muted);margin-bottom:6px;display:flex;justify-content:space-between">
                                <span>Đang tải lên...</span>
                                <span x-text="uploadProgress + '%'"></span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" :style="{ width: uploadProgress + '%' }"></div>
                            </div>
                        </div>

                        {{-- Error message --}}
                        <div x-show="uploadError" x-cloak style="background:rgba(224,82,82,0.12);border:1px solid rgba(224,82,82,0.3);
                                    color:var(--danger);border-radius:8px;padding:10px 14px;
                                    font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:8px">
                            &#9888; <span x-text="uploadError"></span>
                            <button type="button" @click="uploadError=''"
                                style="margin-left:auto;background:none;border:none;color:var(--danger);cursor:pointer">&#10005;</button>
                        </div>

                        {{-- Media grid --}}
                        <div id="media-grid"
                            style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px">

                            {{-- Existing media (server-rendered) --}}
                            @foreach($product->media as $m)
                                <div class="media-item" data-id="{{ $m->id }}" style="position:relative;border-radius:10px;overflow:hidden;
                                            border:2px solid {{ $m->is_cover ? 'var(--gold)' : 'var(--border)' }};
                                            background:var(--surface2)">

                                    {{-- Type badge --}}
                                    <div class="media-type-badge">{{ $m->type }}</div>

                                    @if($m->type === 'image')
                                        <img src="{{ $m->thumbnail_url }}"
                                            style="width:100%;height:110px;object-fit:cover;display:block" alt="{{ $m->alt_text }}">
                                    @elseif($m->type === 'video')
                                        <div
                                            style="height:110px;display:flex;align-items:center;justify-content:center;font-size:36px">
                                            &#127909;</div>
                                    @else
                                        <div
                                            style="height:110px;display:flex;align-items:center;justify-content:center;font-size:36px">
                                            &#127925;</div>
                                    @endif

                                    <div style="padding:6px 8px;display:flex;gap:4px">
                                        @if($m->type === 'image')
                                            <button type="button" onclick="setCover({{ $m->id }}, this)" class="btn btn-sm"
                                                title="Đặt làm ảnh bìa" style="flex:1;padding:4px;font-size:11px;
                                                           background:{{ $m->is_cover ? 'var(--gold)' : 'var(--surface)' }};
                                                           color:{{ $m->is_cover ? '#000' : 'var(--muted)' }};
                                                           border:1px solid var(--border)">&#9733;</button>
                                        @endif
                                        <button type="button" onclick="deleteMedia({{ $m->id }}, this)"
                                            class="btn btn-danger btn-sm"
                                            style="flex:1;padding:4px;font-size:11px">&#10005;</button>
                                    </div>
                                </div>
                            @endforeach

                            {{-- New uploads (Alpine) --}}
                            <template x-for="item in newMedia" :key="item.id">
                                <div class="media-item" :data-id="item.id" style="position:relative;border-radius:10px;overflow:hidden;
                                            border:2px solid var(--border);background:var(--surface2)">

                                    <div class="media-type-badge" x-text="item.type"></div>

                                    <img :src="item.thumbnail_url"
                                        style="width:100%;height:110px;object-fit:cover;display:block"
                                        x-show="item.type === 'image'">
                                    <div x-show="item.type === 'video'"
                                        style="height:110px;display:flex;align-items:center;justify-content:center;font-size:36px">
                                        &#127909;</div>
                                    <div x-show="item.type === 'audio'"
                                        style="height:110px;display:flex;align-items:center;justify-content:center;font-size:36px">
                                        &#127925;</div>

                                    <div style="padding:6px 8px;display:flex;gap:4px">
                                        <button type="button" @click="setCoverNew(item)" class="btn btn-sm"
                                            x-show="item.type === 'image'" style="flex:1;padding:4px;font-size:11px;
                                                       background:var(--surface);color:var(--muted);
                                                       border:1px solid var(--border)">&#9733;</button>
                                        <button type="button" @click="deleteMediaNew(item)" class="btn btn-danger btn-sm"
                                            style="flex:1;padding:4px;font-size:11px">&#10005;</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Empty state --}}
                        <p x-show="newMedia.length === 0 && {{ $product->media->count() }} === 0"
                            style="color:var(--muted);font-size:13px;text-align:center;padding:16px 0;margin:0">
                            Chưa có media. Click vào nút hoặc kéo thả file để upload.
                        </p>

                    </div>{{-- /card-body --}}
                </div>{{-- /media card --}}

            </div>{{-- /left --}}

            {{-- Right column --}}
            <div>
                <div class="card" style="margin-bottom:20px">
                    <div class="card-header">
                        <h3>Thuộc tính</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-control">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giá (VND)</label>
                            <input type="number" name="price" class="form-control"
                                value="{{ old('price', $product->price) }}" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chất liệu</label>
                            <input type="text" name="material" class="form-control"
                                value="{{ old('material', $product->material) }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Trạng thái <span class="req">*</span></label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Bản
                                    nháp</option>
                                <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>Xuất bản</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;margin-bottom:12px;padding:12px 18px;font-size:15px">
                    &#128190; Lưu thay đổi
                </button>

                {{-- Nút xóa: dùng form= attribute, KHÔNG lồng form trong form --}}
                <button type="submit" form="delete-product-form" class="btn btn-danger"
                    style="width:100%;justify-content:center"
                    onclick="return confirm('Xóa sản phẩm này? Tất cả media cũng sẽ bị xóa!')">
                    &#128465; Xóa sản phẩm
                </button>
            </div>

        </div>
    </form>

    {{-- Form xóa đặt NGOÀI form cập nhật — tránh nested form bug --}}
    <form id="delete-product-form" method="POST" action="{{ route('admin.products.destroy', $product) }}"
        style="display:none">
        @csrf @method('DELETE')
    </form>

    {{-- Toast notification --}}
    <div id="upload-toast"></div>

@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // ── TinyMCE ───────────────────────────────────────────────
        tinymce.init({
            selector: '#editor',
            plugins: 'anchor autolink lists link image table wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter | bullist numlist | link image | table',
            skin: 'oxide-dark',
            content_css: 'dark',
            min_height: 400,
        });

        // ── Helpers ───────────────────────────────────────────────
        const CSRF = () => document.querySelector('meta[name=csrf-token]')?.content ?? '';

        function showToast(msg, type = 'success') {
            const el = document.getElementById('upload-toast');
            el.textContent = (type === 'success' ? '✓ ' : '⚠ ') + msg;
            el.className = 'show toast-' + type;
            clearTimeout(el._timer);
            el._timer = setTimeout(() => { el.className = ''; }, 3500);
        }

        // ── Sortable media grid (sync both Blade + Alpine items) ──
        const mediaGrid = document.getElementById('media-grid');
        new Sortable(mediaGrid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd() {
                const ids = [...mediaGrid.querySelectorAll('.media-item')]
                    .map(el => el.dataset.id || el.getAttribute(':data-id'))
                    .filter(Boolean);
                fetch('{{ route("admin.media.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF(),
                    },
                    body: JSON.stringify({ ids }),
                }).catch(() => showToast('Sắp xếp thất bại', 'error'));
            },
        });

        // ── Set cover (Blade items) ────────────────────────────────
        function setCover(id, btn) {
            fetch(`/admin/media/${id}/cover`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF() },
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) { showToast('Đã đặt ảnh bìa'); location.reload(); }
                    else showToast(d.message ?? 'Thất bại', 'error');
                })
                .catch(() => showToast('Lỗi kết nối', 'error'));
        }

        // ── Delete media (Blade items) ─────────────────────────────
        function deleteMedia(id, btn) {
            if (!confirm('Xóa file này?')) return;
            fetch(`/admin/media/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF() },
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        btn.closest('.media-item').remove();
                        showToast('Đã xóa file');
                    } else {
                        showToast(d.message ?? 'Xóa thất bại', 'error');
                    }
                })
                .catch(() => showToast('Lỗi kết nối', 'error'));
        }

        // ── Alpine.js – Media Manager ──────────────────────────────
        function mediaManager(productId) {
            return {
                uploading: false,
                uploadProgress: 0,
                uploadError: '',
                newMedia: [],

                async uploadFiles(event, isDrop = false) {
                    const files = [...(isDrop ? event.dataTransfer.files : event.target.files)];
                    if (!files.length) return;

                    this.uploading = true;
                    this.uploadProgress = 0;
                    this.uploadError = '';

                    try {
                        for (let i = 0; i < files.length; i++) {
                            const fd = new FormData();
                            fd.append('file', files[i]);
                            fd.append('product_id', productId);

                            let res, data;
                            try {
                                res = await fetch('{{ route("admin.media.upload") }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': CSRF() },
                                    body: fd,
                                });
                                data = await res.json();
                            } catch (networkErr) {
                                this.uploadError = `Lỗi mạng khi tải "${files[i].name}, hãy kiểm tra lại dung lượng của file bạn tải lên"`;
                                showToast(this.uploadError, 'error');
                                continue;
                            }

                            if (res.ok && data.success) {
                                this.newMedia.push(data.media);
                                showToast(`Đã tải lên: ${files[i].name}`);
                            } else {
                                // Validation errors (422) or server errors (500)
                                const msg = data?.message
                                    ?? data?.errors?.file?.[0]
                                    ?? `Tải lên thất bại: ${files[i].name}`;
                                this.uploadError = msg;
                                showToast(msg, 'error');
                            }

                            this.uploadProgress = Math.round(((i + 1) / files.length) * 100);
                        }
                    } finally {
                        this.uploading = false;
                        // Reset file inputs so user can re-select same files if needed
                        event.target && (event.target.value = '');
                    }
                },

                async setCoverNew(item) {
                    const res = await fetch(`/admin/media/${item.id}/cover`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': CSRF() },
                    });
                    const data = await res.json();
                    if (data.success) { showToast('Đã đặt ảnh bìa'); location.reload(); }
                    else showToast(data.message ?? 'Thất bại', 'error');
                },

                async deleteMediaNew(item) {
                    if (!confirm('Xóa file này?')) return;
                    const res = await fetch(`/admin/media/${item.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF() },
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.newMedia = this.newMedia.filter(m => m.id !== item.id);
                        showToast('Đã xóa file');
                    } else {
                        showToast(data.message ?? 'Xóa thất bại', 'error');
                    }
                },
            };
        }
    </script>
@endpush