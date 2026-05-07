<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<body>
    <script src="{{ asset('assets/js/common/ckeditor.js') }}"></script>
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
                                <li class="breadcrumb-item"><a href="{{ asset('admin/courses') }}">Courses List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Course</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.courses.update', $course->id) }}" class="forms-sample form-confirm-submit" id="myForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="title">Course Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $course->title) }}">
                                            <small class="text-danger error-message mb-3" id="error-title"></small>
                                            @error('title')
                                            <small class="text-danger" id="hidden-msg-title">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="regular_price">Regular Price ($)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" min="0" max="999999.99" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price', $course->regular_price) }}">
                                                    <small class="text-danger error-message mb-3" id="error-regular_price"></small>
                                                    @error('regular_price')
                                                    <small class="text-danger" id="hidden-msg-regular_price">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sale_price">Sale Price ($)</label>
                                                    <input type="number" step="any" min="0" max="999999.99" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $course->sale_price) }}">
                                                    <small class="text-danger error-message mb-3" id="error-sale_price"></small>
                                                    @error('sale_price')
                                                    <small class="text-danger" id="hidden-msg-sale_price">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="duration">Duration (Month)<span class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control" id="duration" name="duration" min="1" max="120" value="{{ old('duration', $course->duration) }}">
                                                    <small class="text-danger error-message mb-3" id="error-duration"></small>
                                                    @error('duration')
                                                    <small class="text-danger" id="hidden-msg-duration">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Course Image (<2MB)<span class="text-danger">*</span></label>

                                            {{-- Old image --}}
                                            @if($course->image && !old('image_path'))
                                            <div class="mb-2" id="old-image-wrapper">
                                                <img src="{{ asset('storage/' . $course->image) }}" alt="Current Course Image" id="old-image">
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
                                                <input type="file" id="image" style="display:none;" accept=".jpeg,.jpg,.png,.gif,.webp" data-type="courses" data-preview="#preview-image">
                                            </div>
                                            <small class=" text-danger error-message mb-3" id="error-image"></small>
                                            @error('image')
                                            <small class="text-danger" id="hidden-msg-image">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Description<span class="text-danger">*</span></label>
                                            <textarea id="editor" name="description">{{ old('description', $course->description) }}</textarea>
                                            <small class="text-danger error-message mb-3" id="error-editor"></small>
                                            @error('description')
                                            <small class="text-danger" id="hidden-msg-description">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="category_id">Category<span class="text-danger">*</span></label>
                                                    <select class="form-select" id="category_id" name="category_id">
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id', $course->category_id) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger error-message mb-3" id="error-category_id"></small>
                                                    @error('category_id')
                                                    <small class="text-danger" id="hidden-msg-category">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="level_id">Level<span class="text-danger">*</span></label>
                                                    <select class="form-select" id="level_id" name="level_id" value="{{ old('level_id', $course->level_id) }}">
                                                        @foreach($levels as $level)
                                                        <option value="{{ $level->id }}" {{ $level->id == old('level_id', $course->level_id) ? 'selected' : '' }}>
                                                            {{ $level->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger error-message mb-3" id="error-level_id"></small>
                                                    @error('level_id')
                                                    <small class="text-danger" id="hidden-msg-level">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="language">Language<span class="text-danger">*</span></label>
                                                    <select class="form-select" id="language" name="language">
                                                        <option value="English" {{ old('language', $course->language) == 'English' ? 'selected' : '' }}>English</option>
                                                        <option value="Vietnamese" {{ old('language', $course->language) == 'Vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                                                        <option value="Chinese" {{ old('language', $course->language) == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                                    </select>
                                                    <small class="text-danger error-message mb-3" id="error-language"></small>
                                                    @error('language')
                                                    <small class="text-danger" id="hidden-msg-language">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" id="submitBtn">Submit</button>
                                        <a href="{{ route('admin.courses.index')}}" class="btn btn-light">Cancel</a>
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
    @include('admin.courses.scripts')
    <script src="{{ asset('assets/js/common/upload-temp-image.js') }}"></script>
</body>
</html>
