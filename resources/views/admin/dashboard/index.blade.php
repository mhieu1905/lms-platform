<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<body>
    <!-- partial:partials/_navbar.html -->
    @include('admin.common.navbar')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('admin.common.sidebar')
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white me-2">
                            <i class="mdi mdi-home"></i>
                        </span> Dashboard
                    </h3>
                </div>

                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Revenue <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                                </h4>
                                <h2 class="mb-5">{{ '$' . number_format($totalAmount, 2, '.', ',') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <a style="text-decoration: none; color: aliceblue;" href="{{ route('admin.courses.index') }}">
                                    <h4 class="font-weight-normal mb-3">Total Courses <i class="mdi mdi-book-open mdi-24px float-end"></i>
                                    </h4>
                                </a>
                                <h2 class="mb-5">{{ number_format($totalCourses, 0, '.', ',') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <a style="text-decoration: none; color: aliceblue;" href="{{ route('admin.courses.index') }}">
                                    <h4 class="font-weight-normal mb-3">Published Courses <i class="mdi mdi-book-check mdi-24px float-end"></i>
                                    </h4>
                                </a>
                                <h2 class="mb-5">{{ number_format($totalPublishedCourses, 0, '.', ',') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                @if($isAdmin)
                <div class="row">
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Users <i class="mdi mdi-account-group mdi-24px float-end"></i>
                                </h4>
                                <h2 class="mb-5">{{ number_format($totalUsers, 0, '.', ',') }} <i class="mdi mdi-account"></i></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <a style="text-decoration: none; color: aliceblue;" href="{{ route('admin.users.students.index') }}">
                                    <h4 class="font-weight-normal mb-3">Students <i class="mdi mdi-school mdi-24px float-end"></i>
                                    </h4>
                                </a>
                                <h2 class="mb-5">{{ number_format($students, 0, '.', ',') }} <i class="mdi mdi-account"></i></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <a style="text-decoration: none; color: aliceblue;" href="{{ route('admin.users.teachers.index') }}">
                                    <h4 class="font-weight-normal mb-3">Teachers <i class="mdi mdi-account-tie mdi-24px float-end"></i>
                                    </h4>
                                </a>
                                <h2 class="mb-5">{{ number_format($teachers, 0, '.', ',') }} <i class="mdi mdi-account"></i></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/home/dashboard/circle.png') }}" class="card-img-absolute" alt="circle-image" />
                                <a style="text-decoration: none; color: aliceblue;" href="{{ route('admin.users.admins.index') }}">
                                    <h4 class="font-weight-normal mb-3">Admins <i class="mdi mdi-shield-account mdi-24px float-end"></i>
                                    </h4>
                                </a>
                                <h2 class="mb-5">{{ number_format($admins, 0, '.', ',') }} <i class="mdi mdi-account"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Monthly Revenue</h4>
                                <canvas id="barChart" style="height:230px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Best-Selling Courses</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Course Title </th>
                                                @if($isAdmin)
                                                <th> Owner </th>
                                                @endif
                                                <th> Total Orders </th>
                                                <th>Total Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topCourses as $index => $item )
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if($item->product)
                                                    <a style="text-decoration: none; color: black" href="{{ route('courses.show', ['id' => $item->product->id]) }}">
                                                            {{ Str::limit($item->product->title, 80) }}
                                                    </a>
                                                    @else
                                                    <span class="text-muted">Product not found</span>
                                                    @endif
                                                </td>

                                                @if($isAdmin)
                                                <td class="d-flex align-items-center">
                                                    @if($item->product && $item->product->user)
                                                    <a style="text-decoration: none; color: black;" href="{{ route('admin.users.teachers.details', $item->product->user->id) }}">
                                                        @if($item->product->user->avatar)
                                                        <img src="{{ asset('storage/' . $item->product->user->avatar) }}" alt="{{ $item->product->user->name }}" class="rounded-circle me-2" width="40" height="40">
                                                        @else
                                                        <img src="{{ asset('assets/images/common/user_placeholder.png') }}" alt="{{ $item->product->user->name }}" class="rounded-circle me-2" width="40" height="40">
                                                        @endif
                                                        <span>{{ $item->product->user->name }}</span>
                                                    </a>
                                                    @else
                                                    <span class="text-muted">No owner</span>
                                                    @endif
                                                </td>
                                                @endif

                                                <td>{{ $item->total_quantity ?? 0 }}</td>
                                                <td>${{ number_format($item->total_revenue ?? 0, 2, '.', ',') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center">No data available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            @include('admin.common.footer')
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>

    <script>
        const monthlyRevenue = @json($monthlyRevenue);
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const data = labels.map((_, i) => monthlyRevenue[i + 1] ?? 0);

    </script>

    {{-- Chart init --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('barChart').getContext('2d');

            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const monthlyRevenue = @json($monthlyRevenue);
            const data = labels.map((_, i) => monthlyRevenue[i + 1] ?? 0);

            const backgroundColors = [
                'rgba(255, 99, 132, 0.2)'
                , 'rgba(54, 162, 235, 0.2)'
                , 'rgba(255, 206, 86, 0.2)'
                , 'rgba(75, 192, 192, 0.2)'
                , 'rgba(153, 102, 255, 0.2)'
                , 'rgba(255, 159, 64, 0.2)'
                , 'rgba(199, 199, 199, 0.2)'
                , 'rgba(83, 102, 255, 0.2)'
                , 'rgba(255, 102, 255, 0.2)'
                , 'rgba(102, 255, 178, 0.2)'
                , 'rgba(255, 178, 102, 0.2)'
                , 'rgba(102, 255, 255, 0.2)'
            ];

            const borderColors = [
                'rgba(255, 99, 132, 1)'
                , 'rgba(54, 162, 235, 1)'
                , 'rgba(255, 206, 86, 1)'
                , 'rgba(75, 192, 192, 1)'
                , 'rgba(153, 102, 255, 1)'
                , 'rgba(255, 159, 64, 1)'
                , 'rgba(199, 199, 199, 1)'
                , 'rgba(83, 102, 255, 1)'
                , 'rgba(255, 102, 255, 1)'
                , 'rgba(102, 255, 178, 1)'
                , 'rgba(255, 178, 102, 1)'
                , 'rgba(102, 255, 255, 1)'
            ];

            new Chart(ctx, {
                type: 'bar'
                , data: {
                    labels: labels
                    , datasets: [{
                        label: 'Revenue ($)'
                        , data: data
                        , backgroundColor: backgroundColors
                        , borderColor: borderColors
                        , borderWidth: 2
                    }]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        legend: {
                            display: true
                        }
                    }
                    , scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

    </script>

    @include('admin.common.scripts')
</body>
</html>
