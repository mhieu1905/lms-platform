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
                                <li class="breadcrumb-item">
                                    <a href="{{ asset('admin/levels') }}">Level List</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Level</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">

                                    <form method="POST" action="{{ route('admin.levels.update', $level->id) }}" id="myForm"
                                        class="forms-sample form-confirm-submit">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="title">Level Name</label><span style="color: red">*</span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $level->name) }}" placeholder="Level Name">
                                            @error('name')
                                                  <div class="text-danger" id="laravel_title_error_name">{{ $message }}</div>
                                            @enderror

                                            <div class="text-danger mt-1" id="error-name" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2"
                                            id="submitBtn">Submit</button>
                                        <a href="{{ route('admin.levels.index') }}" class="btn btn-light">Cancel</a>
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
    @include('admin.levels.scripts')
</body>

</html>
