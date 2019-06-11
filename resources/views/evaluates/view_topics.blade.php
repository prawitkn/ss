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

                            <li class="breadcrumb-item active" aria-current="page">View Topics</li>
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
                    <form id="form1" class="form-inline" method="get" action="{{ url('/topics/view-topics') }}" novalidate="novalidate" >

                        <label for="topic_group_id" class="text-right control-label col-form-label">Topic Group : </label>
                            <div class="">
                                <select name="topic_group_id" id="topic_group_id" class="form-control">
                                    @foreach($topicGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $topic_group_id) selected 
                                        @endif
                                        >{{ $val->topic_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <label for="topic_position_group_id" class="text-right control-label col-form-label">   Topic Position Group : </label>
                            <div class="">
                                <select name="topic_position_group_id" id="topic_position_group_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach($topicPositionGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $topic_position_group_id) selected 
                                        @endif
                                        >{{ $val->topic_position_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary"> Search</button>
                      </form>
                </div>
                <!--card-body-->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Topic ID</th>
                                    <th>Topic Group</th>
                                    <th>Topic Position Group</th>
                                    <th>Topic Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topics as $topic)
                                <tr class="gradeX">
                                    <td>{{ $topic->id }}</td>
                                    <td>{{ $topic->topic_group_name }}</td>
                                    <td>{{ $topic->topic_position_group_name }}</td>
                                    <td>{{ $topic->topic_name }}</td>
                                    <td class="center">
                                        <a href="{{ url('/topics/edit-topic/'.$topic->id) }}" class="btn btn-primary btn-mini">Edit</a> 
                                        <a href="{{ url('/topics/delete-topic/'.$topic->id) }}" class="btn btn-danger btn-mini btn-deleteMaster">Delete</a>
                                    </td>
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