<th style="max-width: 20px;">
    <div class="checkbox">
        <input type="checkbox" class="select-all" id="{{ $name ?? '' }}-select-all">
        <label for="{{ $name ?? '' }}-select-all"></label>
    </div>
</th>

<script type="module">
    $(document).ready(function() {
        $(document).on('click', '#delete-records', function(event) {
            // Lấy tất cả checkbox đã chọn (trừ checkbox "Chọn tất cả")
            const recordsChecked = $('.index-table').find("input[type='checkbox']:checked").not('.select-all');

            if (recordsChecked.length === 0) {
                return;
            }

            // Lấy danh sách ID từ các checkbox đã chọn
            const ids = recordsChecked.toArray()
                .map(row => parseInt(row.value))
                .filter(id => !isNaN(id)); // Lọc bỏ giá trị không hợp lệ

            const confirmationModal = $("#confirmation-modal");

            if (confirmationModal.length) {
                confirmationModal.modal('show');
                confirmationModal.find("form").find('input[name="ids"][type="hidden']").val(JSON.stringify(ids));
                confirmationModal.find("form").attr('action', "{{ route('admin.products.delete') }}");
            }
        });

        @if (session()->has('message'))
            @if (session('status') === \Modules\Admin\Enums\StatusResponse::SUCCESS)
                success("{{ session('message') }}");
            @elseif (session('status') === \Modules\Admin\Enums\StatusResponse::FAILURE)
                error("{{ session('message') }}");
            @endif
        @endif
    });
</script>
