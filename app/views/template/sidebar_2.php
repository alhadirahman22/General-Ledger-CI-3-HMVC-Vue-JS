<div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed sidebar-scroll">

    <ul class="nav nav-list">
        <li data-name="dashboard" class="<?php echo menu_active('dashboard', false); ?>">
            <a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
                <i class="menu-icon fa fa-tachometer faa-shake"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>


        <?php if ($this->aauth->is_allowed('administration')) : ?>
            <li data-name="Administration" class="<?php echo menu_active('administration', false); ?>">
                <a href="#" class="dropdown-toggle faa-parent animated-hover">
                    <i class="menu-icon fa fa-cog faa-spin"></i>
                    <span class="menu-text">
                        <?php echo lang('administration') ?>
                        <span class="badge badge-transparent tooltip-error" title="" data-original-title="Important to Setings">
                            <i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i>
                        </span>
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <?php if ($this->aauth->is_allowed('administration/settings')) : ?>
                        <li data-name="Settings" class="">
                            <a href="<?= base_url('administration/settings'); ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <?php echo lang('settings') ?>
                            </a>

                            <b class="arrow"></b>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('administration/audit_trails')) : ?>
                        <li data-name="Audit_trails" class="">
                            <a href="<?= base_url('administration/audit_trails'); ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <?php echo lang('audit_trails') ?>
                            </a>

                            <b class="arrow"></b>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('administration/api_method')) : ?>
                        <li data-name="Api_method" class="">
                            <a href="<?= base_url('administration/api_method'); ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <?php echo lang('api_method') ?>
                            </a>

                            <b class="arrow"></b>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('administration/user_management')) : ?>
                        <li data-name="User_management" class="">
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>

                                <?php echo lang('user_management') ?>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>

                            <b class="arrow"></b>

                            <ul class="submenu">
                                <?php if ($this->aauth->is_allowed('administration/user_management/permissions')) : ?>
                                    <li data-name="Permissions" class="">
                                        <a href="<?= base_url('administration/user_management/permissions'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            <?php echo lang('permissions') ?>
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->aauth->is_allowed('administration/user_management/roles')) : ?>
                                    <li data-name="Roles" class="">
                                        <a href="<?= base_url('administration/user_management/roles'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            <?php echo lang('roles') ?>
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->aauth->is_allowed('administration/user_management/users')) : ?>
                                    <li data-name="Users" class="">
                                        <a href="<?= base_url('administration/user_management/users'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            <?php echo lang('users') ?>
                                        </a>
                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
        <?php endif ?>


        <?php if ($this->aauth->is_allowed('hr')) : ?>

            <li data-name="Hr" class="<?php echo menu_active('hr', false); ?>">
                <a href="#" class="dropdown-toggle faa-parent animated-hover">
                    <i class="menu-icon fa fa-users faa-shake"></i>
                    <span class="menu-text">
                        Human Resource
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <?php if ($this->aauth->is_allowed('hr/hr_dashboard')) : ?>
                        <li data-name="Hr_dashboard" class="">
                            <a href="<?= base_url('hr/hr_dashboard'); ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Dashboard
                            </a>

                            <b class="arrow"></b>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('hr/master')) : ?>
                        <li data-name="Master" class="">
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Master
                                <b class="arrow fa fa-angle-down"></b>
                            </a>

                            <b class="arrow"></b>

                            <ul class="submenu">

                                <?php if ($this->aauth->is_allowed('hr/master/master_pt')) : ?>
                                    <li data-name="Master_pt" class="">
                                        <a href="<?= base_url('hr/master/master_pt'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Master PT
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/pt_sub')) : ?>
                                    <li data-name="Pt_sub" class="">
                                        <a href="<?= base_url('hr/master/pt_sub'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Sub PT
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/pt_sub_area')) : ?>
                                    <li data-name="Pt_sub_area" class="">
                                        <a href="<?= base_url('hr/master/pt_sub_area'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Area
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/department')) : ?>
                                    <li data-name="Department" class="">
                                        <a href="<?= base_url('hr/master/department'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Department
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/position')) : ?>
                                    <li data-name="Position" class="">
                                        <a href="<?= base_url('hr/master/position'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Position
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/job_description')) : ?>
                                    <li data-name="Job_description" class="">
                                        <a href="<?= base_url('hr/master/job_description'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Job Description
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/employee_status')) : ?>
                                    <li data-name="Employee_status" class="">
                                        <a href="<?= base_url('hr/master/employee_status'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Employee Status
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/religion')) : ?>
                                    <li data-name="Religion" class="">
                                        <a href="<?= base_url('hr/master/religion'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Religion
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/fingerprint_area')) : ?>
                                    <li data-name="Fingerprint_area" class="">
                                        <a href="<?= base_url('hr/master/fingerprint_area'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Fingerprint Area
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>


                                <?php if ($this->aauth->is_allowed('hr/master/education')) : ?>
                                    <li data-name="Education" class="">
                                        <a href="<?= base_url('hr/master/education'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Education Level
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/major')) : ?>
                                    <li data-name="Major" class="">
                                        <a href="<?= base_url('hr/master/major'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Education Major
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/family')) : ?>
                                    <li data-name="Family" class="">
                                        <a href="<?= base_url('hr/master/family'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Family
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/clothes')) : ?>
                                    <li data-name="Clothes" class="">
                                        <a href="<?= base_url('hr/master/clothes'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Clothes
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/attendance_descriptions')) : ?>
                                    <li data-name="Attendance_descriptions" class="">
                                        <a href="<?= base_url('hr/master/attendance_descriptions'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Attendance Description
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/master/leave_categories')) : ?>
                                    <li data-name="Leave_categories" class="">
                                        <a href="<?= base_url('hr/master/leave_categories'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Leave Categories
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                            </ul>

                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('hr/organization_chart')) : ?>
                        <li data-name="Organization_chart" class="">
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Organization Chart
                                <b class="arrow fa fa-angle-down"></b>
                            </a>

                            <b class="arrow"></b>

                            <ul class="submenu">
                                <?php if ($this->aauth->is_allowed('hr/organization_chart/organization_chart_pt')) : ?>
                                    <li data-name="Organization_chart_pt" class="">
                                        <a href="<?= base_url('hr/organization_chart/organization_chart_pt'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            PT
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/organization_chart/organization_chart_employee')) : ?>
                                    <li data-name="Organization_chart_employee" class="">
                                        <a href="<?= base_url('hr/organization_chart/organization_chart_employee'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Employees
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                            </ul>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('hr/employees')) : ?>
                        <li data-name="Employees" class="">
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Employees
                                <b class="arrow fa fa-angle-down"></b>
                            </a>

                            <b class="arrow"></b>

                            <ul class="submenu">
                                <?php if ($this->aauth->is_allowed('hr/employees/employees_data')) : ?>
                                    <li data-name="Employees_data" class="">
                                        <a href="<?= base_url('hr/employees/employees_data'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Employees
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/employees/employee_position')) : ?>
                                    <li data-name="Employee_position" class="">
                                        <a href="<?= base_url('hr/employees/employee_position'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Position
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/employees/employee_contracts')) : ?>
                                    <li data-name="Employee_contracts" class="">
                                        <a href="<?= base_url('hr/employees/employee_contracts'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Contracts
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/employees/employee_clothes')) : ?>
                                    <li data-name="Employee_clothes" class="">
                                        <a href="<?= base_url('hr/employees/employee_clothes'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Clothes
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>


                                <?php if ($this->aauth->is_allowed('hr/employees/bpjs_kesehatan')) : ?>
                                    <li data-name="Bpjs_kesehatan" class="">
                                        <a href="<?= base_url('hr/employees/bpjs_kesehatan'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            BPJS Kesehatan
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                            </ul>
                        </li>
                    <?php endif ?>

                    <?php if ($this->aauth->is_allowed('hr/attendance')) : ?>
                        <li data-name="Attendance" class="">
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Attendance
                                <b class="arrow fa fa-angle-down"></b>
                            </a>

                            <b class="arrow"></b>

                            <ul class="submenu">

                                <?php if ($this->aauth->is_allowed('hr/attendance/attendance_data')) : ?>
                                    <li data-name="Attendance_data" class="">
                                        <a href="<?= base_url('hr/attendance/attendance_data'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Attendance
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/attendance/attendance_holiday')) : ?>
                                    <li data-name="Attendance_holiday" class="">
                                        <a href="<?= base_url('hr/attendance/attendance_holiday'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Holiday
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/attendance/attendance_leave')) : ?>
                                    <li data-name="Attendance_leave" class="">
                                        <a href="<?= base_url('hr/attendance/attendance_leave'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Leave (Permissions)
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <?php if ($this->aauth->is_allowed('hr/attendance/attendance_report')) : ?>
                                    <li data-name="Attendance_report" class="">
                                        <a href="<?= base_url('hr/attendance/attendance_report'); ?>">
                                            <i class="menu-icon fa fa-leaf green"></i>
                                            Report
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                                <?php endif ?>

                                <!-- <?php if ($this->aauth->is_allowed('hr/attendance/attendance_request')) : ?>
                                <li data-name="Attendance_request" class="">
                                    <a href="<?= base_url('hr/attendance/attendance_request'); ?>">
                                        <i class="menu-icon fa fa-leaf green"></i>
                                        User access to edit attendance
                                    </a>

                                    <b class="arrow"></b>
                                </li>
                            <?php endif ?>

                            <?php if ($this->aauth->is_allowed('hr/attendance/attendance_request')) : ?>
                                <li data-name="Attendance_request" class="">
                                    <a href="<?= base_url('hr/attendance/attendance_request'); ?>">
                                        <i class="menu-icon fa fa-leaf green"></i>
                                        Request a change of attendance
                                    </a>

                                    <b class="arrow"></b>
                                </li>
                            <?php endif ?>



                            <?php if ($this->aauth->is_allowed('hr/attendance/attendance_request')) : ?>
                                <li data-name="Attendance_request" class="">
                                    <a href="<?= base_url('hr/attendance/attendance_request'); ?>">
                                        <i class="menu-icon fa fa-leaf green"></i>
                                        Permission Form
                                    </a>

                                    <b class="arrow"></b>
                                </li>
                            <?php endif ?> -->

                            </ul>
                        </li>
                    <?php endif ?>

                </ul>

            </li>


        <?php endif ?>





    </ul><!-- /.nav-list -->

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>