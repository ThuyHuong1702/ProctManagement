<?php
namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ProductVariant;

class ProductVariantController
{
    public function store(Request $request)
    {
        $variants = $request->input('variants');

        // Nếu không có biến thể, chèn dữ liệu vào bảng products
        if (empty($variants)) {
            $productController = new ProductController();
            return $productController->store($request); // Gọi phương thức store() của ProductController
        }

        try {
            foreach ($variants as $variant) {
                ProductVariant::create([
                    'product_id' => $variant['product_id'],
                    'uid' => $variant['uid'],
                    'name' => $variant['name'],
                    'is_active' => $variant['is_active'],
                    'is_default' => $variant['is_default'],
                    'media' => $variant['media'],
                    'in_stock' => $variant['in_stock'],
                    'manage_stock' => $variant['manage_stock'],
                    'position' => $variant['position'],
                    'price' => $variant['price'],
                    'special_price' => $variant['special_price'],
                    'special_price_type' => $variant['special_price_type'],
                    'cost_price' => $variant['cost_price'],
                    'compare_at_price' => $variant['compare_at_price'],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Variants đã được lưu thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lưu variants!', 'error' => $e->getMessage()], 500);
        }
    }
}
