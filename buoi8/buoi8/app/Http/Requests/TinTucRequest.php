<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TinTucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tin = $this->route('tin');
        $id = is_object($tin) ? $tin->id : $tin;

        return [
            'tieude'            => ['required', 'string', 'max:200'],
            'slug'              => ['nullable', 'string', 'max:255', "unique:tin_tucs,slug,{$id}"],
            'tomtat'            => ['nullable', 'string', 'max:300'],
            'noidung'           => ['required', 'string'],
            'ngaydang'          => ['nullable', 'date'],
            'trang_thai'        => ['nullable', 'in:draft,published'],
            'danhmuc_id'        => ['nullable', 'exists:danh_mucs,id'],
            'hinhanh_up'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery_images.*'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'tieude.required'           => 'Tiêu đề bắt buộc.',
            'tieude.max'                => 'Tiêu đề tối đa :max ký tự.',
            'slug.unique'               => 'Slug bài viết đã tồn tại.',
            'tomtat.max'                => 'Tóm tắt tối đa :max ký tự.',
            'noidung.required'          => 'Nội dung bắt buộc.',
            'trang_thai.in'             => 'Trạng thái không hợp lệ.',
            'danhmuc_id.exists'         => 'Danh mục đã chọn không tồn tại.',
            'hinhanh_up.image'          => 'Tệp tải lên phải là hình ảnh.',
            'hinhanh_up.mimes'          => 'Định dạng cho phép: jpg, jpeg, png, webp.',
            'hinhanh_up.max'            => 'Kích thước tối đa 2MB.',
            'gallery_images.*.image'    => 'Ảnh phụ phải là tệp hình ảnh.',
            'gallery_images.*.max'      => 'Kích thước mỗi ảnh phụ tối đa 2MB.',
        ];
    }
}
