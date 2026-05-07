<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')

@php
use App\Models\Course;
@endphp

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
                                <li class="breadcrumb-item active" aria-current="page">Course List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'courses') }}" class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search"
                                        value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
                            <a href="{{ route('admin.courses.create') }}">
                                <button type="button" class="btn btn-gradient-success btn-fw btn-lg">
                                    Add Course
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
                                                <th class="text-start">@sortablelink('id', 'No')</th>
                                                <th class="text-start">@sortablelink('title', 'Title')</th>
                                                <th class="text-start">@sortablelink('category.name', 'Category')</th>
                                                <th class="text-start">@sortablelink('status', 'Status')</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($courses as $course)
                                            <tr>
                                                <td class="text-start">
                                                    {{ ($courses->currentPage() - 1) * $courses->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-start">{{ Str::limit($course->title, 80) }}
                                                </td>
                                                <td class="text-start">{{ $course->category->name ?? 'N/A' }}</td>
                                                <td class="text-start">
                                                    @php
                                                    $statusMap = [
                                                    Course::STATUS_PENDING => ['class' => 'badge-info'],
                                                    Course::STATUS_PUBLISHING => ['class' => 'badge-success'],
                                                    Course::STATUS_HIDDEN => ['class' => 'badge-secondary'],
                                                    ];

                                                    $currentLabel = $statusMap[$course->status];
                                                    @endphp
                                                    <span class="badge {{ $currentLabel['class'] }} badge_status">
                                                        {{ Course::$statusLabels[$course->status] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:inline;" class="delete-form form-confirm-submit">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="page" value="{{ $courses->currentPage() }}">
                                                        <button type="button" class="btn btn-gradient-danger btn-sm btn-delete btn-action"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                    <a style="text-decoration: none;" href="{{ route('admin.courses.edit', ['id' => $course->id]) }}">
                                                        <button type="button" class="btn btn-gradient-warning btn-sm btn-action"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                    @if(Auth::check() && Auth::user()->hasRole('admin'))
                                                    @php
                                                    $statusMap = [
                                                    Course::STATUS_PENDING => ['class' => 'btn-gradient-success', 'icon' => 'fa-check'],
                                                    Course::STATUS_PUBLISHING => ['class' => 'btn-gradient-secondary', 'icon' => 'fa-eye-slash'],
                                                    Course::STATUS_HIDDEN => ['class' => 'btn-gradient-success', 'icon' => 'fa-eye'],
                                                    ];

                                                    $currentButton = $statusMap[$course->status];
                                                    @endphp

                                                    <button type="button" data-id="{{ $course->id }}" data-status="{{ $course->status }}" class="toggle-status btn {{ $currentButton['class'] }} btn-sm btn-action" style="display:inline;">
                                                        <i class="fa {{ $currentButton['icon'] }}"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <h1>Empty Course List</h1>
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $courses->appends(request()->except('page'))->links() }}
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
    @include('admin.courses.scripts')
</body>

</html>
