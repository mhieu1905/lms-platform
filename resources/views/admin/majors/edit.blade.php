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
                                <li class="breadcrumb-item"><a href="{{ route('admin.majors.index') }}">Major List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Major</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.majors.update', $major->id) }}" class="forms-sample form-confirm-submit" id="myForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name">Major Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Major Name" value="{{ old('name', $major->name) }}">
                                            <small class="text-danger error-message mb-3" id="error-name"></small>
                                            @error('name')
                                            <small class="text-danger" id="hidden-msg-name">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" id="submitBtn">Submit</button>
                                        <a href="{{ route('admin.majors.index') }}" class="btn btn-light">Cancel</a>
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
    @include('admin.majors.scripts')
</body>

</html>
