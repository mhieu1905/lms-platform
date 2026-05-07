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
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item active" aria-current="page">Event List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'events') }}" class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search"
                                        value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
                            <a href="{{ route('admin.events.create') }}">
                                <button type="button" class="btn btn-gradient-success btn-fw">
                                    Add Event
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
                                                <th>@sortablelink('title', 'Title')</th>
                                                <th>@sortablelink('start_time', 'Time')</th>
                                                <th>Schedule</th>
                                                <th>@sortablelink('total_slots', 'Slots')</th>
                                                <th class="text-center">Image</th>
                                                <th class="text-center">@sortablelink('status', 'Status')</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($events as $event)
                                            <tr>
                                                <td class="text-start">
                                                    {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-start">
                                                    {{ Str::limit($event->title, 30) }}
                                                </td>
                                                <td class="text-start">
                                                    {{ $event->start_time->format('g:iA - d/m/Y') }} <br><br>
                                                    {{ $event->finish_time->format('g:iA - d/m/Y') }}
                                                </td>
                                                <td class="text-start">
                                                @if($event->start_time < now() && $event->finish_time > now())
                                                    <span class="badge bg-info badge_status">Happening</span>
                                                @elseif($event->finish_time < now())
                                                    <span class="badge bg-danger badge_status">Expired</span>
                                                @else
                                                    <span class="badge bg-success badge_status">Upcoming</span>
                                                @endif
                                                </td>
                                                <td class="text-start">
                                                    {{ $event->booked_slots }} / {{ $event->total_slots }}
                                                </td>
                                                @if($event->image)
                                                <td class="text-center"><img src="{{ asset('storage/' . $event->image) }}" alt="Event image" style="width: 70px; height: 40px; border-radius: 0;"></td>
                                                @else
                                                <td class="text-center"><span class="text-muted">No image</span></td>
                                                @endif
                                                <td class="text-center">
                                                    <button type="button" data-id="{{ $event->id }}" data-status="{{ $event->status }}" class="toggle-status btn btn-sm btn-gradient-{{ $event->status ? 'success' : 'dark' }}">
                                                        {{ $event->status ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline;" class="delete-form form-confirm-submit">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="page" value="{{ $events->currentPage() }}">
                                                        <button type="button" class="btn btn-gradient-danger btn-sm btn-action btn-delete"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                    <a href="{{ route('admin.events.edit', ['id' => $event->id]) }}">
                                                        <button type="button" class="btn btn-gradient-warning btn-sm btn-action"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    <h1>Empty Event List</h1>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $events->appends(request()->except('page'))->links() }}
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
    @include('admin.events.scripts')
</body>

</html>
