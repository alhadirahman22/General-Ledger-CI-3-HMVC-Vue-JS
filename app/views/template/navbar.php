<?php $user = $this->session->userdata('user') ?>
<div id="navbar" class="navbar navbar-default ace-save-state">
	<div class="navbar-container ace-save-state" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<div class="navbar-header pull-left">
			<a href="<?= base_url('dashboard'); ?>" class="navbar-brand" style="padding-top:0px;padding-bottom:0px;">
				<img src="<?= settings('logo_url'); ?>" style="width:189px;" />
				<!-- <small> -->
				<!-- <span style="font-size: 17px;"><?php echo settings('app_name') ?></span> -->
				<!-- </small> -->
			</a>
		</div>

		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">


				<li class="light-blue dropdown-modal">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<?php if (isset($user->data_employee->photo) && !empty($user->data_employee->photo)) : ?>
							<img class="nav-user-photo img-fitter" data-src="<?php echo base_url() ?>uploads/photo/<?php echo $user->data_employee->photo ?>" width="45" alt="Profile Photo" />
						<?php else : ?>
							<img class="nav-user-photo img-fitter" data-src="<?php echo base_url() ?>assets/images/avatars/profile.png" alt="Profile Photo" width="45" />
						<?php endif ?>
						<span class="user-info">
							<?php if (isset($user->data_employee->nama) && !empty($user->data_employee->nama)) : ?>
								<small><?= $user->data_employee->nama; ?></small>
							<?php else : ?>
								<small><?= $this->session->userdata('username'); ?></small>
							<?php endif ?>
							<?= $this->session->userdata('level_user'); ?>
						</span>
						<i class="ace-icon fa fa-caret-down"></i>
					</a>
					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li data-name="Changepass">
							<a href="<?= base_url('changepass'); ?>">
								<i class="ace-icon fa fa-pencil"></i>
								Change Password
							</a>
						</li>
						<!-- <li>
							<a href="<?php echo base_url() . 'profile' ?>">
								<i class="ace-icon fa fa-user"></i>
								Profile
							</a>
						</li> -->

						<li class="divider"></li>

						<li>
							<a href="<?php echo base_url() . 'auth/logout' ?>">
								<i class="ace-icon fa fa-power-off"></i>
								Logout
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div><!-- /.navbar-container -->
</div>

<style>
	.dropdown-menu-left {
		left: 0;
	}
</style>