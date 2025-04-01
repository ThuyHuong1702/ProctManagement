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

use Illuminate\Support\Facades\DB;

use Modules\Product\Services\ProductService;

use App\Http\Requests\ProductRequest;

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
        $product = new Product(); // Tạo một đối tượng rỗng
        return view("{$this->viewPath}.create", compact('product'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response|JsonResponse
     */
    public function store(ProductRequest $request)
{
    $request->validated();
    //dd($validatedData);
    // Gọi ProductService để format dữ liệu
    $structuredData = ProductService::formatProductVariantsForUpdate($request->all());

    //dd($structuredData);
    // Nếu có biến thể, lưu vào bảng `product_variants`
    if (!empty($structuredData['variants'])) {
        // Tìm giá của biến thể mặc định (is_default = 1)
        $defaultVariant = collect($structuredData['variants'])->firstWhere('is_default', 1);
        $parentPrice = $defaultVariant['price'] ?? 0;
        $parentSpecialPrice = $defaultVariant['special_price'] ?? 0;
        $parentPriceType = isset($defaultVariant['special_price_type']) ? ($defaultVariant['special_price_type'] == 1 ? 1 : 2) : 1;
        $parentSpecialPriceStart = $defaultVariant['special_price_start'] ?? null;
        $parentSpecialPriceEnd = $defaultVariant['special_price_end'] ?? null;
        $parentSku = $defaultVariant['sku'] ?? null;

        // Lưu sản phẩm cha (parent product)
        $product = Product::create([
            'name' => $structuredData['name'] ?? $request->name,
            'brand_id' => $structuredData['brand_id'],
            'sku' => $parentSku, // Lấy sku của biến thể mặc định nếu có
            'price' => $parentPrice, // Lấy giá của biến thể mặc định nếu có
            'special_price' => $parentSpecialPrice,
            'special_price_type' => $parentPriceType,
            'special_price_start' => $parentSpecialPriceStart,
            'special_price_end' => $parentSpecialPriceEnd,
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
                'qty' => $variant['qty'],
                'in_stock' => $variant['in_stock'],
                'is_active' => $variant['is_active'] ?? 1,
                'is_default' => $variant['is_default'] ?? 0,
            ]);
        }
    } else {
        // Nếu không có biến thể, lưu vào `products`
        $product = Product::create([
            'name' => $structuredData['name'] ?? $request->name,
            'description' => $structuredData['description'] ?? null,
            'brand_id' => $structuredData['brand_id'],
            'sku' => $structuredData['sku'],
            'price' => $structuredData['price'],
            'special_price' =>$structuredData['special_price'],
            'special_price_type' => $structuredData['special_price_type'],
            'special_price_start' => $structuredData['special_price_start'],
            'special_price_end' => $structuredData['special_price_end'],
            'is_active' => $structuredData['is_active'] ?? 1,
            'short_description' => $structuredData['short_description'] ?? null,
            'new_from' => $structuredData['new_from'] ?? null,
            'new_to' => $structuredData['new_to'] ?? null,
            'manage_stock' => $structuredData['manage_stock'] ?? 0,
            'qty' => $structuredData['qty'] ?? null,
            'in_stock' => $data['in_stock'] ?? 1,
        ]);
    }
     // Lưu dữ liệu vào bảng `product_categories`
     if (!empty($structuredData['category_id'])) {
        $product->categories()->sync($structuredData['category_id']);
    }

    if ($request->input('redirect_after_save') == "1") {
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được lưu!');
    } else {
        return redirect()->back()->with('success', 'Sản phẩm đã được lưu!');
    }
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
    //$validatedData = $request->validated();
    $structuredData = ProductService::formatProductVariantsForUpdate($request->all());

    $product = Product::findOrFail($id);

    if (!empty($structuredData['variants'])) {
        // Nếu có biến thể, cập nhật sản phẩm chính với giá của biến thể mặc định
        $defaultVariant = collect($structuredData['variants'])->firstWhere('is_default', 1);
        $parentPrice = $defaultVariant['price'] ?? 0;
        $parentSpecialPrice = $defaultVariant['special_price'] ?? 0;
        $parentPriceType = isset($defaultVariant['special_price_type']) ? ($defaultVariant['special_price_type'] == 1 ? 1 : 2) : 1;
        $parentSpecialPriceStart = $defaultVariant['special_price_start'] ?? null;
        $parentSpecialPriceEnd = $defaultVariant['special_price_end'] ?? null;
        $product->update([
            'name' => $structuredData['name'] ?? $request->name,
            'brand_id' => $structuredData['brand_id'],
            'sku' => null,
            'price' => $parentPrice,
            'special_price' => $parentSpecialPrice,
            'special_price_type' => $parentPriceType,
            'special_price_start' => $parentSpecialPriceStart,
            'special_price_end' => $parentSpecialPriceEnd,
            'is_active' => 1,
        ]);

        // Xóa tất cả biến thể cũ trước khi thêm mới
        ProductVariant::where('product_id', $product->id)->delete();

        // Lưu từng biến thể mới vào `product_variants`
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
                'qty' => $variant['qty'],
                'in_stock' => $variant['in_stock'],
                'is_active' => $variant['is_active'] ?? 1,
                'is_default' => $variant['is_default'] ?? 0,
            ]);
        }
    } else {
        // Nếu không có biến thể, xóa toàn bộ biến thể cũ
        ProductVariant::where('product_id', $product->id)->delete();

        // Cập nhật sản phẩm chính
        $product->update([
            'name' => $structuredData['name'],
            'description' => $structuredData['description'] ?? null,
            'brand_id' => $structuredData['brand_id'],
            'sku' => $structuredData['sku'],
            'price' => $structuredData['price'],
            'special_price' => $structuredData['special_price'],
            'special_price_type' => $structuredData['special_price_type'],
            'special_price_start' => $structuredData['special_price_start'],
            'special_price_end' => $structuredData['special_price_end'],
            'is_active' => $structuredData['is_active'] ?? $product->is_active,
            'short_description' => $structuredData['short_description'] ?? null,
            'new_from' => $structuredData['new_from'] ?? null,
            'new_to' => $structuredData['new_to'] ?? null,
            'manage_stock' => $structuredData['manage_stock'] ?? 0,
            'qty' => $structuredData['qty'] ?? null,
            'in_stock' => $data['in_stock'] ?? 1,
        ]);

    }
    // Cập nhật danh mục của sản phẩm
    if (!empty($structuredData['category_id'])) {
        $product->categories()->sync($structuredData['category_id']);
    }

    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
}
public function delete(Request $request)
    {
        $ids = explode(',', $request->ids);

        try {
            DB::beginTransaction();

            Product::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sản phẩm đã được xóa thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = Product::where('name', 'like', "%$keyword%")->get();

        return view('admin.products.index', compact('products'));

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
