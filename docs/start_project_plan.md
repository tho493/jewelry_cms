# 💎 Jewelry CMS – Chi Tiết Implementation Plan

## 📌 Trạng thái hiện tại
- Laravel 13 đã được cài đặt tại `e:\jewelry_cms\jewelry_cms`
- Database: MySQL (`jewelry_cms`, user `root`, no password)
- Chưa cài: Breeze, Intervention Image, Purifier
- Migrations mặc định đã có: `users`, `cache`, `jobs`
- Queue driver: `database` (không cần Redis)

---

## 🗂️ Cấu trúc thư mục mục tiêu

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── MediaController.php
│   │   │   └── DashboardController.php
│   │   └── Public/
│   │       ├── HomeController.php
│   │       ├── ProductController.php
│   │       └── CategoryController.php
│   ├── Requests/
│   │   ├── StoreProductRequest.php
│   │   ├── UpdateProductRequest.php
│   │   └── UploadMediaRequest.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php (có sẵn)
│   ├── Product.php
│   ├── Category.php
│   └── Media.php
├── Services/
│   ├── ProductService.php
│   └── MediaService.php
└── Jobs/
    └── ProcessMediaJob.php (optional)

resources/
├── views/
│   ├── layouts/
│   │   ├── admin.blade.php
│   │   └── public.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── products/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── categories/
│   │   │   ├── index.blade.php
│   │   │   └── create.blade.php
│   │   └── media/
│   │       └── index.blade.php
│   └── public/
│       ├── home.blade.php
│       ├── products/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── categories/
│           └── show.blade.php

storage/app/public/
├── products/
│   ├── images/
│   ├── videos/
│   └── audios/
```

---

## 📦 Phase 1 – Setup & Foundation
> **Ước tính: ~1 ngày**

### 1.1 Cài đặt packages
```bash
# Auth
composer require laravel/breeze

# Image processing
composer require intervention/image-laravel

# HTML Sanitizer
composer require mews/purifier

# Slug tự động
composer require spatie/laravel-sluggable

# Install Breeze (Blade stack)
php artisan breeze:install blade
```

### 1.2 Cấu hình .env
```env
DB_DATABASE=jewelry_cms
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
```

### 1.3 Storage link
```bash
php artisan storage:link
```

### 1.4 Cập nhật User model — thêm trường `role`
- Migration: thêm column `role` vào bảng `users` (default: `'user'`)
- Chỉ có `role = 'admin'` mới vào được admin panel

---

## 🗄️ Phase 2 – Database Migrations & Models
> **Ước tính: ~0.5 ngày**

### 2.1 Migrations cần tạo

#### `create_categories_table`
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

#### `add_role_to_users_table`
```php
$table->string('role')->default('user')->after('password');
```

#### `create_products_table`
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('short_description')->nullable();
    $table->longText('description')->nullable(); // HTML content
    $table->decimal('price', 15, 0)->nullable();  // VND không cần decimal
    $table->string('material')->nullable();
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->string('seo_title')->nullable();
    $table->text('seo_description')->nullable();
    $table->timestamps();
});
```

#### `create_media_table`
```php
Schema::create('media', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['image', 'video', 'audio']);
    $table->string('file_path');         // storage path (relative)
    $table->string('thumbnail_path')->nullable();
    $table->string('alt_text')->nullable();
    $table->string('caption')->nullable();
    $table->boolean('is_cover')->default(false); // ảnh cover của product
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

### 2.2 Models

| Model | Quan hệ |
|-------|---------|
| `Category` | hasMany Products |
| `Product` | belongsTo Category, hasMany Media |
| `Media` | belongsTo Product |
| `User` | — |

**Lưu ý quan trọng:**
- `Product` dùng `Spatie\Sluggable` để tự tạo slug từ `name`
- `Media` có accessor `url` trả về `Storage::url($this->file_path)`

---

## 🔐 Phase 3 – Authentication & Authorization
> **Ước tính: ~0.5 ngày**

### 3.1 Breeze đã cài → Login/Register có sẵn

### 3.2 AdminMiddleware
```php
// app/Http/Middleware/AdminMiddleware.php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403);
    }
    return $next($request);
}
```

### 3.3 Register middleware trong `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => AdminMiddleware::class,
    ]);
})
```

### 3.4 Seeder tạo admin user
```php
User::create([
    'name'     => 'Admin',
    'email'    => 'admin@jewelry.com',
    'password' => Hash::make('password'),
    'role'     => 'admin',
]);
```

---

## 🛣️ Phase 4 – Routes
> **Ước tính: ~0.5 ngày**

### `routes/web.php` layout

```php
// ── Public Routes ──────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [PublicProductController::class, 'show'])->name('products.show');
Route::get('/danh-muc/{slug}', [PublicCategoryController::class, 'show'])->name('categories.show');

// ── Auth Routes (Breeze) ───────────────────────────
require __DIR__.'/auth.php';

// ── Admin Routes ───────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Media
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::patch('media/{media}/cover', [MediaController::class, 'setCover'])->name('media.cover');
    Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');
});
```

---

## ⚙️ Phase 5 – Services Layer
> **Ước tính: ~1 ngày**

### 5.1 `ProductService`
```php
class ProductService
{
    public function store(array $data): Product { ... }
    public function update(Product $product, array $data): Product { ... }
    public function delete(Product $product): void { ... }
    public function getPublished(int $perPage = 12): LengthAwarePaginator { ... }
}
```

### 5.2 `MediaService`
```php
class MediaService
{
    public function upload(UploadedFile $file, Product $product): Media { ... }
    public function generateThumbnail(string $imagePath): string { ... }
    public function delete(Media $media): void {
        Storage::disk('public')->delete($media->file_path);
        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }
        $media->delete();
    }
}
```

**Upload flow trong `MediaService::upload()`:**
1. Xác định type (image / video / audio) từ MIME
2. Store file vào `products/{type}s/{product_id}/`
3. Nếu là image → dùng Intervention Image để resize + tạo thumbnail
4. Lưu record vào bảng `media`

---

## 📁 Phase 6 – Media Upload System
> **Ước tính: ~1 ngày**

### 6.1 Validation (`UploadMediaRequest`)
```php
public function rules(): array
{
    return [
        'file'       => 'required|file|max:102400', // 100MB
        'file'       => [
            'mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,mp3,wav,m4a',
        ],
        'product_id' => 'required|exists:products,id',
        'alt_text'   => 'nullable|string|max:255',
        'caption'    => 'nullable|string|max:500',
    ];
}
```

### 6.2 Image Processing (Intervention Image)
```php
use Intervention\Image\Laravel\Facades\Image;

// Resize ảnh gốc (max width 1920px)
$image = Image::read($file->getRealPath());
$image->scaleDown(width: 1920);
$image->save(storage_path('app/public/' . $path));

// Thumbnail (400x400, crop)
$thumb = Image::read($file->getRealPath());
$thumb->cover(400, 400);
$thumb->save(storage_path('app/public/' . $thumbPath));
```

### 6.3 File structure trong storage
```
storage/app/public/products/
├── images/{product_id}/
│   ├── {uuid}.webp
│   └── thumbnails/{uuid}_thumb.webp
├── videos/{product_id}/
│   └── {uuid}.mp4
└── audios/{product_id}/
    └── {uuid}.mp3
```

### 6.4 AJAX Upload (Alpine.js)
- Upload từng file với `fetch()` hoặc `axios`
- Hiển thị progress bar
- Preview ngay sau khi upload
- Kéo thả sắp xếp thứ tự (SortableJS)

---

## 🎨 Phase 7 – Admin UI (Blade + Alpine.js)
> **Ước tính: ~2 ngày**

### 7.1 Layout Admin (`layouts/admin.blade.php`)
- Sidebar navigation (Dashboard, Products, Categories)
- Header với user info + logout
- Flash messages (success/error)
- Dark/Light mode toggle

### 7.2 Dashboard
- Thống kê: tổng sản phẩm, danh mục, media
- Sản phẩm mới nhất
- Quick actions

### 7.3 Product CRUD
- **Index**: datatable với filter (category, status), search, pagination
- **Create/Edit**: 
  - Form cơ bản (name, price, material, category, status)
  - Rich text editor: **TinyMCE** (CDN) hoặc **CKEditor 5**
  - Media upload section (AJAX, preview grid)
  - SEO section (title, description)
- **Show**: preview public page

### 7.4 Media Manager (trong product edit)
```
┌─────────────────────────────────────────┐
│  [+ Upload Images] [+ Upload Video]      │
│                                          │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐       │
│  │img 1│ │img 2│ │img 3│ │ + │          │
│  │[★] │ │[✕] │ │[✕] │ │   │           │
│  └─────┘ └─────┘ └─────┘ └─────┘       │
│          ↑ kéo thả để sắp xếp           │
└─────────────────────────────────────────┘
```

### 7.5 Package JS cần dùng (qua CDN/npm)
- **TinyMCE** hoặc **CKEditor 5** – Rich text editor
- **SortableJS** – Kéo thả media
- **Alpine.js** – Reactivity (đã có qua Breeze)

---

## 🌐 Phase 8 – Public Frontend (Blade)
> **Ước tính: ~1.5 ngày**

### 8.1 Layout (`layouts/public.blade.php`)
- Header: logo, navigation (Trang chủ, Sản phẩm, Liên hệ)
- Footer: thông tin công ty
- Responsive (CSS thuần hoặc Bootstrap 5)

### 8.2 Trang chủ (`/`)
- **Hero banner**: ảnh lớn + tagline
- **Featured products**: 8 sản phẩm nổi bật
- **Categories section**: grid danh mục
- **About section**: giới thiệu thương hiệu

### 8.3 Trang danh sách sản phẩm (`/san-pham`)
- Filter theo danh mục
- Sort: mới nhất, giá tăng/giảm
- Pagination (12 sp/trang)
- Card: ảnh cover + tên + giá

### 8.4 Trang chi tiết sản phẩm (`/san-pham/{slug}`)
- Gallery ảnh (lightbox)
- Video player (nếu có video)
- Mô tả HTML (render từ TinyMCE)
- Thông tin: giá, chất liệu, danh mục
- SEO meta tags động

### 8.5 SEO
- `<title>` từ `seo_title` hoặc `name`
- `<meta description>` từ `seo_description` hoặc `short_description`
- Open Graph tags
- Schema.org markup (Product schema)

---

## 🔒 Phase 9 – Security
> **Ước tính: ~0.5 ngày**

### 9.1 HTML Sanitization (mews/purifier)
```php
// Khi lưu description
$data['description'] = clean($request->description);
```

### 9.2 Upload Security
- Validate MIME type thực sự (không chỉ extension)
- Giới hạn file size
- Lưu file với tên random (UUID), không dùng tên gốc

### 9.3 CSRF
- Tất cả form POST/PUT/DELETE đều có `@csrf`
- AJAX request gửi `X-CSRF-TOKEN` header

---

## 🧪 Phase 10 – Testing & Seeding
> **Ước tính: ~0.5 ngày**

### 10.1 Seeders
- `CategorySeeder`: 5-10 danh mục mẫu
- `ProductSeeder`: 20-30 sản phẩm mẫu
- `MediaSeeder`: gắn ảnh placeholder vào sản phẩm

### 10.2 Factories
- `ProductFactory`
- `CategoryFactory`

### 10.3 Test thủ công
- Upload ảnh → kiểm tra file trong `storage/app/public`
- CRUD sản phẩm
- Truy cập URL public
- Kiểm tra SEO tags

---

## 📅 Lịch thực hiện đề xuất

| Phase | Nội dung | Thời gian |
|-------|----------|-----------|
| Phase 1 | Setup, packages, cấu hình | Ngày 1 |
| Phase 2 | Migrations, Models | Ngày 1 |
| Phase 3 | Auth, Admin middleware | Ngày 2 |
| Phase 4 | Routes | Ngày 2 |
| Phase 5 | Services Layer | Ngày 2–3 |
| Phase 6 | Media upload system | Ngày 3 |
| Phase 7 | Admin UI | Ngày 4–5 |
| Phase 8 | Public Frontend | Ngày 5–6 |
| Phase 9 | Security | Ngày 6 |
| Phase 10 | Testing, Seeding | Ngày 7 |

**Tổng: ~1 tuần** nếu làm full-time.

---

## 📦 Packages tổng hợp cần cài

```bash
# Production
composer require laravel/breeze
composer require intervention/image-laravel
composer require mews/purifier
composer require spatie/laravel-sluggable

# Dev
npm install sortablejs
```

> **Không cần Redis** – dùng `QUEUE_CONNECTION=database` (đã có `jobs` table)

---

## ⚠️ Lưu ý quan trọng

> [!IMPORTANT]
> - Chạy `php artisan storage:link` trước khi upload bất kỳ file nào
> - Chạy `php artisan migrate` sau mỗi migration mới
> - Seeder tạo admin trước khi test admin panel

> [!WARNING]
> - Laravel 13 đang được dùng (`laravel/framework: ^13.0`) — một số package có thể chưa tương thích. Cần kiểm tra version khi cài.
> - Intervention Image v3+ cần `intervention/image-laravel` (không phải `intervention/image` v2)

> [!TIP]
> - Dùng `php artisan make:model Product -mfc` để tạo model + migration + factory + controller cùng lúc
> - Thứ tự migrate quan trọng: `categories` → `products` → `media` (do foreign key)
