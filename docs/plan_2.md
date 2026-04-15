# 📸 Fix Media Upload Flow – Product Create & Edit

## Tóm tắt vấn đề

Hiện tại luồng upload ảnh/video/audio cho sản phẩm **chỉ hoạt động ở trang Edit**, nhưng **hoàn toàn thiếu tại trang Create**. Ngoài ra, có một số bugs tiềm ẩn trong phần AJAX upload ở trang Edit.

---

## 🔍 Phân tích hiện trạng

### ❌ Vấn đề 1: Trang `create.blade.php` không có Media Manager

- Form `create` chỉ có `method="POST"` thông thường — **không có AJAX, không có ảnh, video, audio**.
- Khi tạo sản phẩm mới, chưa có `product_id` → `UploadMediaRequest` yêu cầu `product_id` trước khi upload → **không thể upload trước khi lưu sản phẩm**.
- Thiết kế ban đầu (Phase 7) nói rằng create/edit phải có "Media upload section (AJAX, preview grid)".

**Giải pháp đề xuất:** Áp dụng luồng 2 bước:
1. Bước 1: Submit form tạo sản phẩm → nhận `product_id`
2. Bước 2: Redirect sang trang Edit (đã làm rồi `redirect()->route('admin.products.edit', $product)`) → **upload media tại edit**

→ Cần cải thiện UX: thêm thông báo hướng dẫn rõ hơn + thêm drop zone tạm thời ở create (nếu muốn preview trước).

**Hoặc phương án tốt hơn:** Cho phép upload media **trực tiếp trong cùng form create** bằng cách dùng **temporary upload** — lưu file vào temp session/storage trước, gắn vào product sau khi save.

> [!IMPORTANT]
> Phương án đơn giản nhất (ít rủi ro nhất): **Giữ nguyên luồng 2 bước** (create → redirect edit → upload), nhưng **cải thiện UI create** để người dùng hiểu rõ là cần upload ảnh sau khi tạo. Đồng thời **sửa tất cả bugs trong edit**.

---

### ❌ Vấn đề 2: AJAX Upload trong `edit.blade.php` có nhiều lỗi tiềm ẩn

| # | File | Vấn đề | Mức độ |
|---|------|---------|--------|
| 1 | `edit.blade.php` | TinyMCE dùng `env('TINY_CLOUD_KEY')` trong Blade — Laravel **không khuyến khích** `env()` trong view, phải dùng `config()` | 🟡 Medium |
| 2 | `edit.blade.php` | `mediaManager({{ $product->id }})` — nếu Alpine.js **chưa load** thì sẽ lỗi | 🟡 Medium |
| 3 | `edit.blade.php` | CSRF token trong AJAX dùng `document.querySelector('meta[name=csrf-token]').content` — nếu meta tag missing sẽ lỗi 419 | 🔴 Critical |
| 4 | `edit.blade.php` | Khi upload nhiều file cùng lúc (`for` loop + `await`), nếu file đầu thất bại → `uploading` vẫn bật mãi | 🟡 Medium |
| 5 | `edit.blade.php` | `uploadFiles` không có error handling — nếu server trả về lỗi 422 (validate fail) → silent fail, không hiện thông báo cho user | 🔴 Critical |
| 6 | `edit.blade.php` | Audio file upload không được preview đúng — dùng emoji 🎥 thay vì 🎵 | 🟡 Medium |
| 7 | `edit.blade.php` | Sortable hoạt động với cả `newMedia` (Alpine template) và Blade items, nhưng `newMedia` items dùng `:data-id` (dynamic binding) — Sortable có thể không đọc được đúng id | 🟡 Medium |
| 8 | `create.blade.php` | Không có Media Manager → Không thể upload ảnh khi tạo mới | 🔴 Critical |

### ❌ Vấn đề 3: `MediaService` thiếu xử lý lỗi khi file không phải ảnh đúng format

- `Image::read()` có thể throw exception nếu file corrupt — hiện chưa có try/catch

### ❌ Vấn đề 4: `UploadMediaRequest` có duplicate key

```php
// Vấn đề: 'file' key bị khai báo 2 lần → PHP chỉ giữ cái sau
'file' => 'required|file|max:102400',
'file' => ['mimes:...'],  // ← ghi đè key trên!
```

> [!WARNING]  
> Rule `max:102400` đã bị ghi đè bởi dòng khai báo sau — giới hạn file size 100MB không hoạt động!

---

## 📐 Proposed Changes

### Component 1: Form Validation & Request

#### [MODIFY] [UploadMediaRequest.php](file:///e:/jewelry_cms/app/Http/Requests/UploadMediaRequest.php)
- Merge `'file'` rules lại thành 1 array duy nhất (sửa duplicate key bug)

---

### Component 2: MediaService – Error Handling

#### [MODIFY] [MediaService.php](file:///e:/jewelry_cms/app/Services/MediaService.php)
- Thêm `try/catch` quanh `Image::read()` + `image->toWebp()->save()` để trả về lỗi có nghĩa thay vì 500
- Thêm MIME type `audio/x-m4a`, `video/webm` vào allowed lists

---

### Component 3: MediaController – Error Response  

#### [MODIFY] [MediaController.php](file:///e:/jewelry_cms/app/Http/Controllers/Admin/MediaController.php)
- `upload()`: Bọc trong try/catch, trả về JSON error nếu `MediaService->upload()` throw exception
- `destroy()`: Kiểm tra quyền — media phải thuộc product của admin (bảo mật)

---

### Component 4: Views – UI Fix

#### [MODIFY] [create.blade.php](file:///e:/jewelry_cms/resources/views/admin/products/create.blade.php)
- **Thêm** section "Media" với thông báo hướng dẫn rõ ràng ("Sau khi tạo sản phẩm, bạn có thể upload ảnh/video/audio")
- **Thêm** visual placeholder/preview area (disabled) để người dùng biết có thể upload media sau
- **Thêm** submit button hiện thị rõ flow: tạo sản phẩm → thêm media

#### [MODIFY] [edit.blade.php](file:///e:/jewelry_cms/resources/views/admin/products/edit.blade.php)
Sửa toàn bộ các lỗi phát hiện:

1. **Sửa** `env('TINY_CLOUD_KEY')` → `config('services.tinymce.key')` hoặc truyền qua Blade variable
2. **Sửa** error handling trong `uploadFiles()` — hiện thông báo lỗi nếu upload thất bại
3. **Sửa** audio preview — dùng emoji đúng (`🎵` vs `🎥`) 
4. **Sửa** `newMedia` items — thêm `x-bind:data-id` để Sortable đọc được
5. **Sửa** `uploading = false` trong finally block thay vì cuối loop
6. **Thêm** toast notification khi upload thành công/thất bại
7. **Thêm** accept attribute rõ ràng theo từng nút upload riêng: "Upload ảnh", "Upload video", "Upload audio"
8. **Sửa** TinyMCE key truyền qua PHP variable thay vì `env()`

---

### Component 5: Config

#### [MODIFY] [config/services.php](file:///e:/jewelry_cms/config/services.php)
- Thêm entry `'tinymce' => ['key' => env('TINY_CLOUD_KEY')]` để dùng `config()` thay `env()` trong view

---

## 🔄 Luồng hoạt động sau khi fix

### Luồng Create (2-step):
```
[Admin] → /admin/products/create
         → Điền thông tin + submit form
         → POST /admin/products → store()
         → Redirect → /admin/products/{id}/edit + flash message "Hãy thêm ảnh bên dưới"
         → [Upload Media via AJAX]
```

### Luồng Edit – Upload Media (AJAX):
```
[Admin] → Click "Upload ảnh/video/audio"
         → Chọn file(s)
         → POST /admin/media/upload (FormData: file + product_id)
         → MediaController::upload()
         → UploadMediaRequest validate
         → MediaService::upload() → store → thumbnail (nếu image)
         → JSON response: {success: true, media: {...}}
         → Alpine.js: push to newMedia[] → render preview
         → Hiện toast "Upload thành công"
```

### Luồng Edit – Lỗi Upload:
```
[Admin] chọn file sai format hoặc quá lớn
         → Server trả 422 hoặc JSON {success: false, message: ...}
         → Alpine.js: hiện toast lỗi đỏ rõ ràng
         → uploading = false (finally block)
```

---

## ✅ Verification Plan

### Manual Testing
1. Tạo sản phẩm mới → verify redirect sang edit + flash message hướng dẫn upload
2. Upload ảnh JPG/PNG/WEBP → verify thumbnail tạo ra, grid hiện preview
3. Upload video MP4 → verify emoji 🎥 đúng
4. Upload audio MP3 → verify emoji 🎵 đúng (không phải 🎥)
5. Upload file sai format (PDF, .exe) → verify thông báo lỗi rõ ràng
6. Upload file > 100MB → verify thông báo giới hạn kích thước
7. Kéo thả media để sắp xếp → verify thứ tự được lưu
8. Đặt ảnh cover → verify border vàng + lưu đúng
9. Xóa media → verify file biến mất khỏi grid và storage

### Automated Check (artisan tinker)
```php
// Kiểm tra UploadMediaRequest rules không bị duplicate key
php artisan tinker
>>> app(App\Http\Requests\UploadMediaRequest::class)->rules()
// Phải thấy 'file' chỉ có 1 entry với đầy đủ rules
```
