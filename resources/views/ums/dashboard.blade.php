 @extends('ums.dashboard.dashboard-meta')
    <!-- BEGIN: Content-->
    @section('content')
        
   
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-4 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Dashboard Analytics</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a>
                                    </li> 
                                    <li class="breadcrumb-item active">Dashboard
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-end col-md-6 col-8">
                    <div class="form-group breadcrumb-right">  
                        <div class="col-md-3 form-group">
                            <form action="" id="setSession">
                                <select name="session" class="form-control" onchange="$('#setSession').submit();">
                                    <option value="2024-2025" @if($session=='2024-2025') selected @endif>2024-2025</option>
                                    <option value="2023-2024" @if($session=='2023-2024') selected @endif>2023-2024</option>
                                    <option value="2022-2023" @if($session=='2022-2023') selected @endif>2022-2023</option>
                                </select>
                            </form>
                        </div>   
                    </div>
                  
                </div>
            </div>
             
            <div class="content-body dasboardnewbody">
                 
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
                        
                        <div class="col-md-4 col-12 cursor-pointer"  data-bs-toggle="modal" data-bs-target="#myModal">
                            <div class="holiday-box p-4" style=" border-left: 10px solid #A0BC8B;">
                                <div><span style="background: rgba(160, 188, 139, 0.2); color: #A0BC8B;">Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$all_application_data->sum('total_applications')}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-4 col-12  cursor-pointer" data-bs-toggle="modal" data-bs-target="#myModal_phd">
                            <div class="holiday-box p-4" style="border-left: 10px solid #62C3C0;">
                                <div><span style="background: rgba(110, 230, 226, 0.2); color: #62C3C0;">P.hD Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$all_application_data_phd->sum('total_applications')}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer" >
                            <div class="holiday-box p-4">
                                <div><span style="background: rgba(168, 139, 151, 0.2); color: #A88B97;">Enrolled Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$enrollment_count}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer">
                            <div class="holiday-box p-4" style="border-left: 10px solid #E3C852;">
                                <div><span style="background: rgba(227, 200, 82, 0.2); color: #E3C852;">Total Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$total_exam_count}}</h3>
                                    <h5>Total</h5>
                                   
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer">
                            <div class="holiday-box p-4"style="border-left: 10px solid #eb6623;" >
                                <div><span style="background: rgba(190, 136, 55, 0.2); color: #eb6623;" >Pending
                                    Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{count($pending)}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header newheader d-flex justify-content-between align-items-start">
                                    <div class="header-left">
                                        <h4 class="card-title">Monthly</h4>
                                        <p class="card-text">Applications Submitted</p>
                                    </div> 
                                    <div class="header-right d-flex align-items-center mb-25">
                                        
                                    </div>
                                </div> 
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <canvas class="leavebar-chart-ex chartjs" data-height="300"></canvas>
                                        </div>
                                        <div class="col-md-4">
                                           
                                               
                                     <div id="donut-opentask"></div>
                                                
                                            
                                            
                                        </div>
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                         
					</div> 
                </section>
                <!-- ChartJS section end -->

            </div>
        </div>
    </div>
  <!-- Modal HTML Structure -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="shiftModalLabel">Total Applications (2024-2025)</h3>
                <button type="button" class="btn-close m-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Table for DataTables -->
                <section id="basic-datatable p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-striped datatables-basic" id="myTable">
                                        <thead>
                                            <tr>
                                              <th>SN#</th>
                                              <th>Campuses Name</th>
                                              <th>Courses Name</th>
                                              <th>Application's Payments Failed</th>
                                              <th>Application's Payments Pending</th>
                                              <th>Application's Payments Paid</th>
                                              <th>Total Applications</th>
                                            </tr>
                                          </thead>
                                          <tbody class="data-hide" id="datahide">
                                            @foreach($all_application_data as $index=>$application_data)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$application_data->campus->name}}</td>
                                                <td>{{$application_data->course->name}}</td>
                                                <td>{{$application_data->payment_failed}}</td>
                                                <td>{{$application_data->payment_pending}}</td>
                                                <td>{{$application_data->payment_paid}}</td>
                                                <td>{{$application_data->total_applications}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <th>{{++$index}}</th>
                                                <th></th>
                                                <th>Total</th>
                                                <th>{{$all_application_data->sum('payment_failed')}}</th>
                                                <th>{{$all_application_data->sum('payment_pending')}}</th>
                                                <th>{{$all_application_data->sum('payment_paid')}}</th>
                                                <th>{{$all_application_data->sum('total_applications')}}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal_phd" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="shiftModalLabel">Total Applications (2024-2025)</h3>
                <button type="button" class="btn-close m-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Table for DataTables -->
                <section id="basic-datatable p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-striped datatables-basic" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Campuses Name</th>
                                                <th>Courses Name</th>
                                                <th>Application's Payments Failed</th>
                                                <th>Application's Payments Pending</th>
                                                <th>Application's Payments Paid</th>
                                                <th>Total Applications</th>
                                            </tr>
                                          </thead>
                                          <tbody class="data-hide" id="datahide">
                                            @foreach($all_application_data_phd as $index=>$application_data)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$application_data->campus->name}}</td>
                                                <td>{{$application_data->course->name}}</td>
                                                <td>{{$application_data->payment_failed}}</td>
                                                <td>{{$application_data->payment_pending}}</td>
                                                <td>{{$application_data->payment_paid}}</td>
                                                <td>{{$application_data->total_applications}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <th>{{++$index}}</th>
                                                <th></th>
                                                <th>Total</th>
                                                <th>{{$all_application_data_phd->sum('payment_failed')}}</th>
                                                <th>{{$all_application_data_phd->sum('payment_pending')}}</th>
                                                <th>{{$all_application_data_phd->sum('payment_paid')}}</th>
                                                <th>{{$all_application_data_phd->sum('total_applications')}}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Scroll to Top Button (Optional) -->
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
@endsection

<script>
  
    Highcharts.chart('salechart', {
        chart: {
            type: 'column'
        },
        title: {
            text: ' '
        },
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Applications'
            }
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                borderRadius: 8,
                pointPadding: .3
            },
        },

        series: [{
            name: 'Rejected',
            data: [
                @foreach($month_pending ?? [] as $month)
                    {{ count($month_pending[$month]) }},
                @endforeach
            ],
            color: '#C9D1D8'
        }, {
            name: 'Approved',
            data: [
                @foreach($month_approve ?? [] as $month) 
                    {{ count($month_approve[$month]) }},
                @endforeach
            ],
            color: '#63C276'
        }, {
            name: 'Received',
            data: [
                @foreach($month_enrolled ?? [] as $month) 
                    {{ count($month_enrolled[$month]) }},
                @endforeach
            ],
            color: '#25DAE6'
        }]
    });

    Highcharts.chart('donatchart', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: false,
                alpha: 0
            },

        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        exporting: {
            enabled: false
        },
        colors: ['#1ea0a1', '#f4a335'],
        plotOptions: {
            pie: {
                innerSize: 160,
                depth: 5,
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        credits: {
            enabled: false
        },
        legend: {
            enabled: true,
            verticalAlign: 'bottom',
            align: 'center',
            y: 10
        },

        series: [{
            name: 'Applications',
            data: [
                ['Received', {{ $application_count ?? 0 }}],
                ['Converted', {{ $enrollment_count ?? 0 }}]
            ]
        }]
    });
</script>

</script>
{{-- @include('footer') --}}

