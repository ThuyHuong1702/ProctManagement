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
use App\Models\Variation;

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
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:191',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'sort_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'special_price_type' => 'nullable|string|in:fixed,percent',
            'special_price_start' => 'nullable|date',
            'special_price_end' => 'nullable|date',
            'selling_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku|max:191',
            'manage_stock' => 'boolean',
            'qty' => 'integer|min:0',
            'in_stock' => 'boolean',
            'viewed' => 'integer|min:0',
            'is_active' => 'boolean',
            'new_from' => 'nullable|date',
            'new_to' => 'nullable|date',
        ]);

        // Tạo sản phẩm mới
        $product = Product::create($request->all());

        $product->categories()->attach($request->category_id);

        // Kiểm tra xem người dùng có muốn quay lại trang danh sách không
        if ($request->input('redirect_after_save') == "1") {
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
        }

        // Nếu không, quay lại trang tạo sản phẩm với thông báo thành công
        return redirect()->back()->with('success', 'Sản phẩm đã được lưu!');
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
        return view("{$this->viewPath}.edit", []);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     */
    public function update($id)
    {

    }

    public function delete(Request $request)
    {
        $productIds = $request->input('product_ids');

        if (empty($productIds)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm nào được chọn!']);
        }

        Product::whereIn('id', $productIds)->delete();

        return response()->json(['success' => true, 'message' => 'Sản phẩm đã bị xóa thành công!']);
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
