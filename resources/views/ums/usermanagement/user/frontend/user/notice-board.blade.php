@extends('frontend.layouts.user')
@section('content')

        <div class="sdb-tabl-com sdb-pro-table">
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
                @foreach($notifications as $notification)
                <tr>
                
                <td>{{$notification['notification_description']}}</td>
                <td>{{$notification['notification_start']}}</td>
                <td>{{$notification['notification_end']}}</td>
                </tr>
                @endforeach
            
            </tbody>
            </table>
      </div>
   </div>
@endsection