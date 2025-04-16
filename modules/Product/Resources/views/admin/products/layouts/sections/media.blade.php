<template v-if="section === 'media'">
    <div class="box-header">
        <h5>{{ trans('product::products.group.media') }}</h5>

        <div class="drag-handle">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <draggable
                    animation="200"
                    class="product-media-grid"
                    force-fallback="true"
                    handle=".handle"
                    :move="preventLastSlideDrag"
                    :list="form.media"
                >
                    <div class="media-grid-item handle" v-for="(media, index) in form.media" :key="index">
                        <div class="image-holder">
                            <img :src="media.path" alt="product media">

                            <button type="button" class="btn remove-image" @click="removeMedia(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="media-grid-item media-picker" id="media-picker">
                        <div class="image-holder">
                            @if (!empty($product->thumbnail))
                                <input type="hidden" name="thumbnail" value="{{ $product->thumbnail }}">
                            @endif

                            <img
                                src="{{ asset('build/assets/placeholder_image.png') }}"
                                class="placeholder-image"
                                alt="Placeholder"
                                id="placeholder-image"
                                style="cursor: pointer;"
                            >
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control" style="display: none;">
                        </div>
                    </div>


                </draggable>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    $(function () {
        @if (!empty($product->thumbnail))
            const oldThumbnailItem = `
                <div class="media-grid-item handle">
                    <div class="image-holder">
                        <img src="{{ $product->thumbnail }}" alt="product media">
                        <button type="button" class="btn remove-image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            $('.product-media-grid').prepend(oldThumbnailItem);
        @endif

        // Khi click ảnh placeholder → mở hộp thoại chọn file
        $('#placeholder-image').on('click', function () {
            $('#thumbnail').trigger('click');
        });

        // Khi chọn file
        $('#thumbnail').on('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                const imagePath = e.target.result;

                // Thêm ảnh mới vào danh sách media (dùng prepend để đưa lên đầu)
                const mediaItem = `
                    <div class="media-grid-item handle">
                        <div class="image-holder">
                            <img src="${imagePath}" alt="product media">
                            <button type="button" class="btn remove-image">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;

                // Thêm ảnh và ẩn placeholder
                $('.product-media-grid').prepend(mediaItem);
                $('#media-picker').hide(); // Ẩn placeholder
            };

            reader.readAsDataURL(file);
        });

        // Khi xóa ảnh
        $(document).on('click', '.remove-image', function () {
            $(this).closest('.media-grid-item').remove();
            $('#media-picker').show(); // Hiện lại placeholder
            $('#thumbnail').val(""); // Reset input file
        });


        // Nếu đã có ảnh thumbnail từ server, ẩn luôn placeholder
        @if (!empty($product->thumbnail))
            $('#media-picker').hide();
        @endif
    });
</script>
@endpush
