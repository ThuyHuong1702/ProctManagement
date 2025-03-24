<?php

namespace Modules\Variation\Http\Controllers\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Traits\HasCrudActions;
use App\Models\Variation;


class VariationController
{

    /**
     * Model for the resource.
     *
     * @var string
     */
//    protected string $model = Variation::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected string $label = 'variation::variations.variation';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected string $viewPath = 'variation::admin.variations';

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $globalVariations = Variation::all();
        //return view('admin.variations.index', compact('globalVariations'));
        return view("{$this->viewPath}.index", compact('globalVariations'));
    }


    public function show($id)
    {
        // Truy vấn biến thể theo ID, lấy kèm danh sách các giá trị từ bảng variation_values
        $variation = Variation::with('values')->find($id);

        // Nếu không tìm thấy, trả về lỗi 404
        if (!$variation) {
            return response()->json(['message' => 'Không tìm thấy biến thể'], 404);
        }

        // Trả về dữ liệu biến thể dưới dạng JSON
        return response()->json($variation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {

    }
}
