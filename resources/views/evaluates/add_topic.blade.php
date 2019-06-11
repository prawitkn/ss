@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">Topics</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Topics</li>

                            <li class="breadcrumb-item active" aria-current="page">Add Topic</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/topics/add-topic') }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="topic_group_id" class="col-sm-3 text-right control-label col-form-label">Topic Group</label>
                            <div class="col-sm-3">
                                <select name="topic_group_id" id="topic_group_id" class="form-control">
                                @foreach($topicGroups as $val)
                                <option value="{{ $val->id }}">{{ $val->topic_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label for="topic_position_group_id" class="col-sm-3 text-right control-label col-form-label">Topic Position Group</label>
                            <div class="col-sm-3">
                                <select name="topic_position_group_id" id="topic_position_group_id" class="form-control">
                                @foreach($topicPositionGroups as $val)
                                <option value="{{ $val->id }}">{{ $val->topic_position_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">Topic</label>
                            <div class="col-sm-9">
                                <textarea name="topic_name" id="topic_name" class="form-control" placeholder="Topic Here"></textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">Topic Description</label>
                            <div class="col-sm-9">
                                <textarea name="topic_desc" id="topic_desc" class="form-control" placeholder="Topic Here"></textarea>
                            </div>
                        </div>
                        
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Add Topic</button>
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