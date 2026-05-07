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
                                <li class="breadcrumb-item active" aria-current="page">Admin List</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <form method="GET" action="{{ route('search', 'admins') }}" class="d-flex align-items-center">
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
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($admins as $user)
                                            <tr>
                                                <td class="text-start">
                                                    {{ ($admins->currentPage() - 1) * $admins->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-start">{{ Str::limit($user->name, 20) }}
                                                </td>
                                                <td class="text-start">{{ Str::limit($user->email, 30) }}
                                                </td>
                                                <td class="text-center">
                                                    @if($user->id !== Auth::user()->id)
                                                    <form action="{{ route('admin.users.admins.destroy', $user->id) }}" method="POST" style="display:inline;" class="delete-form form-confirm-submit">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="page" value="{{ $admins->currentPage() }}">
                                                        <button type="button" class="btn btn-gradient-danger btn-sm btn-delete btn-action"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <h1>Empty Admin List</h1>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $admins->appends(request()->except('page'))->links() }}
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
