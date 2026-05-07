<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.common.header')
    <script src="{{ asset('assets/js/common/ckeditor.js') }}"></script>
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        @include('admin.common.navbar')
        <!-- Sidebar -->
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <!-- Main Panel -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="margin: 0; padding: 0;">
                                <li class="breadcrumb-item"><a href="{{ asset('admin/lessons') }}">Lesson List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Lesson</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" class="forms-sample form-confirm-submit"
                                        action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data"
                                        id="myForm">
                                        @csrf
                                        <!-- Lesson Title -->
                                        <div class="form-group">
                                            <label for="exampleInputName1">Lesson Title</label><span
                                                style="color:red">*</span>
                                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}"
                                                placeholder="Title">
                                            <!-- Laravel backend error -->
                                            @error('title')
                                                <div class="text-danger mt-1" id="laravel_title_error_title">
                                                    {{ $message }}</div>
                                            @enderror
                                            <!-- Client-side error -->
                                            <div class="text-danger mt-1" id="error-title" style="display: none;"></div>
                                        </div>

                                        <!-- Course -->
                                        <div class="form-group">
                                            <label for="exampleInputName1">Course</label><span
                                                style="color:red">*</span>
                                            <select class="form-select" name="course_id" id="course_id">
                                                <option value="">Chose a course</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                            <!-- Laravel backend error -->
                                            @error('course_id')
                                                <div class="text-danger" id="laravel_title_error_course_id">
                                                    {{ $message }}</div>
                                            @enderror
                                            <!-- Client-side error -->
                                            <div class="text-danger mt-1" id="error-course_id" style="display: none;">
                                            </div>
                                        </div>

                                        <!-- Chapter -->
                                        <div class="form-group">
                                            <label for="exampleInputName1">Chapter</label><span
                                                style="color:red">*</span>
                                            <select class="form-select" name="chapter_id" id="chapter_id">
                                                <option value="">Chose a chapter</option>
                                                @foreach ($chaps as $chap)
                                                    <option value="{{ $chap->id }}" @selected(old('chapter_id') == $chap->id)>{{ $chap->title }}</option>
                                                @endforeach
                                            </select>
                                            <!-- Laravel backend error -->
                                            @error('chapter_id')
                                                <div class="text-danger" id="laravel_title_error_chapter_id">
                                                    {{ $message }}</div>
                                            @enderror
                                            <!-- Client-side error -->
                                            <div class="text-danger mt-1" id="error-chapter_id" style="display: none;">
                                            </div>
                                        </div>

                                        <!-- Video Upload -->
                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="video" id="video"
                                                class="file-upload-default" accept=".mp4,.avi,.mov,.mkv,.webm">
                                            <div
                                                class="input-group
                                                col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled
                                                    placeholder="Upload Video">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-gradient-primary py-3"
                                                        type="button">Upload</button>
                                                </span>
                                            </div>
                                            <video id="preview" controls
                                                style="display:none; width:400px; margin-top:10px;"></video>
                                            <input type="hidden" id="video_url" name="video_url">
                                            <!-- Laravel backend error -->
                                            @error('video')
                                                <div class="text-danger" id="laravel_title_error_video">{{ $message }}
                                                </div>
                                            @enderror
                                            <!-- Client-side error -->
                                            <div class="text-danger mt-1" id="error-video" style="display: none;"></div>
                                        </div>

                                        <!-- Content -->
                                        <div class="form-group">
                                            <label for="ck_editor">Content <span style="color:red">*</span></label>
                                            <textarea class="form-control" name="content" id="ck_editor" rows="10">{{ old('content') }}</textarea>

                                            @error('content')
                                                <div class="text-danger" id="laravel_title_error_ck_editor">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            <div class="text-danger mt-1" id="error-ck_editor" style="display: none;">
                                            </div>
                                        </div>

                                        <!-- Duration and Status -->
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <label for="exampleInputName1">Duration</label><span
                                                        style="color:red">*</span>
                                                    <input type="number" min="0" max="999" class="form-control" name="duration"
                                                        id="duration" placeholder="Minutes" value="{{ old('duration') }}">
                                                    <!-- Laravel backend error -->
                                                    @error('duration')
                                                        <div class="text-danger" id="laravel_title_error_duration">
                                                            {{ $message }}</div>
                                                    @enderror
                                                    <!-- Client-side error -->
                                                    <div class="text-danger mt-1" id="error-duration"
                                                        style="display: none;">
                                                    </div>
                                                </div>

                                                <div class="status col-lg-5">
                                                    <label>Status</label><span style="color:red">*</span><br>
                                                    <div class="row mt-3">
                                                        <div class="col-lg-3">
                                                            <input type="radio" name="status" id="status_0"
                                                                value="0" {{ old('status') == '0' ? 'checked' : '' }}> Trial
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <input type="radio" name="status" id="status_1"
                                                                value="1" {{ old('status') == '1' || old('status') === null ? 'checked' : '' }}> Purchase
                                                        </div>
                                                    </div>
                                                    <!-- Laravel backend error -->
                                                    @error('status')
                                                        <div class="text-danger" id="laravel_title_error_status">
                                                            {{ $message }}</div>
                                                    @enderror
                                                    <!-- Client-side error -->
                                                    <div class="text-danger mt-1" id="error-status"
                                                        style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-gradient-primary me-2"
                                            id="submitBtn">Submit</button>
                                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-light">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- Footer -->
                @include('admin.common.footer')
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    @include('admin.common.scripts')
    <script src="{{ asset('assets/js/common/course-chapter-handle.js') }}"></script>
    @include('admin.lessons.scripts')
</body>

</html>
