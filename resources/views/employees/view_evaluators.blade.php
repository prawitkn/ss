@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">ตั้งค่ากลุ่มการประเมิน/ผู้ประเมิน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page">ตั้งค่ากลุ่มการประเมิน/ผู้ประเมิน</li>
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
                    <form id="form1" class="form-inline" method="get" action="{{ url('/employees/view-evaluators') }}" novalidate="novalidate" >{{ csrf_field() }}

                    <div class="row">
                        <div class="form-group col-sm-2">
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
                        </div>
                        <!--/.col-->

                        <div class="form-group col-sm-2">
                        <label for="position_rank_id" class="text-right control-label col-form-label">ระดับตำแหน่ง : </label>
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
                        <label for="section_name" class="text-right control-label col-form-label">ส่วน : </label>
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

                        <div class="form-group col-sm-2">
                        <label for="section_name" class="text-right control-label col-form-label">   ฝ่าย : </label>
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

                        <div class="form-group col-sm-1">
                            <label for="submit" class="text-right control-label col-form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <button type="submit" class="form-control btn btn-primary"> ค้นหา</button>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    </form>
                </div>
                <!--card-body-->

                <div class="card-body">
                    <form id="form2" class="form-inline" method="post" action="{{ url('/employees/edit-evaluators') }}" novalidate="novalidate" >{{ csrf_field() }}

                    <div class="table-responsive">
                        <table id="tbl_no_paging_info" class="table table-striped table-bordered" style="width: 100%" >
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5px;">รหัส</th>
                                    <th style="text-align: center; width: 5px;">ภาพ</th>
                                    <th style="text-align: center; width: 50px;">ชื่อ นามสกุล</th>
                                    <th style="text-align: center; width: 50px;">ตำแหน่ง</th>
                                    <th style="text-align: center; width: 50px;">กลุ่มการตัดเกรด<br>
                                    <select name="grading_group_id" id="grading_group_id_hd" class="form-control" style="width: 150px;">
                                            <option value=""></option>
                                            @foreach($gradingGroups as $val)
                                            <option value="{{ $val->id }}"
                                                >{{ $val->grading_group_name.' - '.$val->grading_group_desc }}</option>
                                            @endforeach
                                        </select>  </th>                                   
                                    </th>
                                    <th style="text-align: center; width: 50px;">ผู้ประเมินคนที่1
                                        <div class="col-sm-3" id="searchEvaluator01" name="searchEvaluator" >
                                            <input type="hidden" name="employee_id" value=@if(!empty($employeeDetails)) 
                                                "{{ $employeeDetails->id }}"
                                            @else
                                                "" 
                                            @endif
                                            />

                                            <a href="#" name="searchEvaluatorCode" data-position-rank-id="10" class="btn btn-outline-primary">
                                            @if(!empty($employeeDetails)) 
                                                {{ $employeeDetails->person_full_name }}
                                            @else
                                                ค้นหา
                                            @endif 
                                            </a>
                                            <a href="" name="searchEvaluatorCodeRemove" class="btn btn-outline-primary"> ลบ</a>
                                        </div>                                        
                                    </th>
                                    <th style="text-align: center; width: 50px;">ผู้ประเมินคนที่2
                                        <div class="col-sm-3" id="searchEvaluator02" name="searchEvaluator" >
                                            <input type="hidden" name="employee_id" value=@if(!empty($employeeDetails)) 
                                                "{{ $employeeDetails->id }}"
                                            @else
                                                "" 
                                            @endif
                                            />

                                            <a href="#" name="searchEvaluatorCode" data-position-rank-id="10" class="btn btn-outline-primary">
                                            @if(!empty($employeeDetails)) 
                                                {{ $employeeDetails->person_full_name }}
                                            @else
                                                ค้นหา
                                            @endif 
                                            </a>
                                            <a href="" name="searchEvaluatorCodeRemove" class="btn btn-outline-primary"> ลบ</a>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr class="gradeX">
                                    <td style="text-align: center; width: 5px;">{{ $employee->person_code }}</td>
                                    <td style="text-align: center; width: 5px;"><img style="width: 40px;" src="{{ asset('/assets/images/employees/small/'.$employee->image) }}" /></td>
                                    <td style="text-align: center; width: 50px;">{{ $employee->person_full_name }}</td>
                                    <td style="text-align: center; width: 50px;">{{ $employee->position_name }}</td>
                                    <td style="text-align: center; width: 50px;">
                                        <input type="hidden" name="ids[]" value="{{ $employee->id }}" />

                                        <select name="grading_group_id[]" id="grading_group_id" class="form-control" style="width: 150px;">
                                            <option value=""></option>
                                            @foreach($gradingGroups as $val)
                                            <option value="{{ $val->id }}"
                                                @if($val->id == $employee->grading_group_id) selected 
                                                @endif
                                                >{{ $val->grading_group_name.' - '.$val->grading_group_desc }}</option>
                                            @endforeach
                                        </select>  
                                    </td>
                                    <td style="text-align: center; width: 50px;">
                                         <div class="col-sm-3" id="searchEvaluator1{{ $employee->id }}" name="searchEvaluator" >
                                            <input type="hidden" name="evaluator1_id[]" value=@if(!empty($employee->evaluator1_id)) 
                                                "{{ $employee->evaluator1_id }}"
                                            @else
                                                "" 
                                            @endif
                                            />

                                            <a href="#" name="searchEvaluatorCode" data-position-rank-id="{{ $employee->position_rank_id }}" class="btn btn-outline-primary">
                                            @if(!empty($employee->evaluator1_id)) 
                                                {{ $employee->evaluator1_full_name }}
                                            @else
                                                ค้นหา
                                            @endif 
                                            </a>
                                            <a href="#" name="searchEvaluatorCodeRemove" class="btn btn-outline-primary"> ลบ</a>
                                        </div>
                                    </td>
                                    <td style="text-align: center; width: 50px;">
                                        <div class="col-sm-3" id="searchEvaluator2{{ $employee->id }}" name="searchEvaluator" >
                                            <input type="hidden" name="evaluator2_id[]" value=@if(!empty($employee->evaluator2_id)) 
                                                "{{ $employee->evaluator2_id }}"
                                            @else
                                                "" 
                                            @endif
                                            />

                                            <a href="#" name="searchEvaluatorCode" data-position-rank-id="{{ $employee->position_rank_id }}" class="btn btn-outline-primary">
                                            @if(!empty($employee->evaluator2_id)) 
                                                {{ $employee->evaluator2_full_name }}
                                            @else
                                                ค้นหา
                                            @endif 
                                            </a>
                                            <a href="#" name="searchEvaluatorCodeRemove" class="btn btn-outline-primary"> ลบ</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"> บันทึก</button>
                        </div>
                    </div>

                    </form>

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



<!-- The Modal -->
<div class="modal" id="modalSearchEvaluator">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">ค้นหาพนักงาน</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <input type="hidden" name="refDivGroupId" data-return-elelment-name="" value="" />

        ชื่อ นามสกุล : <input type="text" name="searchWord" class="form-control" />
        <table class="table">
            <thead>
                <td style="text-align: center; font-weight: bold;">เลือก</td>
                <td style="text-align: center; font-weight: bold;">ชื่อ นามสกุล</td>
                <td style="text-align: center; font-weight: bold;">ตำแหน่ง</td>
            </thead>
            <tbody>                
            </tbody>
        </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

@endsection
