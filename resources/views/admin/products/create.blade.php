@extends('layouts.admin')

@section('page-title', 'Thêm sản phẩm')

@section('topbar-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">&larr; Quay lại</a>
@endsection

@push('styles')
<style>
    .tox-tinymce { border-radius: 8px !important; border-color: var(--border) !important; }

    /* Flow steps */
    .flow-steps {
        display: flex;
        margin-bottom: 24px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border);
    }
    .flow-step {
        flex: 1; padding: 14px 18px;
        display: flex; align-items: center; gap: 12px;
        font-size: 13px;
    }
    .flow-step + .flow-step { border-left: 1px solid var(--border); }
    .flow-step-active  { background: rgba(201,168,76,0.08); }
    .flow-step-inactive { background: var(--surface); opacity: 0.55; }
    .step-num {
        width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700;
    }
    .flow-step-active .step-num   { background: var(--gold); color: #000; }
    .flow-step-inactive .step-num { background: var(--surface2); color: var(--muted); border: 1px solid var(--border); }
    .step-label   { font-weight: 600; }
    .step-sub     { font-size: 11px; color: var(--muted); margin-top: 1px; }
    .flow-step-active .step-label { color: var(--gold); }

    /* Media placeholder */
    .media-placeholder {
        border: 2px dashed var(--border);
        border-radius: 12px; padding: 48px 24px;
        text-align: center; color: var(--muted);
        background: rgba(255,255,255,0.015);
    }
    .media-placeholder .ph-icon { font-size: 44px; margin-bottom: 14px; opacity: 0.45; display: block; }
    .media-placeholder h4 { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .media-placeholder p  { font-size: 13px; line-height: 1.7; color: var(--muted); }
    .media-placeholder strong { color: var(--gold); }
</style>
@endpush

@section('content')

{{-- Step flow indicator --}}
<div class="flow-steps">
    <div class="flow-step flow-step-active">
        <div class="step-num">1</div>
        <div>
            <div class="step-label">Thông tin sản phẩm</div>
            <div class="step-sub">&#9654; Đang thực hiện</div>
        </div>
    </div>
    <div class="flow-step flow-step-inactive">
        <div class="step-num">2</div>
        <div>
            <div class="step-label">Upload ảnh / video / audio</div>
            <div class="step-sub">Sau khi tạo sản phẩm</div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.store') }}">
    @csrf

    <div class="grid-2">

        {{-- Left column --}}
        <div>
            {{-- Thông tin --}}
            <div class="card" style="margin-bottom:20px">
                <div class="card-header"><h3>Thông tin sản phẩm</h3></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Tên (Tiếng Việt)<span class="req">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Nhẫn vàng 18K kim cương..." required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
    
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Tên (Chữ Hán)</label>
                            <input type="text" name="name_hantu" class="form-control"
                                   value="{{ old('name_hantu') }}"
                                   placeholder="Ví dụ: 祥">
                            @error('name_hantu')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mô tả ngắn</label>
                        <input type="text" name="short_description" class="form-control"
                               value="{{ old('short_description') }}"
                               placeholder="Nhẫn đính kim cương thiên nhiên, chất liệu vàng 18K...">
                        @error('short_description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chữ chủ đạo</label>
                        <input type="text" name="main_character" class="form-control"
                               value="{{ old('main_character') }}"
                               placeholder="Ví dụ: Phúc">
                        @error('main_character')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Đặc điểm tạo hình</label>
                        <textarea name="form_characteristics" class="form-control tiny-editor" rows="5"
                                  placeholder="Chi tiết về thiết kế...">{{ old('form_characteristics') }}</textarea>
                        @error('form_characteristics')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Ý nghĩa văn hóa</label>
                        <textarea name="cultural_meaning" class="form-control tiny-editor" rows="5"
                                  placeholder="Ý nghĩa biểu tượng...">{{ old('cultural_meaning') }}</textarea>
                        @error('cultural_meaning')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" id="editor" class="form-control" rows="10"
                                  placeholder="Nhập nội dung...">{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card" style="margin-bottom:20px">
                <div class="card-header"><h3>SEO</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="seo_title" class="form-control"
                               value="{{ old('seo_title') }}"
                               placeholder="Nhẫn vàng 18K | Jewelry CMS">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">SEO Description</label>
                        <textarea name="seo_description" class="form-control" rows="3"
                                  placeholder="Mô tả ngắn gọn cho Google...">{{ old('seo_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Media placeholder --}}
            <div class="card">
                <div class="card-header">
                    <h3>&#128248; Ảnh / Video / Audio</h3>
                    <span style="font-size:12px;color:var(--muted);background:var(--surface2);
                                 padding:3px 10px;border-radius:99px;border:1px solid var(--border)">
                        Bước 2
                    </span>
                </div>
                <div class="card-body">
                    <div class="media-placeholder">
                        <span class="ph-icon">&#128444;&#65039;</span>
                        <h4>Upload media ở bước tiếp theo</h4>
                        <p>
                            Nhấn <strong>"Tạo sản phẩm"</strong> để lưu thông tin cơ bản.<br>
                            Bạn sẽ được chuyển đến trang chỉnh sửa để upload<br>
                            <strong>ảnh</strong>, <strong>video</strong> và <strong>audio</strong> cho sản phẩm.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="sticky-col">
            <div class="card" style="margin-bottom:20px">
                <div class="card-header"><h3>Thuộc tính</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-control">
                            <option value="">— Chọn danh mục —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mã sản phẩm</label>
                        <input type="text" name="product_code" class="form-control"
                               value="{{ old('product_code') }}"
                               placeholder="Bỏ trống để tự động sinh">
                        @error('product_code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá (VND)</label>
                        <input type="number" name="price" class="form-control"
                               value="{{ old('price') }}" placeholder="5000000" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Chất liệu</label>
                        <input type="text" name="material" class="form-control"
                               value="{{ old('material') }}"
                               placeholder="Vàng 18K, Bạch kim, Bạc 925...">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Trạng thái <span class="req">*</span></label>
                        <select name="status" class="form-control">
                            <option value="draft"      {{ old('status', 'draft') === 'draft'      ? 'selected' : '' }}>Ẩn</option>
                            <option value="published"  {{ old('status')           === 'published'  ? 'selected' : '' }}>Hiển thị</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;padding:12px 18px;font-size:15px;margin-bottom:8px">
                Tạo sản phẩm &rarr; Thêm media
            </button>
            <p style="font-size:12px;color:var(--muted);text-align:center;margin:0">
                Sau khi tạo, bạn sẽ chuyển sang <strong style="color:var(--text)">Bước 2</strong> để upload ảnh/video/audio
            </p>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#editor, .tiny-editor',
    plugins: 'anchor autolink lists link image table wordcount',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table',
    skin: 'oxide-dark',
    content_css: 'dark',
    min_height: 400,
});
</script>
@endpush
