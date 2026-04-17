@extends('layouts.admin')

@section('page-title', 'Trang chủ (Home Page)')

@push('styles')
    <style>
        .slide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
        }

        .slide-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid var(--border);
            background: var(--surface2);
            cursor: grab;
        }

        .slide-item:active {
            cursor: grabbing;
        }

        .slide-item img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        .slide-caption-input {
            width: 100%;
            background: transparent;
            border: none;
            border-top: 1px solid var(--border);
            color: var(--text);
            font-size: 12px;
            padding: 6px 10px;
            font-family: inherit;
            outline: none;
        }

        .slide-caption-input::placeholder {
            color: var(--muted);
        }

        .slide-delete-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .slide-delete-btn:hover {
            background: var(--danger);
        }

        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            margin-bottom: 16px;
        }

        .upload-zone:hover {
            border-color: var(--gold);
            background: rgba(201, 168, 76, 0.04);
            color: var(--gold);
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('admin.home.update') }}">
        @csrf @method('PUT')

        <div class="grid-2">
            {{-- LEFT: Text content --}}
            <div>
                <div class="card" style="margin-bottom:20px">
                    <div class="card-header">
                        <h3>Nội dung Hero</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Nhãn nhỏ phía trên tiêu đề</label>
                            <input type="text" name="hero_label" class="form-control"
                                value="{{ old('hero_label', $setting->hero_label) }}" placeholder="Ví dụ: Kiệt tác di sản">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Tiêu đề dòng 1</label>
                                <input type="text" name="hero_title_line1" class="form-control"
                                    value="{{ old('hero_title_line1', $setting->hero_title_line1) }}">
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Tiêu đề dòng 2 (màu vàng)</label>
                                <input type="text" name="hero_title_line2" class="form-control"
                                    value="{{ old('hero_title_line2', $setting->hero_title_line2) }}">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px">
                            <label class="form-label">Đoạn mô tả</label>
                            <textarea name="hero_description" class="form-control" rows="4"
                                placeholder="Đoạn văn mô tả ngắn...">{{ old('hero_description', $setting->hero_description) }}</textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Nút chính</label>
                                <input type="text" name="hero_btn_primary_text" class="form-control"
                                    value="{{ old('hero_btn_primary_text', $setting->hero_btn_primary_text) }}">
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Nút phụ</label>
                                <input type="text" name="hero_btn_secondary_text" class="form-control"
                                    value="{{ old('hero_btn_secondary_text', $setting->hero_btn_secondary_text) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Khu vực Sản phẩm Nổi bật</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Tiêu đề mục</label>
                            <input type="text" name="featured_title" class="form-control"
                                value="{{ old('featured_title', $setting->featured_title) }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Phụ đề</label>
                            <input type="text" name="featured_subtitle" class="form-control"
                                value="{{ old('featured_subtitle', $setting->featured_subtitle) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Sticky actions --}}
            <div class="sticky-col">
                <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;padding:12px;font-size:15px;margin-bottom:12px">
                    Lưu thay đổi
                </button>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary"
                    style="width:100%;justify-content:center">
                    Xem trang chủ
                </a>
            </div>
        </div>
    </form>

    {{-- SLIDE MANAGER (tách ngoài form để tránh conflict CSRF multi-level) --}}
    <div class="card" style="margin-top:24px" x-data="slideManager()">
        <div class="card-header">
            <h3>Ảnh Slider Hero</h3>
            <span style="font-size:12px;color:var(--muted)">Kéo thả để sắp xếp thứ tự</span>
        </div>
        <div class="card-body">
            {{-- Upload zone --}}
            <div class="upload-zone" @click="$refs.fileInput.click()"
                @dragover.prevent="$el.style.borderColor='var(--gold)'" @dragleave.prevent="$el.style.borderColor=''"
                @drop.prevent="dropFiles($event)">
                <span style="font-size:28px;display:block;margin-bottom:6px">&#128229;</span>
                Kéo thả hoặc click để tải ảnh lên
            </div>
            <input type="file" x-ref="fileInput" class="hidden" accept="image/*" multiple @change="uploadFiles($event)"
                style="display:none">

            <div x-show="uploading" style="color:var(--gold);font-size:13px;margin-bottom:12px">Đang tải lên...</div>

            {{-- Slide grid (existing) --}}
            <div class="slide-grid" id="slide-grid">
                @foreach($slides as $slide)
                    <div class="slide-item" data-id="{{ $slide->id }}">
                        <img src="{{ $slide->image_url }}" alt="">
                        <button type="button" class="slide-delete-btn" onclick="deleteSlide({{ $slide->id }}, this)">✕</button>
                        <input type="text" class="slide-caption-input" placeholder="Chú thích ảnh..."
                            value="{{ $slide->caption }}" onchange="updateSlideCaption({{ $slide->id }}, this)">
                    </div>
                @endforeach

                {{-- new uploads --}}
                <template x-for="s in newSlides" :key="s.id">
                    <div class="slide-item" :data-id="s.id">
                        <img :src="s.image_url" alt="">
                        <button type="button" class="slide-delete-btn" @click="deleteSlideNew(s)">✕</button>
                        <input type="text" class="slide-caption-input" placeholder="Chú thích ảnh..." x-model="s.caption"
                            @change="updateSlideCaptionNew(s)">
                    </div>
                </template>
            </div>

            <p x-show="newSlides.length === 0 && {{ $slides->count() }} === 0"
                style="color:var(--muted);font-size:13px;text-align:center;padding:20px 0;margin:0">
                Chưa có ảnh nào. Upload ảnh để hiển thị slider trên trang chủ.
            </p>
        </div>
    </div>

    <div id="home-toast"></div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        const CSRF = () => document.querySelector('meta[name=csrf-token]')?.content ?? '';

        // Toast
        const toast = (msg, type = 'success') => {
            const el = document.getElementById('home-toast');
            el.textContent = (type === 'success' ? '✓ ' : '⚠ ') + msg;
            el.className = 'show toast-' + type;
            el.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:9999;padding:12px 20px;border-radius:10px;font-size:14px;font-weight:500;box-shadow:0 8px 32px rgba(0,0,0,0.4);background:${type === 'success' ? 'rgba(76,175,125,0.95)' : 'rgba(224,82,82,0.95)'};color:#fff;`;
            clearTimeout(el._t);
            el._t = setTimeout(() => el.style.opacity = 0, 3000);
        };

        // Slide Caption (Blade items)
        function updateSlideCaption(id, input) {
            fetch(`/admin/home/slides/${id}`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF(), 'Content-Type': 'application/json' },
                body: JSON.stringify({ caption: input.value })
            }).then(r => r.json()).then(d => d.success ? toast('Đã lưu chú thích') : toast(d.message, 'error'));
        }

        // Delete slide (Blade items)
        function deleteSlide(id, btn) {
            if (!confirm('Xóa ảnh này?')) return;
            fetch(`/admin/home/slides/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF() }
            }).then(r => r.json()).then(d => {
                if (d.success) { btn.closest('.slide-item').remove(); toast('Đã xóa ảnh'); }
                else toast(d.message, 'error');
            });
        }

        // Sortable
        const grid = document.getElementById('slide-grid');
        new Sortable(grid, {
            animation: 150,
            onEnd() {
                const ids = [...grid.querySelectorAll('.slide-item')]
                    .map(el => el.dataset.id).filter(Boolean);
                fetch('{{ route("admin.home.slides.reorder") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF() },
                    body: JSON.stringify({ ids })
                });
            }
        });

        // Alpine
        function slideManager() {
            return {
                uploading: false,
                newSlides: [],

                async uploadFiles(event) {
                    const files = [...event.target.files];
                    if (!files.length) return;
                    this.uploading = true;
                    for (const file of files) {
                        const fd = new FormData();
                        fd.append('image', file);
                        try {
                            const res = await fetch('{{ route("admin.home.slides.upload") }}', {
                                method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF() }, body: fd
                            });
                            const data = await res.json();
                            if (data.success) { this.newSlides.push(data.slide); toast('Đã tải lên: ' + file.name); }
                            else toast(data.message ?? 'Tải lên thất bại', 'error');
                        } catch { toast('Lỗi mạng', 'error'); }
                    }
                    this.uploading = false;
                    event.target.value = '';
                },

                dropFiles(event) {
                    this.$refs.fileInput.files = event.dataTransfer.files;
                    this.uploadFiles({ target: this.$refs.fileInput });
                },

                async deleteSlideNew(s) {
                    if (!confirm('Xóa ảnh này?')) return;
                    const res = await fetch(`/admin/home/slides/${s.id}`, {
                        method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF() }
                    });
                    const data = await res.json();
                    if (data.success) { this.newSlides = this.newSlides.filter(x => x.id !== s.id); toast('Đã xóa ảnh'); }
                    else toast(data.message, 'error');
                },

                async updateSlideCaptionNew(s) {
                    await fetch(`/admin/home/slides/${s.id}`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': CSRF(), 'Content-Type': 'application/json' },
                        body: JSON.stringify({ caption: s.caption })
                    });
                    toast('Đã lưu chú thích');
                }
            };
        }
    </script>
@endpush