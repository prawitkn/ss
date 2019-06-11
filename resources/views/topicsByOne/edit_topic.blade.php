@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"> หัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"> หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page"> รายการหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</li>

                            <li class="breadcrumb-item active" aria-current="page"> แก้ไขหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</li>
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
        <div class="col-md-12">
            <div class="card">
                <form class="form-horizontal" method="post" action="{{ url('/topics/edit-topic-by-one/'.$topicDetails->id) }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="topic_group_id" class="col-sm-3 text-right control-label col-form-label">กลุ่มหัวข้อการประเมิน</label>
                            <div class="col-sm-3">
                                <select name="topic_group_id" id="topic_group_id" class="form-control">
                                @foreach($topicGroups as $val)
                                <option value="{{ $val->id }}"
                                    @if($val->id == $topicDetails->topic_group_id) selected @endif
                                    >{{ $val->topic_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-3 text-right control-label col-form-label">   พนักงาน/ตำแหน่งงาน : </label>
                            <div class="col-sm-3">
                                <input type="hidden" name="employee_id" value="{{ $topicDetails->employee_id }}" />

                                <a href="#" name="searchEmployeeCode" data-id="" class="btn btn-outline-primary">{{ $topicDetails->person_full_name }}</a>
                                <a href="#" name="searchEmployeeCodeRemove" data-id="" class="btn btn-outline-primary"> ลบ</a>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">หัวข้อการประเมิน</label>
                            <div class="col-sm-6">
                                <textarea name="topic_name" id="topic_name" class="form-control" placeholder="Topic Here"> {{ $topicDetails->topic_name }}</textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">เป้าหมายที่ใช้ประเมิน (KPI)</label>
                            <div class="col-sm-6">
                                <textarea name="topic_desc" id="topic_desc" class="form-control" placeholder="Topic Description Here">{{ $topicDetails->topic_desc }}</textarea>
                            </div>
                        </div>
                        
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"> แก้ไขหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</button>
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

<!-- The Modal -->
<div class="modal" id="modalSearchEmployee">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">ค้นหาพนักงาน</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <input type="hidden" name="refId" value="" />

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