@extends('layouts.crm')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ $customer->company_name }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('crm.home') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Customer
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7">
                <div class="form-group d-flex flex-wrap align-items-center justify-content-sm-end mb-sm-0 mb-1">
                    <div class="btn-group new-btn-group my-1 my-sm-0 me-1">
                        <input type="radio" class="btn-check" value="today" name="date_filter" {{ Request::get('date_filter') == 'today' ? 'checked' : '' }} id="Today" />
                        <label class="btn btn-outline-primary bg-white" for="Today">Today</label>

                        <input type="radio" class="btn-check" value="week" name="date_filter" {{ Request::get('date_filter') == 'week' ? 'checked' : '' }} id="Week" />
                        <label class="btn btn-outline-primary bg-white" for="Week">Week</label>

                        <input type="radio" class="btn-check" value="month" name="date_filter" {{ Request::get('date_filter') == 'month' ? 'checked' : '' }} id="Months1Account" />
                        <label class="btn btn-outline-primary bg-white" for="Months1Account">This Month</label>

                        <input type="radio" class="btn-check" value="ytd" name="date_filter" {{ Request::get('date_filter') == 'ytd' ? 'checked' : '' }} id="clear" />
                        <label class="btn btn-outline-primary bg-white" for="clear">YTD</label>
                    </div>
                    <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0 me-1"><i
                            data-feather="arrow-left-circle"></i> Back</button>
                    <a href="{{ route('customers.view',['customerCode' => $customer->customer_code]) }}"><button class="btn btn-primary box-shadow-2 btn-sm"><i data-feather="refresh-cw"></i> Clear</button></a>

                </div>
            </div>
        </div>

        <div class="content-body dasboardnewbody">


            <section id="chartjs-chart">

                <div class="row match-height">
                    <div class="col-xl-12 col-md-6 col-12">
                        <div class="row cutomerdardhcrminfo">

                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body statistics-body">
                                        <div class="d-flex flex-row justify-content-between">
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $salesSummary['totalSalesValue'] }}</h4>
                                                <p class="card-text mb-0">Total Sales</p>
                                            </div>
                                            <div>
                                                <div class="avatar bg-light-info">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="d-flex align-items-end justify-content-between">
                                            <h6>Since Apr-2022</h6>
                                            <p class="text-danger mb-0"><i data-feather="trending-down"></i> 47%</p>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body statistics-body">
                                        <div class="d-flex flex-row justify-content-between">
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">5</h4>
                                                <p class="card-text mb-0">Total NCR’s</p>
                                            </div>
                                            <div>
                                                <div class="avatar bg-light-primary">
                                                    <div class="avatar-content">
                                                        <i data-feather="alert-triangle" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="d-flex align-items-end justify-content-between">
                                            <h6>Since Apr-2022</h6>
                                            <p class="text-danger mb-0"><i data-feather="trending-down"></i> 77%</p>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body statistics-body">
                                        <div class="d-flex flex-row justify-content-between">
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $creditLimit }}</h4>
                                                <p class="card-text mb-0">Credits Issued</p>
                                            </div>
                                            <div>
                                                <div class="avatar bg-light-success">
                                                    <div class="avatar-content">
                                                        <i data-feather="users" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="d-flex align-items-end justify-content-between">
                                            <h6>Since Apr-2022</h6>
                                            <p class="text-danger mb-0"><i data-feather="trending-down"></i> 37%</p>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row match-height">
                    <div class="col-md-5 col-12">
                        <div class="card">
                            <div class="card-header newheader d-flex justify-content-between align-items-start">
                                <div class="header-left">
                                    <h4 class="card-title">Purchase Trends By Month</h4>
                                </div>
                            </div>
                            <div class="card-body mt-2">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <canvas class="line-chart-ex5" style="height: 300px"></canvas>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-header newheader d-flex justify-content-between align-items-start">
                                <div class="header-left">
                                    <h4 class="card-title">Supply Split</h4>
                                </div>
                            </div>
                            <div class="card-body mt-2">
                                <div class="row align-items-center">
                                    <div class="col-md-12 mb-1">
                                        <div id="donut-chart-customer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-md-3">
                        <div class="card overtimechart">
                            <div class="card-header newheader d-flex justify-content-between align-items-start">
                                <div class="header-left">
                                    <h4 class="card-title">Latest NCRs</h4>
                                </div>
                                <div class="dropdown d-flex align-items-center">
                                    <p class="mb-0 text-end font-small-2 me-1"><a class="mb-0 text-primary"
                                            href="{{ route('notes.index') }}"><i data-feather='eye'></i> View All</a></p>
                                </div>
                            </div>
                            <div class="card-body bg-light">
                                <div class="actual-databudgetinfo shadow-none mt-1 rounded-0"
                                    style="max-height: 330px; overflow-y: scroll;">
                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">7/7/24</h5>
                                                <p class="card-text">Sealy NSW</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">2/7/24</h5>
                                                <p class="card-text">Dry Seals</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">7/7/24</h5>
                                                <p class="card-text">Sealy NSW</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">2/7/24</h5>
                                                <p class="card-text">Dry Seals</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">7/7/24</h5>
                                                <p class="card-text">Sealy NSW</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">2/7/24</h5>
                                                <p class="card-text">Dry Seals</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">7/7/24</h5>
                                                <p class="card-text">Sealy NSW</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-75">

                                        <div class="card-body mail-message-wrapper p-1">
                                            <div class="mail-message">
                                                <h5 class="card-text fw-bolder">2/7/24</h5>
                                                <p class="card-text">Dry Seals</p>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="card overtimechart">
                            <div class="card-header newheader d-flex justify-content-between align-items-start">
                                <div class="header-left">
                                    <h4 class="card-title">Contact Log</h4>
                                </div>
                                <div class="dropdown d-flex align-items-center">
                                    <p class="mb-0 text-end font-small-2 me-1"><a class="mb-0 text-primary"
                                            href="{{ route('notes.index') }}"><i data-feather='eye'></i> View All</a></p>
                                    <a href="{{ route('notes.create') }}">
                                        <div class="newcolortheme cursor-pointer">
                                            <i data-feather='file-text' class="me-25"></i> Add Notes
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body bg-light">
                                <div class="actual-databudgetinfo shadow-none mt-1 rounded-0"
                                    style="max-height: 270px; overflow-y: scroll;">
                                    @forelse ($diaries as $diary)
                                        <div class="card mb-75">
                                            <div class="card-body mail-message-wrapper p-1">
                                                <div class="mail-message">
                                                    <h5 class="card-text fw-bolder">{{ $diary->subject }}</h5>
                                                    <p class="card-text">{!! $diary->description !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @include('crm.static.need-to-know')
                    </div>

                </div>
            </section>
            <!-- ChartJS section end -->

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection
@section('scripts')
<script src="{{ asset('/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('/app-assets/vendors/js/charts/chart.min.js') }}"></script>
<script src="{{ asset('/app-assets/js/scripts/charts/crm-customer-charts.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('input[name="date_filter"]');

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                const selectedValue = this.value;
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('date_filter', selectedValue);
                window.location.href = currentUrl;
            });
        });
    });

    var prospectsLabels = Object.values(@json($prospectsData['prospectsGraphData']['labels']));
    var prospectsData = Object.values(@json($prospectsData['prospectsGraphData']['data']));

    var top5CustomersData = @json($top5CustomersData['topProspectsSplitByIndustry']);
    var top5CustomerSalesPrc = top5CustomersData.map(item => item.sales_percentage);
    var top5CustomerCategories = top5CustomersData.map(item => item.industry);
    var top5CustomerColorCode = top5CustomersData.map(item => item.color_code);

</script>
@endsection