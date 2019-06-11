@extends('layouts.adminLayout.admin_design')
@section('content')



    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">หน้าแรก</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
                            <!-- <li class="breadcrumb-item active" aria-current="page">Library</li> -->
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
        </div><!--col-md-12-->
    </div><!--row-->
   <div class="row">        
        <div class="col-md-3">
            <div class="card" style="text-align: center;">
                <div class="card-body center">
                    <!-- <h4 class="card-title">Update Password</h4>

                    <div class="form-group row">
                        <label for="current_pwd" class="col-sm-3 text-right control-label col-form-label">Current Password</label>
                        <div class="col-sm-3">
                            <input type="password" name="current_pwd" id="current_pwd"  class="form-control"placeholder="Current Password Here" autocomplete="off" required value="" >
                            <span id="chkPwd"></span>
                        </div>
                    </div> -->
                    <img style="width: 150px;" src="{{ asset('/assets/images/employees/medium/'.$employeeDetails->image) }}" />
                    <h5 style="font-weight: bold;">{{ $employeeDetails->person_full_name }}</h5>
                    <span>{{ $employeeDetails->position_name }}</span></br>
                    @if($employeeDetails->position_rank_id > 5)
                        <button class="btn btn-primary">ประเมินตนเอง</button>
                    @endif 
                </div><!--card-body-->
            </div>
        </div><!--col-md--6-->

        <div class="col-md-9">
            <div class="card">
                <!-- @foreach(Session::get('userRoles') as $role)
                    {{ $role->user_role_group_name }}
                @endforeach -->
                <div class="card-body table-responsive">
                    <h4 class="card-title">ผู้ถูกประเมิน [views/index.blade]</h4>
                    <table id="table_no_paging" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: center;">ลำดับ</th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;">ชื่อ นามสกุล</th>
                                <th style="text-align: center;">ตำแหน่ง</th>
                                <th style="text-align: center;">สถานะ</th>
                                <th style="text-align: center;">การปฏิบัติ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluates as $indexKey => $evaluate)
                            <tr class="gradeX">
                                <td style="text-align: center;">{{ $indexKey+1 }}</td>
                                 <td style="text-align: center;"><img style="width: 40px;" src="{{ asset('/assets/images/employees/small/'.($evaluate->image!=NULL?$evaluate->image:'default.jpg') ) }}" /></td>
                                <td style="text-align: center;">{{ $evaluate->person_full_name }}</td>
                                <td style="text-align: center;">{{ $evaluate->employee_position }}</td>
                                <td style="text-align: center;">
                                    @if ($evaluate->evaluator1_id == $employeeDetails->id )
                                        @if ($evaluate->evaluator1_status == 2)
                                            <span class="badge badge-pill badge-success">ยืนยันผลประเมินแล้ว</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">ยังไม่ยืนยันผลประเมิน</span>
                                        @endif 
                                    @endif 
                                    @if ($evaluate->evaluator2_id == $employeeDetails->id )
                                        @if ($evaluate->evaluator2_status == 2)
                                            <span class="badge badge-pill badge-success">ยืนยันผลประเมินแล้ว</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">ยังไม่ยืนยันผลประเมิน</span>
                                        @endif 
                                    @endif 
                                </td>
                                <td style="text-align: center;">
                                    <a target="_blank" href="{{ url('/evaluates/edit-evaluate/'.$evaluate->id.'/1/'.$employeeDetails->id) }}" class="btn btn-primary btn-mini">ประเมิน</a>&nbsp; 

                                    <a target="_blank" href="{{ url('/evaluates/view-evaluate/'.$evaluate->id.'/1/'.$employeeDetails->id) }}" class="btn btn-primary btn-mini">ดูสรุปผล</a> 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div><!--col-md--6-->
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->



        
@endsection   
