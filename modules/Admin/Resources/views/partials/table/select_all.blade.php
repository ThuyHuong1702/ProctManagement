<th style="max-width: 20px;">
    <div class="checkbox">
        <input type="checkbox" class="select-all" id="{{ $name ?? '' }}-select-all">
        <label for="{{ $name ?? '' }}-select-all"></label>
    </div>
</th>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Chọn tất cả checkbox khi click vào "select-all"
        document.querySelector(".select-all").addEventListener("change", function () {
            let checkboxes = document.querySelectorAll("input[type='checkbox']:not(.select-all)");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Xóa tất cả bản ghi khi nhấn nút "Xóa tất cả"
        document.querySelector("#delete-all-records").addEventListener("click", function () {
            let checkboxes = document.querySelectorAll("input[type='checkbox']:not(.select-all):checked");

            if (checkboxes.length === 0) {
                alert("Không có bản ghi nào được chọn!");
                return;
            }

            let ids = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (confirm("Bạn có chắc chắn muốn xóa tất cả các bản ghi đã chọn không?")) {
                fetch("{{ route('admin.products.delete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Đã xóa thành công!");
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra khi xóa!");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
            }
        });
    });
</script>
