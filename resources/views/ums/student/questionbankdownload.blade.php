@extends('ums.student.student-meta')
@section('content')

    <head>



        <style>
            #example1 {
                /* border: 2px solid black; */
                padding: 25px;

                color: white;

                font-weight: 300 !important;
            }

            td {
                font-size: 12px !important;

            }

            .circular--square {
                border-radius: 50%;
                height: 111px;
                width: 95px;
            }

            .auto-style2 {
                font-size: large;
            }

            .auto-style3 {
                width: 256px;
            }

            .auto-style4 {
                width: 207px;
            }

            .auto-style5 {
                width: 13px;
            }

            .auto-style6 {
                width: 268px;
            }

            .auto-style7 {
                width: 23px;
            }

            .auto-style10 {
                text-align: left;
            }

            .auto-style11 {
                font-size: x-large;
                font-weight: normal;
                color: #000000;
            }

            .auto-style12 {
                color: #000000;
            }

            .auto-style13 {
                width: 256px;
                font-weight: bold;
            }

            .auto-style14 {
                color: #3333CC;
                font-weight: bold;
            }

            .auto-style16 {
                width: 178px;
            }

            .auto-style17 {
                width: 309px;
            }

            .auto-style18 {
                color: #FF3300;
            }

            .auto-style20 {
                color: #FF0000;
                font-weight: bold;
            }

            .auto-style21 {
                width: 211px;
            }
        </style>
    </head>

    <form id="form1">
        <div id="example1">

            <div class="container" style="margin-top:1px">
                <center>

                    <table id="tblHeader" style="width:1000px;">

                    </table>
                    <hr>
                    <br>
                    <h1 style="font-family:'Times New Roman'" class="auto-style14">Question Banks</h1>
                </center>
                <hr>

                <br />
                <!-- <div class="row">
                
             
                        
                <div class="col-sm-5"></div>
                <div class="col-sm-2">
                  <input type="submit" value="PRINT" onclick="print()" class="btn btn-success">
                </div>
                <div class="col-sm-5"></div>
              </div>
             <br><br>-->

                <!--notification table code-->
                <div class="row">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered admintable border-0" cellspacing="0"
                            cellpadding="0">
                            <thead>
                                <th>Sr. No.</th>
                                <th style="width: 200px;">Campus Name</th>
                                <th>Program Name</th>
                                <th>Course Name</th>
                                <th>PHD Title</th>
                                <th>Semester Name</th>
                                <th>Paper Name</th>
                                <th>Session</th>
                                <th>Question Bank</th>
                                <th>Synopsis</th>
                                <th>Thesis</th>
                                <th>Journal Paper</th>
                                <th>Seminar</th>
                                <th>Action</th>
                                @if (Auth::guard('admin')->check())
                                @endif
                            </thead>
                            @if (count($quesBankData) > 0)
                                @php $i = 1; @endphp
                                @foreach ($quesBankData as $key => $internal)
                                    <tbody>
                                        <tr>
                                            <td>#{{ $i++ }}</td>
                                            <td>{{ $internal->Campuse->name }}</td>
                                            <td>{{ $internal->Category->name }}</td>
                                            <td>{{ $internal->Course->name }}</td>
                                            <td>{{ $internal->phd_title ? $internal->phd_title : 'N/A' }}</td>
                                            <td>{{ $internal->Semester->name }}</td>
                                            <td>{{ $internal->subject->name ?? '' }}</td>
                                            <td>{{ $internal->session }}</td>
                                            <td><a href="{{ $internal->question_bank_file }}" target="_blank">Download</a>
                                            </td>
                                            <td><a href="{{ $internal->synopsis_file }}" target="_blank">Download</a></td>
                                            <td><a href="{{ $internal->thysis_file }}" target="_blank">Download</a></td>
                                            <td><a href="{{ $internal->journal_paper_file }}" target="_blank">Download</a>
                                            </td>
                                            <td><a href="{{ $internal->seminar_file }}" target="_blank">Download</a></td>
                                            {{-- @if (Auth::guard('admin')->check()) --}}
                                            <td>
                                                <a href="{{ route('edit-question-bank', [$internal->id]) }}"
                                                    class="btn-sm btn-success "><i class="iconly-boldPlus"></i> Edit</a>
                                                <br />
                                                <br />
                                                <a href="{{ route('delete-question-bank', [$internal->id]) }}"
                                                    class="btn-sm btn-danger" onClick="return confirm('Are you sure?');"><i
                                                        class="iconly-boldPlus"></i> Delete</a>
                                            </td>
                                            {{-- @endif --}}
                                        </tr>
                                    </tbody>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center"><strong>NO DATA FOUND</strong></td>
                                </tr>
                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

            @endsection
