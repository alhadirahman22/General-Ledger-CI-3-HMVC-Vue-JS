<!-- <div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed sidebar-scroll"> -->
<div id="sidebar" class="sidebar responsive ace-save-state">

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

					<?php if ($this->aauth->is_allowed('administration/approval')) : ?>
						<li data-name="Approval" class="">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-caret-right"></i>

								Approval
								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

								<?php if ($this->aauth->is_allowed('administration/approval/approval_role')) : ?>
									<li data-name="Approval_role" class="">
										<a href="<?= base_url('administration/approval/Approval_role'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Approval Role
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

								<?php if ($this->aauth->is_allowed('administration/approval/approval_settings')) : ?>
									<li data-name="Approval_settings" class="">
										<a href="<?= base_url('administration/approval/Approval_settings'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Approval Settings
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

							</ul>
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

								<?php if ($this->aauth->is_allowed('administration/user_management/departments')) : ?>
									<li data-name="Departments" class="">
										<a href="<?= base_url('administration/user_management/departments'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Departemen
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

								<?php if ($this->aauth->is_allowed('administration/user_management/employees')) : ?>
									<li data-name="Employees" class="">
										<a href="<?= base_url('administration/user_management/employees'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Karyawan
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

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

		<?php if ($this->aauth->is_allowed('master')) : ?>

			<li data-name="Master" class="<?php echo menu_active('master', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-database faa-shake"></i>
					<span class="menu-text">
						Master
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('master/jabatan')) : ?>
						<li data-name="Jabatan" class="">
							<a href="<?= base_url('master/jabatan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Jabatan
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
					<!-- <?php if ($this->aauth->is_allowed('master/negara')) : ?>
						<li data-name="Negara" class="">
							<a href="<?= base_url('master/negara'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Negara
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?> -->

					<!-- <?php if ($this->aauth->is_allowed('master/currency')) : ?>
						<li data-name="Currency" class="">
							<a href="<?= base_url('master/currency'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Mata Uang
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?> -->
				</ul>

			</li>

		<?php endif ?>
		<?php if ($this->aauth->is_allowed('reimbursment')) : ?>
			<li data-name="reimbursment" class="<?php echo menu_active('reimbursment', false); ?> disabled">
				<a href="<?= base_url('reimbursment'); ?>" class="faa-parent animated-hover">
					<i class="menu-icon fa fa-archive faa-shake"></i>
					<span class="menu-text">
						Reimbursment
					</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif ?>

	</ul><!-- /.nav-list -->

	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>
</div>