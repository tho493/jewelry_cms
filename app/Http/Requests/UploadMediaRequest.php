<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'       => [
                'required',
                'file',
                'max:102400', // 100MB
                'mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm,mp3,wav,m4a',
            ],
            'product_id' => 'required|exists:products,id',
            'alt_text'   => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'file'       => 'tệp tin',
            'product_id' => 'sản phẩm',
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Chỉ hỗ trợ: JPG, PNG, WEBP, GIF, MP4, MOV, AVI, MP3, WAV, M4A.',
            'file.max'   => 'Kích thước tệp tối đa là 100MB.',
        ];
    }
}
