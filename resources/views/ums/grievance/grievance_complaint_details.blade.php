 @extends('ums.admin.admin-meta')
  @section('content')
      
  
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed"> --}}


    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
        <div class="col-md-8 bg-white p-4 shadow">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    About DSMNRU Grievance Redressal And Management
                </div>
                <div class="panel-body about-gcr">
                    <form method="" action="" id="your-form">
                        <input type="hidden" name="_token" value="SoHZ3FIbVl9Ifk8zva5Q1T3L49OqN5IOTCPmcIIE">
                        <div class="tab1">
                            <div class="chat-container">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>(Student) &nbsp;&nbsp; 2023-02-13 13:17:33</td>
                                        </tr>
                                        <tr>
                                            <td>(Admin) &nbsp;&nbsp; 2025-01-15 12:35:20</td>
                                        </tr>
                                        <tr>
                                            <td>(Admin) &nbsp;&nbsp; 2025-01-15 21:38:04</td>
                                        </tr>
                                        <tr>
                                            <td>(Admin) &nbsp;&nbsp; 2025-01-15 21:38:13</td>
                                        </tr>
                                        <tr>
                                            <td>sd (Admin) &nbsp;&nbsp; 2025-01-16 10:58:52</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <div class="row">
                                    <div class="col-md-7">
                                        <input name="complaint" id="message-input" class="form-control" rows="2">
                                    </div>
                                    <div class="col-md-5">
                                        <select name="status" class="form-control" style="height: 55px;">
                                            <option value="0" selected>Pending</option>
                                            <option value="1">Under Process</option>
                                            <option value="2">Closed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 text-center">
                                        <br>
                                        <button type="submit" class="btn btn-success btn-lg">Save</button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
