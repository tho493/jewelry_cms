<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    protected $fillable = [
        'hero_label',
        'hero_title_line1',
        'hero_title_line2',
        'hero_description',
        'hero_btn_primary_text',
        'hero_btn_secondary_text',
        'featured_title',
        'featured_subtitle',
    ];

    /**
     * Lấy bản ghi duy nhất hoặc tạo mới với giá trị mặc định.
     */
    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'hero_label'              => 'Kiệt tác di sản',
            'hero_title_line1'        => 'Tinh Hoa',
            'hero_title_line2'        => 'Trang Sức',
            'hero_description'        => 'Tuyển tập những món bảo vật được hồi sinh từ dòng chảy Hán - Việt, chế tác thủ công với độ tinh xảo tuyệt đối dành riêng cho giới mộ điệu.',
            'hero_btn_primary_text'   => 'Khám phá tuyệt tác',
            'hero_btn_secondary_text' => 'Xem bộ sưu tập',
            'featured_title'          => 'Sản phẩm nổi bật',
            'featured_subtitle'       => 'Những thiết kế được yêu thích nhất',
        ]);
    }
}
