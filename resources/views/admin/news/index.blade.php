<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')


<body>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ asset('admin/news') }}">News</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">News List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'news') }}"
                                class="d-flex align-items-center">
                                <div class="input-group" style="width: 300px;">
                                    <div class="input-group-prepend bg-transparent">
                                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                    </div>
                                    <input type="text" class="form-control" id="customSearch" name="search"
                                        value="{{{request('search')}}}" placeholder="SEARCH IN HERE">
                                </div>
                            </form>
                            <a href="{{ route('admin.news.create') }}">
                                <button type="button" class="btn btn-gradient-success btn-fw btn-lg">
                                    Add News
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
                                                    <th>@sortablelink('user_id', 'Author')</th>
                                                    <th class="text-start col-1">Image</th>
                                                    <th>@sortablelink('date', 'Date')</th>
                                                    <th>@sortablelink('category_id', 'Category')</th>
                                                    <th>@sortablelink('status', 'Status')</th>
                                                    <th class="text-center col-1">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($news as $data)
                                                    <tr>
                                                        <td class="text-start">
                                                            {{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td class="text-start" title="{{ $data->title }}">
                                                            {{ Str::limit($data->title, limit: 30) }}
                                                        </td>
                                                        <td class="text-start">{{ $data->user->name }}</td>
                                                        <td> <img src="{{ asset('storage/uploads/news/' . $data->image) }}" alt=""></td>
                                                        <td>{{ $data->date->format('d/m/Y') }}</td>
                                                        <td>{{ Str::limit($data->category->name, limit: 15) ?? 'N/A' }}</td>
                                                        <td class="">
                                                            <button type="button" data-id="{{ $data->id }}"
                                                                data-status="{{ $data->status }}"
                                                                class="toggle-status btn btn-sm btn-gradient-{{ $data->status ? 'success' : 'dark' }}">
                                                                {{ $data->status ? 'Public' : 'Hidden' }}
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <form action="{{ route('admin.news.destroy', $data->id) }}"
                                                                method="POST" style="display:inline;"
                                                                class="delete-form form-confirm-submit">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-gradient-danger btn-sm btn-delete"><i
                                                                        class="fa fa-trash-o"></i></button>
                                                            </form>
                                                            <a href="{{ route('admin.news.edit', $data->id) }}">
                                                                <button type="button"
                                                                    class="btn btn-gradient-warning btn-sm">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted">
                                                            <h1>Empty News List</h1>
                                                        </td>
                                                    </tr>
                                                @endforelse
    
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $news->appends(request()->except('page'))->links() }} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.common.footer')
            </div>
        </div>
    </div>
    @include('admin.common.scripts')
    @include('admin.news.scripts')
</body>

</html>
