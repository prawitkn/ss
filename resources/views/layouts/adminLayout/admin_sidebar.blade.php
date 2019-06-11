<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('admin/index') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">หน้าแรก</span></a></li>

                @php $isValid=false; @endphp
                @foreach(Session::get('userRoles') as $role)
                    @switch($role->user_role_group_id)
                        @case(1)
                            @php $isValid=true; @endphp
                            @break(2)
                        @default
                    @endswitch
                @endforeach 
                @php if($isValid) { @endphp
                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu"> ผู้ใช้งานระบบ</span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/users/add-user') }}" aria-expanded="false"><i class="mdi mdi-user"></i><span class="hide-menu">Add User</span></a></li> -->

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/users/view-users') }}" aria-expanded="false"><i class="mdi mdi-user"></i><span class="hide-menu">รายการผู้ใช้งานระบบ</span></a></li>
                        </ul>
                    </li>

                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu"> ระดับตำแหน่งงาน</span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/add-position_rank') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มระดับตำแหน่งงาน</span></a></li>

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/view-position_ranks') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการระดับตำแหน่างงาน</span></a></li>
                        </ul>
                    </li>

                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu"> ฝ่าย</span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/add-department') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มฝ่าย</span></a></li>

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/view-departments') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการฝ่าย</span></a></li>
                        </ul>
                    </li>

                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu"> ส่วน</span></a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/add-section') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มส่วน</span></a></li>

                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin/view-sections') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการส่วน</span></a></li>
                        </ul>
                    </li>
                @php } $isValid=false; @endphp

                      
                @foreach(Session::get('userRoles') as $role)
                    @switch($role->user_role_group_id)
                        @case(1) @case(3) @case(4)
                            @php $isValid=true; @endphp
                            @break(2)
                        @default
                    @endswitch
                @endforeach 

                @php if($isValid) { @endphp
                            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> กลุ่มการตัดเกรด </span></a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/gradingGroups/add-gradingGroup') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">เพิ่มกลุ่มการตัดเกรด</span></a></li>

                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/gradingGroups/view-gradingGroups') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu">รายการกลุ่มการตัดเกรด</span></a></li>
                                </ul>
                            </li>

                            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> กลุ่มหัวข้อการประเมิน </span></a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topicGroups/add-topicGroup') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">เพิ่มกลุ่มหัวข้อการประเมิน</span></a></li>

                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topicGroups/view-topicGroups') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu">รายการกลุ่มหัวข้อการประเมิน</span></a></li>
                                </ul>
                            </li>

                            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-human-male-female"></i><span class="hide-menu"> หัวข้อการประเมิน (กลุ่มระดับตำแหน่ง)</span></a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topics/add-topic') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">เพิ่มหัวข้อการประเมิน (กลุ่มระดับตำแหน่ง)</span></a></li>

                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topics/view-topics') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu">รายการหัวข้อการประเมิน (กลุ่มระดับตำแหน่ง)</span></a></li>
                                </ul>
                            </li>

                            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-human-male"></i><span class="hide-menu"> หัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</span></a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topics/add-topic-by-one') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</span></a></li>

                                    <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/topics/view-topics-by-one') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</span></a></li>
                                </ul>
                            </li>

                            

                            
                            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-human"></i><span class="hide-menu"> พนักงาน </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/employees/add-employee') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มพนักงาน</span></a></li>

                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/employees/view-employees') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการพนักงาน</span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/employees/view-evaluators') }}" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu"> ตั้งค่ากลุ่มการประเมิน/ผู้ประเมิน</span></a></li>
                        
                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu"> เวลาการทำงาน</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/timeAttendances/add-leave') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> นำเข้าข้อมูลการลาป่วย/ลากิจ</span></a></li>

                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/timeAttendances/add-late') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> นำเข้าข้อมูลการมาสาย-กลับก่อน</span></a></li>

                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/timeAttendances/add-absence') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> นำเข้าข้อมูลการขาดงาน</span></a></li>

                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/timeAttendances/add-warning') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เตือนวาจา/เตือนอักษร/พักงาน</span></a></li> 

                                
                            </ul>
                        </li>


                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu"> ห้วงการประเมิน</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/terms/add-term') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu"> เพิ่มห้วงการประเมิน</span></a></li>

                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/terms/view-terms') }}" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> รายการห้วงการประเมิน</span></a></li>
                            </ul>
                        </li>
                @php } $isValid=false; @endphp
                         
                            
           
                @foreach(Session::get('userRoles') as $role)
                    @switch($role->user_role_group_id)
                        @case(1) @case(3)
                            @php $isValid=true; @endphp
                            @break(2)
                        @default
                    @endswitch
                @endforeach 

                @php if($isValid) { @endphp
                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/grading') }}" aria-expanded="false"><i class="mdi mdi-chart-scatterplot-hexbin"></i><span class="hide-menu">สรุปคะแนน</span></a></li> 
                @php } $isValid=false; @endphp                
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
