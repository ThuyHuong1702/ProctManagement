<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Traits\HasCrudActions;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariant;

use Modules\Product\Services\ProductService;

class ProductController
{
    /**
     * Model for the resource.
     *
     * @var string
     */
//    protected string $model = Product::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected string $label = 'product::products.product';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected string $viewPath = 'product::admin.products';

    /**
     * Lấy giá trị Global Variation ID từ request hoặc mặc định.
     *
     * @param Request $request
     * @return int|null
     */
    protected function getGlobalVariationId(Request $request)
    {
        return $request->query('globalVariationId') ?? null;
    }

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Danh sách cột có thể sắp xếp
        $sortableColumns = ['id', 'name', 'price', 'in_stock', 'updated_at'];

        // Lấy giá trị cột cần sắp xếp từ request, mặc định là 'id'
        $sortBy = $request->get('sort_by', 'id');

        // Kiểm tra nếu cột không hợp lệ, đặt lại thành 'id'
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'id';
        }

        // Lấy thứ tự sắp xếp, mặc định là 'asc'
        $sortOrder = $request->get('sort', 'asc');
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $perPage = $request->input('per_page', 2);
        $totalProducts = Product::count(); // Tổng số sản phẩm


        // Truy vấn sản phẩm với điều kiện sắp xếp động
        $products = Product::orderBy($sortBy, $sortOrder)->paginate($perPage);

        // Định dạng dữ liệu trước khi gửi đến view
        $products->getCollection()->transform(function ($product) {
            $now = Carbon::now();

            // Kiểm tra nếu ngày hiện tại nằm trong khoảng giảm giá
            $isDiscountActive = $product->special_price_start && $product->special_price_end &&
                                $now->between($product->special_price_start, $product->special_price_end);

            // Định dạng giá
            if ($isDiscountActive && $product->selling_price && $product->selling_price != $product->price) {
                $product->formatted_price = "<span class='text-danger fw-bold'>" . number_format($product->selling_price, 2) . " VNĐ</span> " .
                                            "<del class='text-muted ms-2'>" . number_format($product->price, 2) . " VNĐ</del>";
            } else {
                $product->formatted_price = "<span class='fw-bold'>" . number_format($product->price, 2) . " VNĐ</span>";
            }

            // Tính thời gian cập nhật
            $days_diff = $now->diffInDays($product->updated_at);
            $product->formatted_updated_at = ($days_diff < 30) ?
                "<span class='text-success'>{$days_diff} ngày trước</span>" :
                "<span class='text-primary'>" . floor($days_diff / 30) . " tháng trước</span>";

            return $product;
        });

        // Trả dữ liệu về view
        return view("{$this->viewPath}.index", compact('products', 'sortBy', 'sortOrder', 'perPage', 'totalProducts'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view("{$this->viewPath}.create");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response|JsonResponse
     */
    public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $validated = $request->validate([
        'name' => 'required|string|max:191',
        'brand_id' => 'required|exists:brands,id',
        'variants' => 'nullable|array', // Danh sách biến thể (nếu có)
    ]);

    // Gọi ProductService để format dữ liệu
    $structuredData = ProductService::formatProductVariants($request->all());

    // Nếu có biến thể, lưu vào bảng `product_variants`
    if (!empty($structuredData['variants'])) {
        // Tìm giá của biến thể mặc định (is_default = 1)
        $defaultVariant = collect($structuredData['variants'])->firstWhere('is_default', 1);
        $parentPrice = $defaultVariant['price'] ?? 0;

        // Lưu sản phẩm cha (parent product)
        $product = Product::create([
            'name' => $validated['name'] ?? $request->name,
            'brand_id' => $validated['brand_id'],
            'sku' => null, // Sản phẩm cha không có SKU riêng
            'price' => $parentPrice, // Lấy giá của biến thể mặc định nếu có
            'is_active' => 1,
        ]);

        // Lưu từng biến thể vào `product_variants`
        foreach ($structuredData['variants'] as $variant) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variant['name'],
                'sku' => $variant['sku'],
                'price' => $variant['price'] ?? 0,
                'special_price' => $variant['special_price'],
                'special_price_type' => $variant['special_price_type'],
                'special_price_start' => $variant['special_price_start'],
                'special_price_end' => $variant['special_price_end'],
                'manage_stock' => $variant['manage_stock'],
                'in_stock' => $variant['in_stock'],
                'is_active' => $variant['is_active'] ?? 1,
                'is_default' => $variant['is_default'] ?? 0,
            ]);
        }
    } else {
        // Nếu không có biến thể, lưu vào `products`
        Product::create([
            'name' => $structuredData['name'] ?? $request->name,
            'brand_id' => $structuredData['brand_id'],
            'sku' => $request->sku,
            'price' => $request->price,
            'is_active' => 1,
        ]);
    }

    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được lưu!');
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Factory|View|Application
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view("{$this->viewPath}.edit", compact('product'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'nullable|array',
        ]);

        // Gọi service để xử lý dữ liệu
        $structuredData = ProductService::formatProductVariantsForUpdate($request->all());
        //dd($structuredData);

        // Tìm sản phẩm cha
        $product = Product::findOrFail($id);

        if (!empty($structuredData['variants'])) {
            // Nếu có biến thể, cập nhật biến thể thay vì sản phẩm chính
            foreach ($structuredData['variants'] as $variant) {
                $variantModel = ProductVariant::find($variant['id']);
                if ($variantModel) {
                    $variantModel->update($variant);
                } else {
                    ProductVariant::create(array_merge($variant, ['product_id' => $product->id]));
                }
            }

            // Cập nhật giá sản phẩm cha theo biến thể mặc định
            $defaultVariant = collect($structuredData['variants'])->firstWhere('is_default', 1);
            $product->update(['price' => $defaultVariant['price'] ?? $product->price]);
        } else {
            // Nếu không có biến thể, cập nhật sản phẩm chính
            $product->update([
                'name' => $structuredData['name'],
                'brand_id' => $structuredData['brand_id'],
                'sku' => $structuredData['sku'],
                'price' => $structuredData['price'],
                'is_active' => $structuredData['is_active'] ?? $product->is_active,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }



    public function delete(Request $request)
    {
        try {
            $productIds = json_decode($request->input('ids'), true);

            if (empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có sản phẩm nào được chọn!'
                ]);
            }

            Product::whereIn('id', $productIds)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã bị xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xóa sản phẩm!',
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * Get request object
     *
     * @param string $action
     *
     * @return Request
     */
    protected function getRequest(string $action): Request
    {
        return match (true) {
            !isset($this->validation) => request(),
            isset($this->validation[$action]) => resolve($this->validation[$action]),
            default => resolve($this->validation),
        };
    }
}
