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
                            <li class="breadcrumb-item" aria-current="page">รายการระดับตำแหน่ง</li>

                            <li class="breadcrumb-item active" aria-current="page">แก้ไขระดับตำแหน่ง</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/admin/edit-position_rank/'.$positionRankDetails->id) }}" novalidate="novalidate" >{{ csrf_field() }}

                    <input type="hidden" name="url" value="{{ URL::previous() }}" />
                    
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="position_rank_name" class="col-sm-3 text-right control-label col-form-label">ชื่อระดับตำแหน่ง</label>
                            <div class="col-sm-9">
                                <input type="text" name="position_rank_name" id="position_rank_name" class="form-control" placeholder="Position Rank Here" value="{{ $positionRankDetails->position_rank_name }}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="position_rank_group_id" class="col-sm-3 text-right control-label col-form-label">กลุ่มระดับตำแหน่ง</label>
                            <div class="col-sm-6">
                                <select name="position_rank_group_id" id="position_rank_group_id" class="form-control">
                                @foreach($positionRankGroups as $val)
                                <option value="{{ $val->id }}"
                                @if($val->id==$positionRankDetails->position_rank_group_id)
                                 selected 
                                @endif 
                                >{{ $val->position_rank_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">แก้ไขระดับตำแหน่ง</button>
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