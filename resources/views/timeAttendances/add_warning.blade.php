@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">เตือนวาจา / หนังสือเตือน / พักงาน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page">เตือนวาจา / หนังสือเตือน / พักงาน</li>
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
                    <form id="form1" class="form-inline" method="get" action="{{ url('/timeAttendances/add-warning') }}" novalidate="novalidate" >

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

                        <div class="form-group col-md-2">
                            <label for="cycle_no">รอบ : </label>
                            <select name="cycle_no" class="form-control">
                                <option value="1" @if($cycle_no==1) ' selected ' @endif >1 : พ.ย.-เม.ย.</option>
                                <option value="2" @if($cycle_no==2) ' selected ' @endif >2 : พ.ค.-ต.ค.</option>
                            </select>
                        </div><!--col-->

                        <div class="form-group col-sm-1">
                            <label for="submit" class="text-right control-label col-form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <button type="submit" class="form-control btn btn-primary"> ค้นหา</button>
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

                <div class="border-top">
                <div class="card-body">
                    <form id="form2" class="form-inline" method="post" action="{{ url('/timeAttendances/use-warning') }}" novalidate="novalidate" >{{ csrf_field() }}
                        <div class="row col-sm-12">
                            <input type="hidden" name="term_id" value="{{ $term->id }}" />
                            <input type="hidden" name="cycle_no" value="{{ $cycle_no }}" />

                            <div class="form-group col-sm-3">
                                <label for="cycle_no">ห้วงเวลาการประเมิน : </label></br>
                                <span style="color: blue; font-weight: bold;">{{ $term->term_name }}</span>
                            </div>

                        </div>
                        <!--row-->

                    <div class="table-responsive">
                        <table id="tbl_no_searching_paging_info" class="display" style="width: 100%" >
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">ชื่อ นามสกุล</th>
                                    <th style="text-align: center;">ตำแหน่ง</th>
                                    <th style="text-align: center; width: 50px;">เตือนวาจา</th>
                                    <th style="text-align: center; width: 50px;">เตือนอักษร</th>
                                    <th style="text-align: center; width: 50px;">พักงาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluateHeaders as $key => $evaluateHeader)
                                @if($cycle_no==1)
                                    <tr class="gradeX">
                                        <td style="text-align: center; width: 50px;">{{ ($key+1) }}</td>

                                        <td style="text-align: center;">
                                            <input type="hidden" name="ids[]" value="{{ $evaluateHeader->id }}" />

                                            {{ $evaluateHeader->person_full_name }}</td>
                                        <td style="text-align: center;">{{ $evaluateHeader->position_name }}</td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="warning[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->warning1_count }}">
                                        </td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="warning_latter[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->warning_latter1_count }}">
                                        </td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="suspended[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->suspended1_count  }}">
                                        </td>
                                    </tr>
                                @else
                                    <tr class="gradeX">
                                        <td style="text-align: center; width: 50px;">{{ ($key+1) }}</td>

                                        <td style="text-align: center; width: 50px;">
                                            <input type="hidden" name="ids[]" value="{{ $evaluateHeader->id }}" />

                                            {{ $evaluateHeader->person_full_name }}</td>
                                        <td style="text-align: center; width: 50px;">{{ $evaluateHeader->position_name }}</td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="warning[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->warning2_count }}">
                                        </td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="warning_latter[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->warning_latter2_count }}">
                                        </td>
                                        <td style="text-align: center; width: 50px;">
                                            <input type="number" name="suspended[]" min="0" max="10" style="width: 50px;" value="{{ $evaluateHeader->suspended2_count  }}">
                                        </td>
                                    </tr>
                                @endif
                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.table-resonsive-->
                </div>
                <!--/.border-top-->

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
