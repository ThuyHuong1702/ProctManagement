<?php
namespace Modules\Variation\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Variation\Entities\Variation;
use Modules\Variation\Entities\VariationValue;
use Illuminate\Routing\Controller;

class VariationController extends Controller
{
    /**
     * Hiển thị danh sách dữ liệu.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $variations = Variation::paginate(10);
        $sortableColumns = ['id', 'name', 'type', 'is_global', 'position'];
        $sortBy = $request->get('sort_by', 'id');
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'id';
        }
        $sortOrder = $request->get('sort', 'desc');
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $perPage = $request->input('per_page', 5);
        $totalProducts = Variation::count();
        $variations = Variation::orderBy($sortBy, $sortOrder)->paginate($perPage);

        return view('variation::admin.variations.index', compact('variations', 'perPage', 'totalProducts'));
    }

    /**
     * Hiển thị form thêm dữ liệu mới.
     *
     * @return View
     */
    public function create()
    {
        $varition = new Variation(); // Tạo một đối tượng rỗng

        return view('variation::admin.variations.create', compact('varition'));
    }

    /**
     * Lưu dữ liệu mới vào cơ sở dữ liệu.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*.uid' => 'required|string',
        ];

        // Điều kiện xác thực cho label, color, image
        if ($request->type === 'color') {
            $rules['values.*.color'] = 'required|string|max:255';
        } elseif ($request->type === 'image') {
            $rules['values.*.image'] = 'required|string|max:255';
        } else {
            $rules['values.*.label'] = 'required|string|max:255';
        }

        // Thực hiện xác thực
        $request->validate($rules);

        try {
            // Tạo variation mới
            $variation = Variation::create([
                'name' => $request->name,
                'type' => $request->type,
            ]);

            // Lưu các variation values
            foreach ($request->values as $value) {
                // Xử lý theo kiểu type
                if ($request->type === 'color') {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'uid' => $value['uid'],
                        'color' => $value['color'],
                    ]);
                } elseif ($request->type === 'image') {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'uid' => $value['uid'],
                        'image' => $value['image'],
                    ]);
                } else {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'uid' => $value['uid'],
                        'label' => $value['label'],
                    ]);
                }
            }

            return redirect()->route('admin.variations.index')->with('success', 'Dữ liệu đã được thêm thành công!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm dữ liệu.');
        }
    }
    /**
     * Hiển thị chi tiết dữ liệu (nếu cần).
     *
     * @param int $id
     * @return View
     */
    /**
 * Hiển thị chi tiết dữ liệu (nếu cần).
 *
 * @param int $id
 * @return View
 */
public function show($id)
{
    $variation = Variation::query()
        ->select('id', 'name', 'type')
        ->where('id', $id)
        ->with([
            'values' => function ($query) {
                $query->select('id', 'label', 'value', 'variation_id')->orderBy('position', 'asc');
            },
        ])->first();

    $values = $variation->values->map(function ($value) use ($variation) {
        return [
            'uid' => $value->id,
            'value' => $variation->type === 'color' ? $value->color : $value->label,
        ];
    });

    return view('variation::admin.variations.show', compact('variation', 'values'));
}


    /**
     * Hiển thị form sửa dữ liệu.
     *
     * @param int $id
     * @return View
     */
/**
 * Hiển thị form sửa dữ liệu.
 *
 * @param int $id
 * @return View
 */
public function edit($id)
{
    $variation = Variation::query()
        ->select('id', 'name', 'type')
        ->where('id', $id)
        ->with([
            'values' => function ($query) {
                $query->select('id', 'label', 'value', 'variation_id')->orderBy('position', 'asc');
            },
        ])->first();

    $values = $variation->values->map(function ($value) use ($variation) {
        return [
            'uid' => $value->id,
            'label' => $value->label,
            'color' => $variation->type === 'color' ? $value->value : null,
            'image' => $variation->type === 'image' ? $value->value : null,
        ];
    });

    return view('variation::admin.variations.edit', compact('variation', 'values'));
}




    /**
     * Cập nhật dữ liệu vào cơ sở dữ liệu.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*.uid' => 'required|string',
        ];

        // Điều kiện xác thực cho label, color, image
        if ($request->type === 'color') {
            $rules['values.*.color'] = 'required|string|max:255';
        } elseif ($request->type === 'image') {
            $rules['values.*.image'] = 'required|string|max:255';
        } else {
            $rules['values.*.label'] = 'required|string|max:255';
        }

        // Thực hiện xác thực
        $request->validate($rules);

        try {
            // Cập nhật Variation
            $variation = Variation::findOrFail($id);
            $variation->update([
                'name' => $request->name,
                'type' => $request->type,
            ]);

            // Xóa các giá trị cũ
            VariationValue::where('variation_id', $variation->id)->delete();

            // Thêm các giá trị mới
            foreach ($request->values as $value) {
                if ($request->type === 'color') {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'label' => $value['label'],
                        'value' => $value['color'],
                    ]);
                } elseif ($request->type === 'image') {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'label' => $value['label'],
                        'value' => $value['image'],
                    ]);
                } else {
                    VariationValue::create([
                        'variation_id' => $variation->id,
                        'label' => $value['label'],
                        'value' => '',
                    ]);
                }
            }

            return redirect()->route('admin.variations.index')->with('success', 'Dữ liệu đã được cập nhật thành công!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu.');
        }
    }

    /**
     * Xóa bản ghi khỏi cơ sở dữ liệu.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $variation = Variation::findOrFail($id);
        $variation->delete();

        return redirect()->route('admin.variations.index')->with('success', 'Dữ liệu đã được xóa thành công!');
    }

    /**
     * Xóa nhiều bản ghi cùng lúc.
     *
     * @param Request $request
     * @return Response
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if (empty($ids)) {
                return response()->json(['error' => true, 'message' => 'No IDs provided'], 400);
            }

            // Kiểm tra nếu tất cả các ID có tồn tại trong cơ sở dữ liệu
            $validIds = Variation::whereIn('id', $ids)->pluck('id');
            if (count($validIds) !== count($ids)) {
                return response()->json(['error' => true, 'message' => 'Some IDs are invalid'], 400);
            }

            // Tiến hành xóa
            Variation::destroy($ids);
            return response()->json(['message' => __('Thành công')]);
        } catch (\Exception $ex) {
            report($ex);
            return response()->json([
                'error' => true,
                'message' => __('Admin::business-office.delete.failure')
            ]);
        }
    }
}
