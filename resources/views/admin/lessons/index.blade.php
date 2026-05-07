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
                                    <a href="{{ asset('admin/lessons') }}">Lesson</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Lesson List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'lessons') }}"
                                class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search"
                                        value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
                            <a href="{{ route('admin.lessons.create') }}">
                                <button type="button" class="btn btn-gradient-success btn-fw btn-lg">
                                    Add Lesson
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
                                                    <th>@sortablelink('title', 'Title')</th>
                                                    <th>@sortablelink('chapter_id', 'Chapter')</th>
                                                    <th>@sortablelink('chapter.course_id', 'Courses')</th>
                                                    <th>@sortablelink('duration', "Duration (Minutes)")</th>
                                                    <th>@sortablelink('status', 'Status')</th>
                                                    <th class="text-center col-1">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($data->isEmpty())
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">
                                                            <h1>EMPTY LESSON</h1>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @foreach ($data as $lesson)
                                                        <tr>
                                                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                            </td>
                                                            <td title="{{ $lesson->title }}">
                                                                {{ Str::limit($lesson->title, limit: 40) }}</td>
                                                            <td>{{ Str::limit($lesson->chapter->title, 15) }}</td>
                                                            <td>{{ $lesson->chapter->course->title }}</td>
                                                            <td>{{ $lesson->duration }} </td>
                                                            <td>{{ $lesson->status === 1 ? 'Purchase' : 'Trial' }}</td>
                                                            <td class= "text-center">
                                                                <form method="POST"
                                                                    action="{{ route('admin.lessons.destroy', $lesson->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-gradient-danger btn-sm btn-delete">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </button>
                                                                    <a href="{{ route('admin.lessons.edit', $lesson->id) }}"
                                                                        class="btn btn-gradient-warning btn-sm">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                </form>
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
                    <!-- content-wrapper ends -->
                    <!-- partial:../../partials/_footer.html -->
                    <!-- partial -->
                </div>
                @include('admin.common.footer')

                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        @include('admin.common.scripts')
</body>

</html>
