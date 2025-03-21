<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
        $brands = Brand::where('is_active', 1)->get(); // Lấy tất cả thương hiệu đang hoạt động
        $categories = Category::where('is_active', 1)->get(); // Lấy tất cả danh mục đang hoạt động
        $globalVariations = Variation::all(); // Lấy tất cả globalVariations từ database

        return view("{$this->viewPath}.create", compact('brands', 'categories', 'globalVariations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|JsonResponse
     */
    public function store()
    {

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
