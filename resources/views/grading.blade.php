@extends('layouts.adminLayout.admin_design')
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">สรุปคะแนน</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">หน้าแรก</a></li>
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
        <div class="col-md-12">
            <div class="card">
                <!-- @foreach(Session::get('userRoles') as $role)
                    {{ $role->user_role_group_name }}
                @endforeach -->
                <div class="card-body">
                    <form id="frmGrading" class="form-inline" method="get" action="{{ url('grading') }}" novalidate="novalidate" >
                        {{ csrf_field() }}

                        <input type="hidden" name="isSubmit" value="1" />

                        <label for="term_id" class="text-right control-label col-form-label">ห้วงการประเมิน : <span style="color: red;">{{ $term->term_name }}</span></label>
                        &nbsp;&nbsp;&nbsp;
                        <label for="grading_group_id" class="text-right control-label col-form-label">กลุ่มการตัดเกรด : </label>
                            <div class="">
                                <select name="grading_group_id" id="grading_group_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($gradingGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $grading_group_id) selected 
                                        @endif
                                        >{{ $val->grading_group_name.' - '.$val->grading_group_desc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" > ค้นหา</button>&nbsp;

                            <!-- <a href="" name="btnGrading" id="btnGrading" class="btn btn-default" > มีรายการที่ยังไม่ยืนยัน </a>

                            <a href="" name="btnGrading" id="btnGrading" class="btn btn-primary" > ตัดเกรด </a> -->
                      </form>
                </div>
                <!--card-body-->

                <div class="card-body table-responsive">
                    <h4 class="card-title">ผู้รับการประเมิน</h4>
                    <table id="tbl_no_paging_info" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 30px;">ลำดับ</th>
                                <th style="text-align: center; width: 30px;">ภาพ</th>
                                <th style="text-align: center;">รหัส</th>
                                <th style="text-align: center;">ชื่อ นามสกุล</th>
                                <th style="text-align: center;">ตำแหน่ง</th>
                                <th style="text-align: center;">ผู้ประเมินคนที่ 1</th>
                                <th style="text-align: center;">ผู้ประเมินคนที่ 2</th>            
                                <th style="text-align: center; width: 30px;">คะแนนรวม</th>          
                                <th style="text-align: center; width: 20px;">เกรด</th>                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluates as $indexKey => $evaluate)
                            <tr class="gradeX">
                                <td style="text-align: center; width: 30px;">{{ $indexKey+1 }}</td>
                                 <td style="text-align: center; width: 30px;"><img style="width: 40px;" src="{{ asset('/assets/images/employees/small/'.$evaluate->image) }}" /></td>
                                <td class="center">{{ $evaluate->person_code }}</td>
                                <td class="center">{{ $evaluate->person_full_name }}</td>
                                <td class="center">{{ $evaluate->employee_position }}</td>
                                <td class="center">
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
                                <td class="center">
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
                                <td style="text-align: center; width: 30px;">{{ $evaluate->average_score }}</td>
                                <td style="text-align: center; width: 20px;">{{ $evaluate->calculate_grading }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form id="form2" class="form-inline" method="post" action="{{ url('grading/grading/'.$grading_group_id) }}" novalidate="novalidate" >
                        {{ csrf_field() }}
                        @if($grading_group_id!=NULL)
                        <button type="submit" class="btn btn-danger" > ตัดเกรด</button>
                        @endif 
                      </form>
                </div>

            </div>
        </div>
        <!--col-md--6-->
    </div>
    <!--/.row-->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
        
@endsection   
