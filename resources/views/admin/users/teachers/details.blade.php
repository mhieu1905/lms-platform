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
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.teachers.index') }}">Teacher List</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Teacher Details</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'applications') }}" class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <div class="scroll-table">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $teacher->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $teacher->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Avatar</th>
                                            @if(!$teacher->avatar)
                                            <td class="text-muted">Not provided</td>
                                            @else
                                            <td><img src="{{ asset('storage/' . $teacher->avatar) }}" alt="Teacher avatar"></td>
                                            @endif

                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            @if(!$teacher->phone)
                                            <td class="text-muted">Not provided</td>
                                            @else
                                            <td>{{ $teacher->phone }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            @if(!$teacher->address)
                                            <td class="text-muted">Not provided</td>
                                            @else
                                            <td>{{ $teacher->address }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Majors</th>
                                            @if($teacher->majors->isNotEmpty())
                                            <td>{{ $teacher->majors->pluck('name')->join(', ') }}</td>
                                            @else
                                            <td><span class="text-muted">No majors selected</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>CV File</th>
                                            <td class="text-start">
                                                @if($teacher->cv_file)
                                                <a href="{{ asset('storage/' . $teacher->cv_file) }}" target="_blank" class="text-dark " style="text-decoration: none">
                                                    <i class="fa fa-file-pdf-o text-danger"></i> CV File
                                                </a>
                                                @else
                                                <span class="text-muted">No CV file uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Courses Created</th>
                                            <td>{{ $teacher->courses_count }}</td>
                                        </tr>
                                        <tr>
                                            <th>Overview</th>
                                            @if($teacher->overview)
                                            <td class="text-wrap lh-base">{{ $teacher->overview }}</td>
                                            @else
                                            <td><span class="text-muted">Not provided</span></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Created at</th>
                                            <td>{{ $teacher->created_at->format('d/m/Y - H:i') }}</td>
                                        </tr>
                                        @if($teacher->id !== Auth::user()->id)
                                        <tr>
                                            <th>Actions</th>
                                            <td>
                                                <form action="{{ route('admin.users.teachers.destroy', $teacher->id) }}" method="POST" style="display:inline;" class="delete-form form-confirm-submit">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-gradient-danger btn-sm btn-delete btn-action"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endif

                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($teacher->courses_count > 0)
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>List of courses created</h5><br>
                                        <div class="scroll-table">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Course Title</th>
                                                <th>Created At</th>
                                            </tr>
                                            @foreach($teacher->courses as $course)
                                            <tr>
                                                <td class="text-wrap lh-base">
                                                <a style="text-decoration: none; color: black" href="{{ route('courses.show', ['id' => $course->id]) }}">
                                                    {{ Str::limit($course->title, 110) }}
                                                </a>
                                                </td>
                                                <td>{{ $course->created_at->format('d/m/Y - H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
