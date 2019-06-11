@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">ระดับตำแหน่ง</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>

                            <li class="breadcrumb-item active" aria-current="page">รายการระดับตำแหน่ง</li>
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
                    <form id="form2" class="form-inline" method="post" action="{{ url('/admin/edit-position_ranks') }}" novalidate="novalidate" >{{ csrf_field() }}

                    <input type="hidden" name="url_current" value="{{ URL::current() }}" />

                    <div class="table-responsive">
                        <table id="tbl_no_paging_info" class="display">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">ชื่อระดับตำแหน่ง</th>
                                    <th style="text-align: center;">กลุ่มระดับตำแหน่ง</th>
                                    <th style="text-align: center; width: 50px;">สถานะ</th>
                                    <th style="text-align: center; width: 180px;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($positionRanks as $positionRank)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="hidden" name="ids[]" class="form-control" style="text-align: center; width: 50px;" value="{{ $positionRank->id }}" />

                                        <input type="text" name="seq_nos[]" class="form-control" style="text-align: center; width: 50px;" value="{{ $positionRank->position_rank_seq_no }}" />
                                    </td>
                                    <td style="text-align: center;">{{ $positionRank->position_rank_name }}</td>
                                    <td style="text-align: center;">{{ $positionRank->position_rank_group_name }}</td>
                                    <td style="text-align: center; width: 50px;">
                                        @if($positionRank->status==1)
                                            <a href="{{ url('/admin/set-active-position_rank/'.$positionRank->id.'/'.$positionRank->status) }}"><span class="badge badge-success">ใช้งาน</span>
                                            </a>
                                        @else 
                                            <a href="{{ url('/admin/set-active-position_rank/'.$positionRank->id.'/'.$positionRank->status) }}"><span class="badge badge-danger">ไม่ใช้งาน</span></a>
                                        @endif   
                                    </td>
                                    <td style="text-align: center;"><a href="{{ url('/admin/edit-position_rank/'.$positionRank->id) }}" class="btn btn-primary btn-mini"><i class="mdi mdi-table-edit"></i> แก้ไข</a> <a href="{{ url('/admin/delete-position_rank/'.$positionRank->id) }}" class="btn btn-danger btn-mini btn-deleteMaster"><i class="mdi mdi-delete-forever"></i> ลบ</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.table-responsive-->

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> บันทึกข้อมูลในตาราง</button>
                        </div>
                    </div>
                    <!--/.border-top-->
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

@endsection