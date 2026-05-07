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
                                <ol class="breadcrumb p-0">
                                    <li class="breadcrumb-item active" aria-current="page">Major List</li>
                                </ol>
                            </nav>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <form method="GET" action="{{ route('search', 'majors') }}" class="d-flex align-items-center">
                                    <div class="input-group" style="width: 300px;">
                                        <div class="input-group-prepend bg-transparent">
                                            <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                        </div>
                                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="SEARCH IN HERE">
                                    </div>
                                </form>
                                <a href="{{ route('admin.majors.create') }}">
                                    <button type="button" class="btn btn-gradient-success btn-fw btn-lg">
                                        Add Major
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
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
                                                @forelse($majors as $major)
                                                <tr>
                                                    <td>{{ ($majors->currentPage() - 1) * $majors->perPage() + $loop->iteration }}</td>
                                                    <td class="col-8">{{ $major->name }}</td>
                                                    <td class="col-3 text-center">
                                                        <div class="d-inline-flex gap-2">
                                                            <form method="POST" action="{{ route('admin.majors.destroy', $major->id) }}" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="page" value="{{ $majors->currentPage() }}">
                                                                <button type="button" class="btn btn-gradient-danger btn-sm btn-delete btn-action">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('admin.majors.edit', $major->id) }}" class="btn btn-gradient-warning btn-sm btn-action">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <td colspan="3" class="text-center text-muted">
                                                    <h1>Empty Major List</h1>
                                                </td>
                                                @endforelse


                                            </tbody>
                                        </table>
                                        </div>
                                        {{ $majors->appends(request()->except('page'))->links() }}
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
