<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'product_code'    => 'nullable|string|max:100|unique:products,product_code',
            'short_description' => 'nullable|string|max:500',
            'description'     => 'nullable|string',
            'name_hantu'      => 'nullable|string|max:255',
            'main_character'  => 'nullable|string|max:50',
            'form_characteristics' => 'nullable|string',
            'cultural_meaning' => 'nullable|string',
            'price'           => 'nullable|numeric|min:0',
            'material'        => 'nullable|string|max:255',
            'category_id'     => 'nullable|exists:categories,id',
            'status'          => 'required|in:draft,published',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'tên sản phẩm',
            'product_code'    => 'mã sản phẩm',
            'name_hantu'      => 'tên hán việt',
            'main_character'  => 'chữ chủ đạo',
            'form_characteristics' => 'đặc điểm tạo hình',
            'cultural_meaning' => 'ý nghĩa văn hóa',
            'price'           => 'giá',
            'category_id'     => 'danh mục',
            'status'          => 'trạng thái',
        ];
    }
}
