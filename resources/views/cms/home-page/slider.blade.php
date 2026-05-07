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
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item active" aria-current="page">Slider List</li>
                            </ol>
                        </nav>
                        <div class="d-flex flex-column align-items-end gap-3">
                            <div class="search-field d-none d-md-block">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <form method="GET" action="{{ route('search', 'sliders') }}" class="d-flex align-items-center">
                                        <div class="input-group" style="width: 300px;">
                                            <div class="input-group-prepend bg-transparent">
                                                <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                            </div>
                                            <input type="text" class="form-control" id="customSearch" name="search" 
                                                value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                        </div>
                                    </form>
                                    <a href="{{ route('cms.home-page.slider.create') }}">
                                        <button type="button" class="btn btn-gradient-success btn-fw">Add Slider</button>
                                    </a>
                                </div>
                            </div>
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
                                                <th class="text-start">@sortablelink('subtitle', 'Subtitle')</th>
                                                <th class="text-start">@sortablelink('button_text', 'Button text')</th>
                                                <th class="text-center">Image</th>
                                                <th class="text-center">@sortablelink('status', 'Status')</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sliders as $slider)
                                            <tr>
                                                <td class="text-start">{{ ($sliders->currentPage() - 1) * $sliders->perPage() + $loop->iteration }}</td>
                                                <td class="text-start">{{ Str::limit($slider->title, 30) }}</td>
                                                <td class="text-start">{{ Str::limit($slider->subtitle, 30) }}</td>
                                                <td class="text-start">{{ $slider->button_text }}</td>
                                                @if($slider->image)
                                                <td class="text-center"><img src="{{ asset('storage/' . $slider->image) }}" alt="Slider image" style="width: 70px; height: 40px; border-radius: 0;"></td>
                                                @else
                                                <td class="text-center"><span class="text-muted">No image</span></td>
                                                @endif
                                                <td class="text-center">
                                                    <button type="button" data-id="{{ $slider->id }}" data-status="{{ $slider->status }}" class="toggle-status btn btn-sm btn-gradient-{{ $slider->status ? 'success' : 'dark' }}">
                                                        {{ $slider->status ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cms.home-page.slider.destroy', $slider->id) }}" method="POST" style="display:inline;" class="delete-form form-confirm-submit">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="page" value="{{ $sliders->currentPage() }}">
                                                        <button type="button" class="btn btn-gradient-danger btn-sm btn-delete"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                    <a href="{{ route('cms.home-page.slider.edit', ['id' => $slider->id]) }}">
                                                        <button type="button" class="btn btn-gradient-warning btn-sm"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    <h1>Empty Slider List</h1>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $sliders->appends(request()->except('page'))->links() }}
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
    @include('cms.home-page.slider-scripts')
</body>
</html>
