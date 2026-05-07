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
                        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ asset('admin/levels') }}">Level</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Level List</li>
                                </ol>
                            </nav>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <form method="GET" action="{{ route('search', 'levels') }}"
                                    class="d-flex align-items-center">
                                    <div class="input-group" style="width: 300px;">
                                        <div class="input-group-prepend bg-transparent">
                                            <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                        </div>
                                        <input type="text" class="form-control" name="search"
                                            value="{{ request('search') }}" placeholder="SEARCH IN HERE">
                                    </div>
                                </form>
                                <a href="{{ route('admin.levels.create') }}">
                                    <button type="button" class="btn btn-gradient-success btn-fw btn-lg">
                                        Add Level
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        @if (session('error'))
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Action Failed!",
                                                        text: "{{ session('error') }}",
                                                        timer: 5000,
                                                        showConfirmButton: false
                                                    });
                                                });
                                            </script>
                                        @endif
                                        @if (session('success'))
                                            <div class="alert alert-success d-none">{{ session('success') }}</div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Swal.fire({
                                                        title: " Action succeeded!",
                                                        text: "{{ session('success') }}",
                                                        icon: "success",
                                                        timer: 5000,
                                                        showConfirmButton: false
                                                    });
                                                });
                                            </script>
                                        @endif
                                        <div class="scroll-table">
                                            <table class="table" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>@sortablelink('id', 'No')</th>
                                                        <th class="col-8">@sortablelink('name', 'Name')</th>
                                                        <th class="col-3 text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($data->isEmpty())
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">
                                                                <h1>EMPTY LEVEL</h1>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($data as $level)
                                                            <tr>
                                                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                                </td>
                                                                <td class="col-8"> {{ $level->name }}</td>
                                                                <td class="col-3 text-center">
                                                                    <div class="d-inline-flex gap-2">
                                                                        <form method="POST"
                                                                            action="{{ route('admin.levels.destroy', $level->id) }}"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button"
                                                                                class="btn btn-gradient-danger btn-sm btn-delete">
                                                                                <i class="fa fa-trash-o"></i>
                                                                            </button>
                                                                        </form>
                                                                        <a href="{{ route('admin.levels.edit', $level->id) }}"
                                                                            class="btn btn-gradient-warning btn-sm">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @if (!$data->isEmpty())
                                            <div class="mt-4">
                                                {{ $data->appends(request()->except('page'))->links() }}
                                            </div>
                                        @endif
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
    </body>

    </html>
