<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.common.header')
    <script src="{{ asset('assets/js/common/ckeditor.js') }}"></script>
</head>
<body>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ asset('admin/lessons')}}">Lesson List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Lesson</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" class="forms-sample form-confirm-submit" id="myForm"
                                        action="{{ route('admin.lessons.update', $lesson->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <!-- Title Field -->
                                        <div class="form-group">
                                            <label for="title">Lesson Title</label><span style="color:red">*</span>
                                            <input type="text" class="form-control" name="title" id="title"
                                                placeholder="Lesson Title" value="{{ old('title', $lesson->title) }}">
                                            @error('title')
                                                <div class="text-danger" id="laravel_title_error_title">{{ $message }}</div>
                                            @enderror
                                            <div class="text-danger mt-1" id="error-title" style="display: none;"></div>
                                        </div>

                                        <!-- Course Field -->
                                        <div class="form-group">
                                            <label for="course_id">Course</label><span style="color:red">*</span>
                                            <select class="form-select" name="course_id" id="course_id">
                                                <option value="">Choose a course</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        @selected(old('course_id', $course->id == $lesson->chapter->course_id))>
                                                        {{ $course->title}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <div class="text-danger" id="laravel_title_error_course_id">{{ $message }}</div>
                                            @enderror
                                            <div class="text-danger mt-1" id="error-course_id" style="display: none;"></div>
                                        </div>

                                        <!-- Chapter Field -->
                                        <div class="form-group">
                                            <label for="chapter_id">Chapter</label><span style="color:red">*</span>
                                            <select class="form-select" name="chapter_id" id="chapter_id"
                                                data-selected="{{ old('chapter_id', $lesson->chapter_id) }}">
                                                @foreach ($chaps as $chap)
                                                    <option value="{{ $chap->id }}"
                                                        @selected(old('chapter_id', $chap->id == $lesson->chapter_id))>
                                                        {{ $chap->title}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('chapter_id')
                                                <div class="text-danger" id="laravel_title_error_chapter_id">{{ $message }}</div>
                                            @enderror
                                            <div class="text-danger mt-1" id="error-chapter_id" style="display: none;"></div>
                                        </div>

                                        <!-- Video Field -->
                                        <div class="form-group">
                                            <label>Video upload</label>
                                            <input type="file" name="video" id="video" class="file-upload-default" accept=".mp4,.avi,.mov,.mkv,.webm">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled
                                                    placeholder="Upload Video" value="{{ $lesson->video }}">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-gradient-primary py-3"
                                                        type="button">Upload</button>
                                                </span>
                                            </div>
                                            <video id="preview" controls style="display:{{ $src ? 'block' : 'none' }}; 
                                            width:400px; margin-top:10px;">
                                            <source src="{{ $src }}" type="video/mp4">
                                            </video>
                                            <input type="hidden" id="video_url" name="video_url">

                                            @error('video')
                                                <div class="text-danger" id="laravel_title_error_video">{{ $message }}</div>
                                            @enderror
                                            <div class="text-danger mt-1" id="error-video" style="display: none;"></div>
                                        </div>

                                        <!-- Content Field -->
                                        <div class="form-group">
                                            <label for="ck_editor">Content <span style="color:red">*</span></label>
                                            <textarea class="form-control" name="content" id="ck_editor" rows="10">{{ old('content', $lesson->content) }}</textarea>
                                            @error('content')
                                                <div class="text-danger" id="laravel_title_error_ck_editor">{{ $message }}</div>
                                            @enderror
                                            <div class="text-danger mt-1" id="error-ck_editor" style="display: none;"></div>
                                        </div>

                                        <!-- Duration and Status -->
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <label for="duration">Duration</label><span style="color:red">*</span>
                                                    <input type="number" class="form-control" id="duration"
                                                        name="duration" placeholder="Minutes"
                                                        value="{{ old('duration', $lesson->duration) }}">
                                                    @error('duration')
                                                        <div class="text-danger" id="laravel_title_error_duration">{{ $message }}</div>
                                                    @enderror
                                                    <div class="text-danger mt-1" id="error-duration" style="display: none;"></div>
                                                </div>
                                                <div class="status col-lg-5">
                                                    <label>Status</label><span style="color:red">*</span><br>
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <input type="radio" name="status" value="0" id="status_0" 
                                                                {{ old('status', $lesson->status) == 0 ? 'checked' : '' }}>
                                                            Trial
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <input type="radio" name="status" value="1" id="status_1" 
                                                                {{ old('status', $lesson->status) == 1 ? 'checked' : '' }}>
                                                            Purchase
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary me-2">Submit</button>
                                        <a href="{{ route('admin.lessons.index')}}" class="btn btn-light">Cancel</a>
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
    <!-- Include validation script BEFORE course-chapter-handle.js -->
    @include('admin.lessons.scripts')
    <script src="{{ asset('assets/js/common/course-chapter-handle.js') }}"></script>
</body>
</html>