@extends('ums.student.layouts.app1')
@section('content')

<link href="/assets/frontend/css/result.css" rel="stylesheet">
<link href="/assets/frontend/css/result-style.css" rel="stylesheet">

<style>
#example1 {
  border: 2px solid black;
  padding: 25px;
  background: url('assets/viewresult.jpg');
  background-repeat: repeat;
  background-size: auto;
  color:white;
  font-weight: 300 !important;
}

td, th {
    font-size: 12px !important;
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th {
    background-color: #3333CC;
    color: white;
    font-weight: bold;
}

table td {
    background-color: #f2f2f2;
    color: black;
}

.table-dark {
    background-color: #333;
    color: white;
}

.table, th, td {
    border-radius: 5px;
}
</style>

<body>
    <form id="form1">
    <div id="example1">

         <div class="container" style="margin-top:1px">
        <center>
        
         <table id="tblHeader" style="width:1000px;">
            <tbody>
                <tr>
                     <td>
                         <img src="\assets\frontend\images\icon.png" class="circular--square" width="80" height="80">
                     </td>
                    <td style="font-family:'Times New Roman'">
                        <center><h1 class="auto-style14">Dr Shakuntala Misra National Rehabilitation University</h1></center>
                    </td>
                      <td>
                    <img src="\assets\frontend\images\icon.png" class="circular--square" width="100" height="100">
                      </td>
                 </tr>
             </tbody>
         </table>
            <hr>
             <br>
      </center>
             <br />
              <div class="row">
            
                   <div class="col-sm-5"></div>
                  <div class="col-sm-2">
                      <input type="submit" value="PRINT" onclick="print()" class="btn btn-success">
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <br><br>
                
                <!-- Notification table code -->
                <div class="row">
                    <center><h1 style="font-family:'Times New Roman'" class="auto-style14">Notification-Board</h1></center><br><br>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Notification Description</th>
                                <th scope="col">Notification Started</th>
                                <th scope="col">Notification Ended</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Uncomment this line for debugging --}}
                            {{-- {{ dd($notifications) }} --}}
                            @foreach($notifications as $notification)
                            <tr>
                                <td>{{$notification->notification_description}}</td>
                                <td>{{$notification->notification_start}}</td>
                                <td>{{$notification->notification_end}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
    </div>
    </form>
</body>

@endsection
