<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')

<body>
    <script src="{{ asset('assets/js/common/ckeditor.js') }}"></script>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item"><a href="{{ asset('admin/news') }}">News list</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit News </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data"
                                        action="{{ route('admin.news.update', $newsEdit->id) }}"
                                        class="forms-sample form-confirm-submit" id="myForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="title">News Title<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="title"
                                                        name="title" placeholder="News Title"
                                                        value="{{ old('title', $newsEdit->title) }}">
                                                    <small class="text-danger error-message mb-3"
                                                        id="error-title"></small>
                                                    @error('title')
                                                        <small class="text-danger"
                                                            id="hidden-msg-title">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="date">Date<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="date"
                                                        name="date" placeholder="mm/dd/yyyy"
                                                        value="{{ old('date',\Carbon\Carbon::parse($newsEdit->date)->format('Y-m-d')) }}">
                                                    <small class="text-danger error-message mb-3"
                                                        id="error-date"></small>
                                                    @error('date')
                                                        <small class="text-danger"
                                                            id="hidden-msg-date">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="category_id">Category<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" id="category_id" name="category_id"
                                                        style="padding: 10px">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('category_id', $newsEdit->category_id) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger error-message mb-3"
                                                        id="error-category_id"></small>
                                                    @error('category_id')
                                                        <small class="text-danger"
                                                            id="hidden-msg-category">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">News Image (<2MB)<span class="text-danger">*</span>
                                            </label>

                                            <div class="mb-2" id="old-image-wrapper">
                                                <img src="{{ asset('storage/uploads/news/' . $newsEdit->image) }}"
                                                    alt="Current Slider Image" id="old-image"
                                                    style="max-width: 200px; border-radius: 4px;">
                                            </div>
                                            {{-- Preview image --}}
                                            <div class="mb-2" id="new-image-preview" style="display: none;">
                                                <img src="" alt="New Image Preview" id="preview-image"
                                                    style="max-width: 200px; border-radius: 4px;">
                                            </div>
                                            <input type="file" id="image" name="image" class="form-control"
                                                accept=".jpeg,.jpg,.png,.gif,.webp" value="{{ old('image') }}">
                                            <small class="text-danger error-message mb-3" id="error-image"></small>
                                            @error('image')
                                                <small class="text-danger"
                                                    id="hidden-msg-image">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Description<span class="text-danger">*</span></label>
                                            <textarea id="editor" name="description">{{ old('description', $newsEdit->description) }}</textarea>
                                            <small class="text-danger error-message mb-3" id="error-editor"></small>
                                            @error('description')
                                                <small class="text-danger"
                                                    id="hidden-msg-description">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2"
                                            id="submitBtn">Submit</button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-light">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.common.footer')
            </div>
        </div>
    </div>
    @include('admin.common.scripts')
    @include('admin.news.scripts')
</body>

</html>
