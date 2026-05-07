<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<script type="text/javascript" src="{{ asset('assets/js/common/iconify.min.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('assets/css/common/cmsfooter.css') }}">

<body>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ asset('cms/footers') }}">Footer</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Footer Social Create</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="col-1">@sortablelink('id', 'No')</th>
                                                <th class="col-3">@sortablelink('key_id', 'Type')</th>
                                                <th class="col-3">Content</th>
                                                <th class="col-3 text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <h1>EMPTY FOOTER</h1>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($data as $footer)
                                                    <tr>
                                                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td class="col-3"> {{ $footer->key->name }}</td>
                                                        @if (isset($footer->content->logo))
                                                            <td>
                                                                <img src="{{ asset('assets/images/home/logo/' . $footer->content->logo) }}"
                                                                    alt="">
                                                            </td>
                                                        @elseif (isset($footer->content->copyright))
                                                            <td class="col-3">{{ $footer->content->copyright }}</td>
                                                        @elseif (isset($footer->content->title))
                                                            <td class="col-3">{{ $footer->content->title }}</td>
                                                        @elseif (isset($footer->content->title))
                                                            <td class="col-3">{{ $footer->content->title }}</td>
                                                        @elseif (isset($footer->content->items))
                                                            <td class="col-3">
                                                                <ul
                                                                    class="d-flex align-items-center gap-15px list-unstyled">
                                                                    @foreach ($footer->content->items as $item)
                                                                        <li>

                                                                            <a href="#" class="transition-all">
                                                                                <i class="iconify fs-24"
                                                                                    data-icon="{{ $item->label }}"></i>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                        @endif
                                                        <td class="col-3 text-center">
                                                            <div class="d-inline-flex gap-2">
                                                                <form method="POST"
                                                                    action="{{ route('cms.footers.destroy', $footer->id) }}"
                                                                    class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-gradient-danger btn-sm btn-delete">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </button>
                                                                </form>
                                                                <a href="{{ route('cms.footers.edit', $footer->id) }}"
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
                @include('admin.common.footer')
            </div>
        </div>
    </div>
    @include('admin.common.scripts')
    @include('cms.footers.scripts')
</body>

</html>
