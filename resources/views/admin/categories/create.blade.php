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
                                <li class="breadcrumb-item"><a href="{{ asset('admin/categories') }}">Category List</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Add New Category</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('admin.categories.store')}}"
                                        class="forms-sample form-confirm-submit">
                                        @csrf
                                        <div class="form-group">
                                            <label for="title">Category Names</label><span style="color:red">*</span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Category Name">
                                            @error('name')
                                                  <div class="text-danger" id="laravel_title_error_name">{{ $message }}</div>
                                            @enderror

                                            {{-- Client-side error --}}
                                            <div class="text-danger mt-1" id="error-name" style="display: none;"></div>
                                        </div>
                                    <button type="submit" id="submitBtn" class="btn btn-gradient-primary me-2">Submit</button>
                                    <a href="{{ route('admin.categories.index')}}" class="btn btn-light">Cancel</a>
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
    @include('admin.categories.scripts')
</body>

</html>