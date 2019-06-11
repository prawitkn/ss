@extends('layouts.adminLayout.admin_design')
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">รายงาน</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item "><a href="#">หน้าแรก</a></li>
                            <li class="breadcrumb-item active"><a href="#">รายงาน</a></li>
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
        <div class="col-md-12">
            <div class="card">
                <!-- @foreach(Session::get('userRoles') as $role)
                    {{ $role->user_role_group_name }}
                @endforeach -->
                <div class="card-body">
                    <form id="form1" class="form-inline" method="get" action="{{ url('/admin/index') }}" novalidate="novalidate" >

                        <div class="row">
                            <div class="form-group col-sm-2">
                            <label for="term_id" class="text-right control-label col-form-label">ห้วงการประเมิน : </label>
                            <span style="color: blue;">{{ $currentTermDetails->term_name }}</span>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-2">
                                <label for="grading_group_id" class="text-right control-label col-form-label"> กลุ่มการตัดเกรด : </label>
                                <select name="grading_group_id" id="grading_group_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($gradingGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $grading_group_id) selected 
                                        @endif
                                        >{{ $val->grading_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-2">
                                <label for="position_rank_id" class="text-right control-label col-form-label"> ระดับตำแหน่ง : </label>
                                <select name="position_rank_id" id="position_rank_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($positionRanks as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $position_rank_id) selected 
                                        @endif
                                        >{{ $val->position_rank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-2">
                                <label for="department_id" class="text-right control-label col-form-label"> ฝ่าย : </label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($departments as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $department_id) selected 
                                        @endif
                                        >{{ $val->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-2">
                                <label for="section_name" class="text-right control-label col-form-label"> ส่วน : </label>
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($sections as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $section_id) selected 
                                        @endif
                                        >{{ $val->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-1">
                                <label for="submit" class="text-right control-label col-form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <button type="submit" class="btn btn-primary" > ค้นหา</button>
                            </div>
                            <!--/.col-->

                            <div class="form-group col-sm-1">
                                
                            </div>
                            <!--/.col-->
                        </div>
                        <!--/.row-->
                      </form>
                </div>
                <!--card-body-->

                <div class="card-body table-responsive">
                    <h4 class="card-title">ผู้รับการประเมิน [views/admin.blade]</h4>
                    <table id="tbl_no_searching_paging_info" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 50px;">ลำดับ</th>
                                <th style="text-align: center; width: 50px;">ภาพ</th>
                                <th style="text-align: center; width: 50px;">รหัส</th>
                                <th>ชื่อ นามสกุล</th>
                                <th>ตำแหน่ง</th>
                                <th style="text-align: center; width: 80px;">ผู้ประเมินคนที่ 1</th>
                                <th style="text-align: center; width: 80px;">ผู้ประเมินคนที่ 2</th>     
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluates as $indexKey => $evaluate)
                            <tr class="gradeX">
                                <td style="text-align: center; width: 50px;">{{ $indexKey+1 }}</td>
                                 <td style="text-align: center; width: 50px;"><img style="width: 40px;" src="{{ asset('/assets/images/employees/small/'.$evaluate->image) }}" /></td>
                                <td style="text-align: center; width: 50px;">{{ $evaluate->person_code }}</td>
                                <td>{{ $evaluate->person_full_name }}</td>
                                <td>{{ $evaluate->employee_position }}</td>
                                <td style="text-align: center; width: 80px;">
                                    @if($evaluate->evaluator1_id != NULL)
                                        @switch($evaluate->evaluator1_status)
                                            @case(2)
                                                <a target="_blank" href="{{ url('/evaluates/view-evaluate/'.$evaluate->id.'/1/'.$evaluate->evaluator1_id) }}" class="btn btn-success btn-mini" >{{ $evaluate->evaluator1_person_full_name }}</a> 
                                                @break
                                            @default
                                                <a target="_blank" href="{{ url('/evaluates/view-evaluate/'.$evaluate->id.'/1/'.$evaluate->evaluator1_id) }}" class="btn btn-danger btn-mini" >{{ $evaluate->evaluator1_person_full_name }}</a> 
                                        @endswitch
                                    @endif
                                </td>
                                <td style="text-align: center; width: 80px;">
                                    @if($evaluate->evaluator2_id != NULL)
                                        @switch($evaluate->evaluator2_status)
                                            @case(2)
                                                <a target="_blank" href="{{ url('/evaluates/view-evaluate/'.$evaluate->id.'/1/'.$evaluate->evaluator2_id) }}" class="btn btn-success btn-mini" >{{ $evaluate->evaluator2_person_full_name }}</a> 
                                                @break
                                            @default
                                                <a target="_blank" href="{{ url('/evaluates/view-evaluate/'.$evaluate->id.'/1/'.$evaluate->evaluator2_id) }}" class="btn btn-danger btn-mini" >{{ $evaluate->evaluator2_person_full_name }}</a> 
                                        @endswitch
                                    @endif
                                </td>
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
