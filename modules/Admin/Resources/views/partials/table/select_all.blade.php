<th style="max-width: 20px;">
    <div class="checkbox">
        <input type="checkbox" class="select-all" id="{{ $name ?? '' }}-select-all">
        <label for="{{ $name ?? '' }}-select-all"></label>
    </div>
</th>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".select-all").addEventListener("change", function () {
        let checkboxes = document.querySelectorAll("input[type='checkbox']:not(.select-all)");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
});
</script>
