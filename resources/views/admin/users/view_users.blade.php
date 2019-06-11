@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">ผู้ใช้งานระบบ</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">หน้าแรก</a></li>

                            <li class="breadcrumb-item active" aria-current="page">รายการผู้ใช้งานระบบ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
   <div class="row">
        <div class="col-12">
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
            
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">ชื่อผู้ใช้งาน</th>
                                    <th style="text-align: center;">อีเมล์</th>
                                    <th style="text-align: center;">สิทธิ</th>
                                    <!--<th style="text-align: center;">การปฏิบัติ</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="gradeX">
                                    <td style="text-align: center;">{{ $user->name }}</td>
                                    <td style="text-align: center;">{{ $user->email }}</td>
                                    <td style="text-align: center;">{{ $user->admin }}</td>
                                    <!-- <td style="text-align: center;"><a href="{{ url('/admin/users/edit-user/'.$user->id) }}" class="btn btn-primary btn-mini"> แก้ไข</a> <a href="{{ url('/admin/users/delete-user/'.$user->id) }}" class="btn btn-danger btn-mini btn-deleteMaster">ลบ</a>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

@endsection