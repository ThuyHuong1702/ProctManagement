<?php

namespace Modules\Product\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Variation;
use Illuminate\Http\Request;

class ProductCreatePageComposer
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $variations = Variation::with('values')->get(); // Lấy danh sách biến thể cùng giá trị

        // Lấy Global Variation ID từ request
        $globalVariationId = $this->getGlobalVariationId();

        $view->with(compact('brands', 'categories', 'variations', 'globalVariationId'));
    }


    private function getGlobalVariationId()
    {
        return $this->request->input('globalVariationId', null);
    }
}
