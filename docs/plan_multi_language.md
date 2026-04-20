# Kế Hoạch Triển Khai Đa Ngôn Ngữ (Multi-Language) cho Jewelry CMS

Mục tiêu: Cho phép hệ thống quản trị (Admin) nhập liệu bằng nhiều ngôn ngữ (Tiếng Việt, Tiếng Anh) và người dùng (Frontend) có thể chuyển đổi ngôn ngữ truy cập nhưng vẫn đảm bảo tối ưu SEO đường dẫn (URL).

Kế hoạch này không bao gồm cài đặt chi tiết mã nguồn, mà phác thảo đầy đủ quy trình các bước thao tác trên toàn bộ hệ thống.

---

## 1. Công Cụ & Thư Viện Kiến Trúc (Tech Stack)

Để quá trình thiết kế Đa Ngôn Ngữ sạch sẽ nhất và không cần phải nhân bản (duplicate) các dòng trong CSDL, chúng ta sẽ áp dụng các Package nổi bật sau đây của Laravel:

1. **`spatie/laravel-translatable`**: Xử lý nội dung **Dữ liệu động (Dynamic Data)** trong cở sở dữ liệu. Nó chuyển đổi các cột text thành kiểu dữ liệu `JSON` để lưu song song cả tiếng Việt lẫn tiếng Anh trên cùng 1 record (ví dụ: `{"vi": "Nhẫn ngọc", "en": "Jade Ring"}`).
2. **`mcamara/laravel-localization`**: Giúp quản lý cấu trúc routing **URL chuẩn SEO**. Tự động chèn prefix mã ngôn ngữ (VD: `yousite.com/vi/san-pham` và `yousite.com/en/products`). Giải quyết bài toán SEO đa khu vực thẻ `<link rel="alternate" hreflang="x">`.
3. **File `lang/vi.json` & `lang/en.json`**: Giải quyết các **Dữ liệu tĩnh (Static Strings)** trên giao diện: Footer, thanh menu "Trang chủ", nút bấm "Xem tất cả", v.v.

---

## 2. Giai Đoạn Thay Đổi Cấu Trúc Database (Migrations)

Chúng ta cần tạo các file Migration để nâng cấp **thay đổi kiểu dữ liệu (data type)** cột từ `string/text` sang `json` cho các bảng cần dịch.

### Những bảng cần thay đổi:
- **`products`**: `name`, `slug`, `short_description`, `description`, `name_hantu`, `main_character`, `form_characteristics`, `cultural_meaning`, `material`, `seo_title`, `seo_description`.
- **`categories`**: `name`, `slug`.
- **`home_settings`**: `hero_label`, `hero_title_line1`, `hero_title_line2`, `hero_description`, `hero_btn_primary_text`, `hero_btn_secondary_text`, `featured_title`, `featured_subtitle`.
- **`home_slides`**: `caption`.
- **`abouts`**: `content`.

> [!WARNING]  
> Cần backup dữ liệu hiện tại trước. Sau đó dùng artisan command để loop qua từng record cũ và chuyển format từ dạng text thô sang dạng JSON `{ "vi": "giá trị cũ" }` để tránh mất dữ liệu.

---

## 3. Giai Đoạn Nâng Cấp Models

Với mọi Model tương ứng, ta tiến hành thao tác cấu hình:

- Thêm Trait `HasTranslations` do thư viện Spatie cung cấp.
- Khai báo property `$translatable = ['cột_cần_dịch_1', 'cột_cần_dịch_2', ...]` để báo cho Laravel biết các biến này sẽ tự động phân giải tùy theo ngôn ngữ.
- Đối với `slug` của Categories và Products, cấu hình lại chức năng auto-generate slug sinh ra 2 loại URL song song: Cả `slug-tieng-viet` lẫn `slug-english`.

---

## 4. Cải Tạo Giao Diện Admin Box (CMS)

Tại khu vực Admin, người quản trị cần có khả năng điền Data cho nhiều ngôn ngữ khi Thêm/Sửa sản phẩm.

- **Thiết kế lại Form HTML**: Đối với mỗi trường hợp cần dịch (ví dụ: Tên sản phẩm), thay vì 1 khung input duy nhất, ta sẽ sử dụng cấu trúc **Tab (Tab Tiếng Việt | Tab Tiếng Anh)** hoặc làm input có đính kèm icon lá cờ 🇻🇳/🇬🇧.
- **Form Request Validation**: Cập nhật logic để validate mảng dữ liệu. Thay vì xác thực field `name`, ta cần xác thực qua cú pháp `name.vi` (Yêu cầu bắt buộc) và `name.en` (Có thể tùy chọn nếu admin chưa có nội dung tiếng Anh ngay).

---

## 5. Nâng Cấp Giao Diện Public (Frontend) và SEO

Trang phục vụ khách hàng cuối sẽ trải qua các bước tinh chỉnh lớn:

- **Language Switcher (Dropdown đổi ngôn ngữ):** Bổ sung nút bấm trên thanh Menu (Header) đại diện cho ngôn ngữ hiện tại và list chọn để khách hàng đổi sang tiếng Anh.
- **Routing URL Group:** Chuyển toàn bộ các route hiển thị của Public (như `/san-pham`, `/gioi-thieu`) bọc vào trong group của thư viện `laravel-localization`. Điều này sẽ giúp tự động sinh ra các tiền tố `/vi/` hoặc `/en/`.
- **Cập nhật Blade View**: Các chữ cứng (Mã SP:, Xem thêm:, Liện hệ:) bọc với helper function của Laravel `__()` (vd: `__('Code')`).
- **SEO Thuận tiện:** Thư viện sẽ hỗ trợ sinh các cụm cấu trúc siêu dữ liệu `<link rel="alternate" hreflang="en" href="domain/en">` vào thẻ Head một cách tự nhiên.

> [!TIP]  
> Do kiến trúc Spatie cực kì thông minh, đoạn mã cũ ngoài views như `{{ $product->name }}` gần như sẽ KHÔNG cần phải sửa đổi gì, thư viện tự lo việc nhận diện khách đang view `en` thì lấy `tên tiếng Anh`, khách view `vi` thì tự nhận `tên tiếng Việt` cho field đó. Rất an toàn và tiết kiệm thời gian.

## 6. Lộ Trình Chạy (Timeline Ước Tính)

- **Bước 1**: Cấu hình `composer`, config và Localization tĩnh (15%). 
- **Bước 2**: Thực thi Migration DB an toàn + update Models (25%). 
- **Bước 3**: Thi công cập nhật giao diện và Controller phía Admin cho các Form nhập liệu (40%).
- **Bước 4**: Tích hợp Frontend Switcher URL, Test Route và Hoàn thiện (20%).
