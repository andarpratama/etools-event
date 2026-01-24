<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    {{-- <link href="" rel="stylesheet" type="text/css"> --}}
    <link href="{{ asset('admin-view/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    {{-- <link href="css/sb-admin-2.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('admin-view/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .btn-group .btn {
            border-radius: 0;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 0.35rem;
            border-bottom-left-radius: 0.35rem;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 0.35rem;
            border-bottom-right-radius: 0.35rem;
        }
        .btn-group .btn.active {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
        }
        .gap-2 {
            gap: 0.5rem;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('admin-view.partials.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('admin-view.partials.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group" role="group" aria-label="Period filter">
                                <button type="button" class="btn btn-sm btn-outline-primary period-btn active" data-period="daily">Daily</button>
                                <button type="button" class="btn btn-sm btn-outline-primary period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary period-btn" data-period="monthly">Monthly</button>
                            </div>
                            <select id="days-select" class="form-control form-control-sm" style="width: auto;">
                                <option value="7">Last 7 days</option>
                                <option value="30" selected>Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="180">Last 6 months</option>
                                <option value="365">Last year</option>
                            </select>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Views</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-views">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Today Views</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="today-views">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Average Daily</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="avg-daily">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Peak Day</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="peak-day">-</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Card -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary" id="chart-title">Daily Page Views</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="pageViewsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('admin-view.partials.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('admin-view.partials.logout-modal')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin-view/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-view/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin-view/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin-view/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        let pageViewsChart;
        let currentPeriod = 'daily';
        let currentDays = 30;

        function loadAnalytics(period = 'daily', days = 30) {
            fetch(`{{ route('admin.analytics.daily') }}?period=${period}&days=${days}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-views').textContent = data.total.toLocaleString();
                document.getElementById('today-views').textContent = data.today.toLocaleString();
                
                const avg = data.data.length > 0 ? Math.round(data.data.reduce((a, b) => a + b, 0) / data.data.length) : 0;
                document.getElementById('avg-daily').textContent = avg.toLocaleString();
                
                const maxViews = Math.max(...data.data);
                const maxIndex = data.data.indexOf(maxViews);
                document.getElementById('peak-day').textContent = maxViews > 0 ? data.labels[maxIndex] : '-';

                // Update chart title
                const periodLabels = {
                    'daily': 'Daily',
                    'weekly': 'Weekly',
                    'monthly': 'Monthly'
                };
                document.getElementById('chart-title').textContent = periodLabels[data.period || period] + ' Page Views';

                if (pageViewsChart) {
                    pageViewsChart.destroy();
                }

                const ctx = document.getElementById('pageViewsChart').getContext('2d');
                pageViewsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Page Views',
                            data: data.data,
                            backgroundColor: 'rgba(78, 115, 223, 0.5)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Views: ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading analytics:', error);
            });
        }

        $(document).ready(function() {
            loadAnalytics(currentPeriod, currentDays);

            // Period button handlers
            $('.period-btn').on('click', function() {
                $('.period-btn').removeClass('active');
                $(this).addClass('active');
                currentPeriod = $(this).data('period');
                loadAnalytics(currentPeriod, currentDays);
            });

            // Days select handler
            $('#days-select').on('change', function() {
                currentDays = $(this).val();
                loadAnalytics(currentPeriod, currentDays);
            });
        });
    </script>

</body>

</html>