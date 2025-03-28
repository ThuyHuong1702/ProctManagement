<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi request này không.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng gửi request này
    }

    /**
     * Định nghĩa các quy tắc xác thực cho request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|gte:0',
            'special_price' => 'nullable|numeric|gte:0',
            'special_price_type' => 'nullable|in:1,2', // 1: Fixed, 2: Percent
            'special_price_start' => 'nullable|date|before_or_equal:special_price_end',
            'special_price_end' => 'nullable|date|after_or_equal:special_price_start',
            'sku' => 'nullable|string|max:50',
            'manage_stock' => 'boolean',
            'qty' => 'nullable|integer|gte:0',
            'in_stock' => 'boolean',
            'is_active' => 'boolean',
            'new_from' => 'nullable|date',
            'new_to' => 'nullable|date|after_or_equal:new_from',

            // Xác thực biến thể (nếu có)
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string|max:191',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.price' => 'required_with:variants|numeric|gte:0',
            'variants.*.special_price' => 'nullable|numeric|gte:0',
            'variants.*.special_price_type' => 'nullable|in:1,2',
            'variants.*.special_price_start' => 'nullable|date|before_or_equal:variants.*.special_price_end',
            'variants.*.special_price_end' => 'nullable|date|after_or_equal:variants.*.special_price_start',
            'variants.*.manage_stock' => 'boolean',
            'variants.*.qty' => 'nullable|integer|gte:0',
            'variants.*.in_stock' => 'boolean',
            'variants.*.is_active' => 'boolean',
            'variants.*.is_default' => 'boolean',
        ];
    }

    /**
     * Định nghĩa thông báo lỗi tùy chỉnh.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'special_price.numeric' => 'Giá khuyến mãi phải là số.',
            'special_price_type.in' => 'Loại giá khuyến mãi không hợp lệ.',
            'special_price_start.before_or_equal' => 'Ngày bắt đầu khuyến mãi phải trước hoặc bằng ngày kết thúc.',
            'special_price_end.after_or_equal' => 'Ngày kết thúc khuyến mãi phải sau hoặc bằng ngày bắt đầu.',
            'sku.max' => 'Mã SKU không được quá 50 ký tự.',
            'qty.integer' => 'Số lượng phải là số nguyên.',
            'qty.min' => 'Số lượng không được nhỏ hơn 0.',
            'new_to.after_or_equal' => 'Ngày kết thúc mới phải sau hoặc bằng ngày bắt đầu mới.',

            // Thông báo lỗi cho biến thể
            'variants.*.name.required_with' => 'Tên biến thể là bắt buộc khi có biến thể.',
            'variants.*.price.required_with' => 'Giá biến thể là bắt buộc khi có biến thể.',
            'variants.*.price.numeric' => 'Giá biến thể phải là số.',
            'variants.*.special_price.numeric' => 'Giá khuyến mãi của biến thể phải là số.',
        ];
    }
}
