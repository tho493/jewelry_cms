<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'     => 'nullable|string',
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
            'name'        => 'tên sản phẩm',
            'price'       => 'giá',
            'category_id' => 'danh mục',
            'status'      => 'trạng thái',
        ];
    }
}
