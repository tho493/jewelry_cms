# Kế Hoạch Triển Khai Đa Ngôn Ngữ UI (Frontend Interface) cho Jewelry CMS

Mục tiêu: Cho phép hệ thống quản trị (Admin) **tạo và quản lý danh sách ngôn ngữ động** để chuyển đổi trên Front-end. Người dùng xem website (Public Frontend) có thể chuyển đổi ngôn ngữ thông qua thẻ menu.
Việc chuyển đổi ngôn ngữ **CHỈ ÁP DỤNG TRÊN GIAO DIỆN PUBLIC (Frontend)** đối với các chuỗi hiển thị tĩnh (thanh menu, nút bấm, tiêu đề footer, nhãn dán, vv...). 
**LƯU Ý QUAN TRỌNG**: 
- **Không áp dụng đa ngôn ngữ cho Admin**: Toàn bộ giao diện Admin và nội dung nhập liệu (Sản phẩm, Danh mục, Slider, About) đều chỉ dùng duy nhất 1 ngôn ngữ (ví dụ Tiếng Việt). Hoàn toàn không có tabs ngôn ngữ hay chuyển đổi ngôn ngữ ở trang quản trị CMS.
- Đảm bảo tối ưu SEO đường dẫn (URL) qua các file cấu hình.

Kế hoạch này không bao gồm cài đặt chi tiết mã nguồn, mà phác thảo đầy đủ quy trình các bước thao tác trên hệ thống.

---

## 1. Công Cụ & Thư Viện Kiến Trúc (Tech Stack)

Để quá trình thiết kế Đa Ngôn Ngữ sạch sẽ nhất:

1. **`mcamara/laravel-localization`**: Giúp quản lý cấu trúc routing **URL chuẩn SEO** trên Public Frontend. Tự động chèn prefix mã ngôn ngữ (VD: `yousite.com/vi/san-pham` và `yousite.com/en/products`). Giải quyết bài toán SEO đa khu vực thẻ `<link rel="alternate" hreflang="x">`.
2. **Hệ thống Translation mặc định của Laravel (`lang/*.json` hoặc `lang/*/*.php`)**: Giải quyết các **Dữ liệu tĩnh (Static Strings)** trên giao diện Public: Footer, thanh menu "Trang chủ", các nút bấm "Xem tất cả", nhãn thông báo hệ thống, giỏ hàng, v.v.

*(Lưu ý: Không sử dụng bất kỳ JSON translation data hay packages cho Database).*

---

## 2. Quản Lý Danh Sách Giao Diện Ngôn Ngữ (Admin Language Setting)

Thay vì hardcode danh sách ngôn ngữ (`vi`, `en`) vào file config, **Admin CMS là nơi duy nhất quản lý ngôn ngữ**. Hệ thống sẽ đọc danh sách ngôn ngữ từ database và tự động cấu hình ứng dụng (config) khi cần.

### 2.1. Bảng `languages` trong Database

Tạo migration mới cho bảng quản lý ngôn ngữ (đã hoàn thiện):

| Cột | Kiểu | Mô tả |
|-----|------|---------|
| `id` | `bigint` | Primary key |
| `code` | `varchar(10)` | Mã ngôn ngữ ISO 639-1 (vd: `vi`, `en`, `zh`, `ja`) |
| `name` | `varchar(100)` | Tên đầy đủ (vd: `Vietnamese`) |
| `native_name` | `varchar(100)` | Tên bản ngữ (vd: `Tiếng Việt`) |
| `flag_emoji` | `varchar(10)` | Icon lá cờ (vd: `🇻🇳`) |
| `is_default` | `boolean` | Ngôn ngữ mặc định (chỉ 1 ngôn ngữ được phép) |
| `is_active` | `boolean` | Bật/tắt hiển thị trên frontend switcher |
| `sort_order` | `integer` | Thứ tự hiển thị trong dropdown |
| `timestamps` | — | `created_at`, `updated_at` |

### 2.2. Service Provider (LanguageServiceProvider)

Boot ở thời điểm app khởi động (đã hoàn thiện một phần):
1. Load danh sách ngôn ngữ active từ DB (có cache `Cache::rememberForever`).
2. Tự động chèn mảng hỗ trợ ngôn ngữ vào `laravel-localization.supportedLocales`.
3. Đặt `defaultLocale` là ngôn ngữ có `is_default = true`.
4. Clear cache mỗi lần Admin thao tác Thêm/Sửa/Xóa cấu hình ngôn ngữ.

---

## 3. Quản Lý & Dịch Thuật Chuỗi Cứng (Static Strings UI)

Vì chúng ta chỉ thay đổi ngôn ngữ giao diện (như label, cấu trúc text fix cứng), cần áp dụng Laravel Localization JSON files:

- Thay thế tất cả chuỗi văn bản tĩnh ở **Frontend (Trang chủ, Chi tiết SP, Giỏ hàng, Footer)** sử dụng helper `__()`. Ví dụ:
  Thay vì `<button>Thêm vào giỏ</button>`
  Ta viết: `<button>{{ __('Thêm vào giỏ') }}</button>`
- Cung cấp các file ngôn ngữ như `/lang/vi.json` và `/lang/en.json` chứa các key-value map để hệ thống nạp tự động vào Frontend.

*(Phần này Admin hiện tại có thể không cần chỉnh sửa trực tiếp trên CMS mà can thiệp thẳng vào hệ thống JSON file, hoặc tương lai có thể làm Module "Translation Manager" nếu người dùng cần sửa).*

---

## 4. Tích hợp Giao Diện Public (Frontend) và Chuẩn Tối Ưu SEO

Trang phục vụ khách hàng cuối sử dụng package điều phối đường dẫn:

- **Routing URL Group:** Chuyển toàn bộ các route hiển thị của Public (Web) bọc vào trong middleware group `['localize', 'localeSessionRedirect', 'localizationRedirect']` của thư viện `laravel-localization`.
- **Phản hồi linh hoạt:** Thư viện sẽ sinh `<link rel="alternate" hreflang="...">` vào thẻ `<head>` một cách thông minh, tối ưu SEO.

---

## 5. Lựa Chọn Ngôn Ngữ Ưu Tiên và Dropdown Chuyển Đổi (Language Switcher)

Ngôn ngữ ưu tiên quyết định giao diện hiển thị cho người xem:

### 5.1. Cơ Chế Bắt URL/Cookie

Áp dụng cơ chế ưu tiên đã tích hợp sẵn của package:
1. Xét qua **URL Prefix** (`/vi/`, `/en/`) -> Quyết định locale tức thì.
2. Kiểm tra Session hoặc Cookie.
3. Fallback đọc `Accept-Language` của Browser.
4. Fallback cuối cùng là ngôn ngữ `is_default` được cấu hình trong bảng `languages`.

### 5.2. Giao Diện Language Switcher (Dropdown)

Thiết kế component chọn ngôn ngữ hiển thị trên **Thanh Menu Header (Frontend)**:
- Lặp qua mảng `$activeLanguages` được cung cấp mặc định.
- Khi người dùng click chọn: Redirect lại đúng đường dẫn hiện tại nhưng thay đổi prefix (Sử dụng hàm helper `LaravelLocalization::getLocalizedURL($langCode)`).

Ví dụ cấu trúc:
```blade
<div class="lang-switcher">
    @foreach($activeLanguages as $lang)
        <a href="{{ LaravelLocalization::getLocalizedURL($lang->code) }}"
           class="{{ app()->getLocale() === $lang->code ? 'active' : '' }}">
            {{ $lang->flag_emoji }} {{ strtoupper($lang->code) }}
        </a>
    @endforeach
</div>
```

---

## 6. Lộ Trình Triển Khai (Timeline Cập Nhật Lại)

- [x] **Bước 1**: Tạo cấu trúc DB `languages` và model quản trị ngôn ngữ.
- [x] **Bước 2**: Code Web Admin khu vực quản trị Ngôn Ngữ, Cấu hình Sync Provide cache (`LanguageServiceProvider`).
- [x] **Bước 3**: (Không cần Undo) Xác nhận các Model không sử dụng `spatie/laravel-translatable`, Admin UI luôn là 1 ngôn ngữ độc lập.
- [x] **Bước 4**: Bọc Middleware đa ngôn ngữ cho toàn bộ Route ở giao diện Frontend Public (`localeSessionRedirect`, `localizationRedirect`, `localeViewPath`).
- [x] **Bước 5**: Thêm bộ Dropdown "Language Switcher" vào Public Header và Mobile Nav (hiển thị khi có ≥ 2 ngôn ngữ active).
- [x] **Bước 6**: Thay thế toàn bộ mã code chữ fix cứng trên các file Blade Frontend qua Helper `__()` và tổ chức file `lang/vi.json` và `lang/en.json`.
  - `layouts/public.blade.php`: splash tagline, nav, footer, hreflang SEO
  - `public/home.blade.php`: title, meta\_description
  - `public/products/index.blade.php`: title, meta\_description, filter labels
  - `public/products/show.blade.php`: breadcrumb, meta labels, audio, buttons
  - `public/about/index.blade.php`: title, fallback texts, team heading
  - `public/categories/show.blade.php`: breadcrumb, count text, empty state, contact label
