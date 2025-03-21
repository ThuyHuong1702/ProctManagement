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
    use HasCrudActions;

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
        return view("{$this->viewPath}.index");
    }


    public function show($id)
    {
        $variation = Variation::with('values')->find($id);

        if (!$variation) {
            return response()->json(['message' => 'Không tìm thấy biến thể'], 404);
        }

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
