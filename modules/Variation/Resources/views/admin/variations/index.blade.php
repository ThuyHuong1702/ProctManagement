@extends('admin::layout')
 
@component('admin::components.page.header')
    @slot('title', trans('variation::variations.variations'))
 
    <li class="active">{{ trans('variation::variations.variations') }}</li>
@endcomponent
 
@component('admin::components.page.index_table')
    @slot('buttons', ['create'])
    @slot('resource', 'variations')
    @slot('name', trans('variation::variations.variation'))
 
    @slot('thead')
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>{{ trans('admin::admin.table.id') }}</th>
            <th>{{ trans('variation::variations.table.name') }}</th>
            <th>{{ trans('variation::variations.table.type') }}</th>
            <th>{{ trans('admin::admin.table.updated') }}</th>
        </tr>
    @endslot
 
    @slot('tbody')
    @foreach ($variations as $variation)
        <tr class="variation-row" data-id="{{ $variation->id }}">
            <td><input type="checkbox" name="ids[]" value="{{ $variation->id }}"></td>
            <td>{{ $variation->id }}</td>
            <td>{{ $variation->name }}</td>
            <td>{{ $variation->type }}</td>
            <td>{{ $variation->updated_at }}</td>
        </tr>
    @endforeach
@endslot
 
    @slot('ttotal')
        <div>
            <label class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">
                {{ "Show $perPage of $totalProducts variations" }}
            </label>
        </div>
    @endslot
 
    @slot('tchange')
        <div class="row dt-layout-row">
            <div class="dt-paging">
                <nav aria-label="pagination">
                    <ul class="pagination">
                        <li class="dt-paging-button page-item">
                            {{ $variations->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endslot
@endcomponent
 
@push('scripts')
    <script type="module">
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.querySelectorAll('input[name="ids[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }

        $(document).ready(function() {
            // Sự kiện nhấp đúp chuột vào mỗi dòng trong bảng
            $(".variation-row").dblclick(function() {
                // Lấy ID của variation từ thuộc tính data-id của dòng
                const variationId = $(this).data("id");
                // Chuyển hướng đến trang chỉnh sửa với ID của variation
                window.location.href = "{{ url('admin/variations') }}/" + variationId + "/edit";
            });

            $(".btn-delete").click(function() {
                $("#confirmation-modal").modal('show');
            });

            $(".delete").click(function(e) {
                e.preventDefault();
                const selectedIds = [];
                document.querySelectorAll("input[name='ids[]']:checked").forEach((checkbox) => {
                    selectedIds.push(checkbox.value);
                });

                if (selectedIds.length === 0) {
                    toastr.warning("Vui lòng chọn ít nhất một biến thể để xóa!");
                    return;
                }

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.variations.bulkDelete') }}",
                    data: { ids: selectedIds },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: (response) => {
                        location.reload();
                        toaster(response.message, { type: "success" });
                    },
                })
                .catch((error) => {
                    toaster(error.responseJSON.message, { type: "default" });
                });
            });
        });
    </script>
@endpush
