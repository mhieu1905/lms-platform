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
                                <li class="breadcrumb-item active" aria-current="page">Teacher Application List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'applications') }}" class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search" 
                                        value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
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
                                                <th class="text-start">@sortablelink('id', 'No')</th>
                                                <th class="text-start">@sortablelink('name', 'Name')</th>
                                                <th class="text-start">@sortablelink('email', 'Email')</th>
                                                <th class="text-start">@sortablelink('major', 'Major')</th>
                                                <th class="text-start">CV file</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($applications as $user)
                                            <tr>
                                                <td class="text-start">
                                                    {{ ($applications->currentPage() - 1) * $applications->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-start">{{ Str::limit($user->name, 20) }}
                                                </td>
                                                <td class="text-start">{{ Str::limit($user->email, 30) }}
                                                </td>
                                                <td class="text-start">
                                                    @if($user->majors->isNotEmpty())
                                                    {{ $user->majors()->pluck('name')->join(', ') }}
                                                    @else
                                                    <span class="text-muted">No majors selected</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if($user->cv_file)
                                                    <a href="{{ asset('storage/' . $user->cv_file) }}" target="_blank" class="text-dark " style="text-decoration: none">
                                                        <i class="fa fa-file-pdf-o text-danger"></i> CV File
                                                    </a>
                                                    @else
                                                    <span class="text-muted">No CV file uploaded</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <!-- Approve form -->
                                                    <form class="approveForm" action="{{route('admin.users.teacher.applications.approve', $user->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="page" value="{{ $applications->currentPage() }}">
                                                        <button type="submit" class="btn btn-gradient-success btn-sm btn-action btn-approve">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Reject form -->
                                                    <form id="rejectForm-{{ $user->id }}" class="rejectForm" action="{{ route('admin.users.teacher.applications.reject', $user->id) }}" method="POST" style="display:none;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="reason" class="rejectReason">
                                                    </form>

                                                    <!-- Reject button -->
                                                    <button onclick="rejectTeacher({{ $user->id }})" type="button" class="btn btn-gradient-danger btn-sm btn-action btn-reject">
                                                        <i class="fa fa-times"></i>
                                                    </button>

                                                    <!-- Details button -->
                                                    <a href="{{ route('admin.users.teacher.applications.details', $user->id) }}" 
                                                    class="btn btn-gradient-primary btn-sm btn-action btn-details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <h1>Empty Teacher Application List</h1>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $applications->appends(request()->except('page'))->links() }}
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
