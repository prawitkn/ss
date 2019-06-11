@extends('layouts.adminLayout.admin_design')
@section('content')



    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Dashboard</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    @if(Session::has('flash_message_error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{!! session('flash_message_error') !!}</strong>
        </div>
    @endif
    
    @if(Session::has('flash_message_success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{!! session('flash_message_success') !!}</strong>
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="form1" class="form-horizontal" method="post" action="{{ url('/admin/update-pwd') }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <h4 class="card-title">Update Password</h4>
                        <div class="form-group row">
                            <label for="current_pwd" class="col-sm-3 text-right control-label col-form-label">Current Password</label>
                            <div class="col-sm-3">
                                <input type="password" name="current_pwd" id="current_pwd"  class="form-control"placeholder="Current Password Here" autocomplete="off" required value="" >
                                <span id="chkPwd"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_pwd" class="col-sm-3 text-right control-label col-form-label">New Password</label>
                            <div class="col-sm-3">
                                <input type="password" name="new_pwd" id="new_pwd" class="form-control" autocomplete="off" placeholder="New Password Here" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirm_pwd" class="col-sm-3 text-right control-label col-form-label">Confirm New Password</label>
                            <div class="col-sm-3">
                                <input type="password" name="confirm_pwd" id="confirm_pwd" class="form-control" autocomplete="off"  placeholder="Confirm New Password Here" required>
                            </div>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->



        
@endsection   
