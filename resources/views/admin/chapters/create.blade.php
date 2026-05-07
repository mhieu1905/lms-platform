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
                            <ol class="breadcrumb" style="margin: 0; padding: 0;">
                                <li class="breadcrumb-item"><a href="{{ asset('admin/chapters') }}">Chapter List</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Add New Chapter</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.chapters.store') }}"
                                        class="forms-sample form-confirm-submit" id="chapterForm">
                                        @csrf

                                        <div class="form-group">
                                            <label for="title">Chapter Title<span style="color:red">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                placeholder="Chapter Title">

                                            {{-- Laravel backend error --}}
                                            @error('title')
                                                <div class="text-danger" id="laravel_title_error_title">{{ $message }}
                                                </div>
                                            @enderror

                                            {{-- Client-side error --}}
                                            <div class="text-danger mt-1" id="error-title" style="display: none;"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="course_id">Course<span style="color:red">*</span></label>
                                            <select class="form-select" id="course_id" name="course_id">
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                            </select>

                                            {{-- Laravel backend error --}}
                                            @error('course_id')
                                                <div class="text-danger" id="laravel_title_error_course_id">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            {{-- Client-side error --}}
                                            <div class="text-danger mt-1" id="error-course_id" style="display: none;">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-gradient-primary me-2" id="submitBtn"
                                            disabled>Submit</button>
                                        <a href="{{ route('admin.chapters.index') }}" class="btn btn-light">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                @include('admin.common.footer')
            </div>
            @include('admin.common.scripts')
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
</body>
@include('admin.chapters.scripts')

</html>
