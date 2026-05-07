<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<body>

    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        @include('admin.common.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            @include('admin.common.sidebar')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item"><a href="{{ route('cms.home-page.slider.index') }}">Sliders List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Slide Create</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form enctype="multipart/form-data" method="POST" action="{{ route('cms.home-page.slider.update', $slider->id) }}" class="forms-sample form-confirm-submit" id="myForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $slider->title) }}">
                                            <small class="text-danger error-message mb-3" id="error-title"></small>
                                            @error('title')
                                            <small class="text-danger" id="hidden-msg-title">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="subtitle">Subtitle<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}">
                                            <small class="text-danger error-message mb-3" id="error-subtitle"></small>
                                            @error('subtitle')
                                            <small class="text-danger" id="hidden-msg-subtitle">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Slider Image<span class="text-danger">*</span></label>
                                            {{-- Old image --}}
                                            @if($slider->image && !old('image_path'))
                                            <div class="mb-2" id="old-image-wrapper">
                                                <img src="{{ asset('storage/' . $slider->image) }}" alt="Current Slider Image" id="old-image">
                                            </div>
                                            @endif

                                            {{-- Preview image --}}
                                            <div class="mb-2" id="new-image-preview" style="{{ old('image_path') ? 'display:block;' : 'display:none;' }}">
                                                <img src="{{ old('image_path') ? asset(old('image_path')) : '' }}" alt="New Image Preview" id="preview-image">
                                            </div>

                                            <div class="custom-file-container">
                                                <button type="button" id="chooseFileBtn" class="btn btn-gradient-primary">Choose File</button>
                                                <span id="fileNameDisplay" class="file-name">{{ old('image_path') ? basename(old('image_path')) : 'No file chosen' }}</span>
                                                <input type="hidden" name="image_path" id="imagePath" value="{{ old('image_path') }}">
                                                <input type="file" id="image" style="display:none;" accept=".jpeg,.jpg,.png,.gif,.webp" data-type="sliders" data-preview="#preview-image">
                                            </div>
                                            <small class=" text-danger error-message mb-3" id="error-image"></small>
                                            @error('image')
                                            <small class="text-danger" id="hidden-msg-image">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="button_text">Button text<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}">
                                                    <small class="text-danger error-message mb-3" id="error-button_text"></small>
                                                    @error('button_text')
                                                    <small class="text-danger" id="hidden-msg-button_text">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label for="button_link">Button link<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="button_link" name="button_link" value="{{ old('button_link', $slider->button_link) }}">
                                                    <small class="text-danger error-message mb-3" id="error-button_link"></small>
                                                    @error('button_link')
                                                    <small class="text-danger" id="hidden-msg-button_link">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="regular_price">Regular Price</label>
                                                    <input type="text" class="form-control" id="regular_price" name="regular_price" placeholder="Regular Price" value="{{ old('regular_price', $slider->regular_price) }}">
                                                    <small class="text-danger error-message mb-3" id="error-regular_price"></small>
                                                    @error('regular_price')
                                                    <small class="text-danger" id="hidden-msg-regular_price">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sale_price">Sale Price</label>
                                                    <input type="text" class="form-control" id="sale_price" name="sale_price" placeholder="Sale Price" value="{{ old('sale_price', $slider->sale_price) }}">
                                                    <small class="text-danger error-message mb-3" id="error-sale_price"></small>
                                                    @error('sale_price')
                                                    <small class="text-danger" id="hidden-msg-sale_price">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                              <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="date_end">Date End</label>
                                                    <input type="date" class="form-control" id="date_end" name="date_end" placeholder="Date End" value="{{  old('date_end', \Carbon\Carbon::parse($slider->date_end)->format('Y-m-d'))  }}">
                                                    <small class="text-danger error-message mb-3" id="error-date_end"></small>
                                                    @error('date_end')
                                                    <small class="text-danger" id="hidden-msg-date_end">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <p class="card-description"> Status<span class="text-danger">*</span></p>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label><input type="radio" name="status" value="1" {{ $slider->status ? 'checked' : ''}}>Active</label>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label><input type="radio" name="status" value="0" {{ $slider->status ? '' : 'checked'}}>Inactive</label>
                                                </div>
                                            </div>
                                            <small class="text-danger error-message mb-3" id="error-status"></small>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" id="submitBtn">Submit</button>
                                        <a href="{{ route('cms.home-page.slider.index')}}" class="btn btn-light">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                @include('admin.common.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    @include('admin.common.scripts')
    @include('cms.home-page.slider-scripts')
    <script src="{{ asset('assets/js/common/upload-temp-image.js') }}"></script>
</body>
</html>
