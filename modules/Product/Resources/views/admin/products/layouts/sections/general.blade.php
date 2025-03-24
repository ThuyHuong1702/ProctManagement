<div class="box">
    <div class="box-header">
        <h5>{{ trans('product::products.group.general') }}</h5>
    </div>

    <div class="box-body">
        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label text-left">
                {{ trans('product::attributes.name') }}
                <span class="text-red">*</span>
            </label>

            <div class="col-sm-12">
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    required
                >
                @error('name')
                    <span class="help-block text-red">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-sm-12 control-label text-left">
                {{ trans('product::attributes.description') }}
                <span class="text-red">*</span>
            </label>

            <div class="col-sm-12">
                <textarea
                    name="description"
                    id="description"
                    class="form-control wysiwyg"
                    required
                >
                </textarea>
                @error('description')
                    <span class="help-block text-red">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="brand-id" class="col-sm-12 control-label text-left">
                {{ trans('product::attributes.brand_id') }}
                <span class="text-red">*</span>
            </label>
            <div class="col-sm-6">
                <select name="brand_id" id="brand-id" class="form-control custom-select-black" required>
                    <option value="">Please Select</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->slug }}</option>
                        @endforeach
                </select>
                @error('brand_id')
                    <span class="help-block text-red">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="categories" class="col-sm-12 control-label text-left">
                {{ trans('product::attributes.categories') }}
                <span class="text-red">*</span>
            </label>

            <div class="col-sm-6">
                <select name="categories" id="categories-id" class="form-control custom-select-black" required>
                    <option value="">Please Select</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->slug }}</option>
                        @endforeach
                </select>
                @error('categories')
                    <span class="help-block text-red">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="is-active" class="col-sm-12 control-label text-left">
                {{ trans('product::attributes.is_active') }}
                <span class="text-red">*</span>
            </label>

            <div class="col-sm-9">
                <div class="switch">
                    <input type="checkbox" name="is_active" id="is-active">

                    <label for="is-active">
                        {{ trans('product::products.form.enable_the_product') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
