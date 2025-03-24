<th style="max-width: 20px;">
    <div class="checkbox">
        <input type="checkbox" class="select-all" id="{{ $name ?? '' }}-select-all">
        <label for="{{ $name ?? '' }}-select-all"></label>
    </div>
</th>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Chọn tất cả checkbox
        document.querySelector(".select-all").addEventListener("change", function () {
            let checkboxes = document.querySelectorAll("input[name='product_ids[]']:not(.select-all)");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Xử lý xóa sản phẩm khi checkbox được chọn
        document.querySelector(".btn-delete").addEventListener("click", function () {
            let selectedIds = [];
            document.querySelectorAll("input[name='product_ids[]']:checked").forEach((checkbox) => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                alert("Vui lòng chọn ít nhất một sản phẩm để xóa!");
                return;
            }

            // Xác nhận xóa
            if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} sản phẩm đã chọn không?`)) {
                fetch("{{ route('admin.products.delete') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ product_ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Xóa sản phẩm thành công!");
                        selectedIds.forEach(id => {
                            let row = document.querySelector(`input[value='${id}']`).closest("tr");
                            if (row) row.remove(); // Xóa hàng khỏi bảng ngay lập tức
                        });
                    } else {
                        alert("Đã xảy ra lỗi khi xóa sản phẩm!");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
            }
        });
    });
</script>
