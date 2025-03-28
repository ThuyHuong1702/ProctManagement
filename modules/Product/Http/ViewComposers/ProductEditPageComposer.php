<?php

namespace Modules\Product\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Variation;

class ProductEditPageComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $brands = Brand::all(); // Lấy danh sách thương hiệu từ DB
        $categories = Category::all();
        $variations = Variation::with('values')->get();

        $view->with(compact('brands', 'categories', 'variations'));
    }
}
