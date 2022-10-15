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
		<li data-name="Mytask" class="<?php echo menu_active('mytask', false); ?>">
			<a href="<?= base_url('mytask'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-paper-plane faa-shake"></i>
				<span class="menu-text"> My Task </span>
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
			<li data-name="Reimbursment" class="<?php echo menu_active('reimbursment', false); ?>">
				<a href="<?= base_url('reimbursment'); ?>" class="faa-parent animated-hover">
					<i class="menu-icon fa fa-archive faa-shake"></i>
					<span class="menu-text">
						Reimbursment
					</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif ?>

		<?php if ($this->aauth->is_allowed('contact_management')) : ?>
			<li data-name="Contact_management" class="">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-address-book faa-shake"></i>
					<span class="menu-text">
						<?php echo lang('contact_management') ?>
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('contact_management/customers')) : ?>
						<li data-name="Customers" class="">
							<a href="<?= base_url('contact_management/customers'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Customers
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('contact_management/suppliers')) : ?>
						<li data-name="Suppliers" class="">
							<a href="<?= base_url('contact_management/suppliers'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
				</ul>
			</li>
		<?php endif ?>
		<?php if ($this->aauth->is_allowed('purchasing')) : ?>

			<li data-name="Purchasing" class="<?php echo menu_active('purchasing', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-shopping-cart faa-shake"></i>
					<span class="menu-text">
						Purchasing
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('purchasing/purchase_request')) : ?>
						<li data-name="Purchase_request" class="">
							<a href="<?= base_url('purchasing/purchase_request'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Request
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('purchasing/purchase_order')) : ?>
						<li data-name="Purchase_order" class="">
							<a href="<?= base_url('purchasing/purchase_order'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Order
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('purchasing/purchase_return')) : ?>
						<li data-name="Purchase_return" class="">
							<a href="<?= base_url('purchasing/purchase_return'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
				</ul>

			</li>

		<?php endif ?>
		<?php if ($this->aauth->is_allowed('sales_order')) : ?>

			<li data-name="Sales_order" class="<?php echo menu_active('sales_order', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-shopping-bag faa-shake"></i>
					<span class="menu-text">
						Sales Order
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('sales_order/master')) : ?>
						<li data-name="Master" class="">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-caret-right"></i>

								Master
								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<?php if ($this->aauth->is_allowed('sales_order/master/tnc')) : ?>
									<li data-name="Tnc" class="">
										<a href="<?= base_url('sales_order/master/tnc'); ?>">
											<i class="menu-icon fa fa-database orange"></i>
											Terms & Conditions
										</a>
										<b class="arrow"></b>
									</li>
								<?php endif ?>
							</ul>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('sales_order/quotation')) : ?>
						<!-- <li data-name="Quotation" class="">
							<a href="<?= base_url('sales_order/quotation/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>

								Quotation
								<b class="arrow"></b>
							</a>
						</li> -->
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('sales_order/contract')) : ?>
						<li data-name="Contract" class="">
							<a href="<?= base_url('sales_order/contract/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>

								Order
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
				</ul>
			</li>
		<?php endif ?>
		<?php if ($this->aauth->is_allowed('finance')) : ?>
			<li data-name="Finance" class="<?php echo menu_active('finance', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text">
						Finance
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>
				<ul class="submenu">

					<?php if ($this->aauth->is_allowed('finance/coa')) : ?>
						<li data-name="Coa" class="">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-caret-right"></i>
								Coa
								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

								<?php if ($this->aauth->is_allowed('finance/coa/coa_group')) : ?>
									<li data-name="Coa_group" class="">
										<a href="<?= base_url('finance/coa/coa_group'); ?>">
											<i class="menu-icon fa fa-database orange"></i>
											Group
										</a>
										<b class="arrow"></b>
									</li>
								<?php endif ?>

								<?php if ($this->aauth->is_allowed('finance/coa/coa_index')) : ?>
									<li data-name="Coa_index" class="">
										<a href="<?= base_url('finance/coa/coa_index'); ?>">
											<i class="menu-icon fa fa-database orange"></i>
											Coa
										</a>
										<b class="arrow"></b>
									</li>
								<?php endif ?>
							</ul>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('finance/generalLedger')) : ?>
						<li data-name="GeneralLedger" class="">
							<a href="<?= base_url('finance/generalLedger/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>

								General Ledger
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('finance/jurnalVoucher')) : ?>
						<li data-name="JurnalVoucher" class="">
							<a href="<?= base_url('finance/jurnalVoucher/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Journal Voucher
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('finance/account_receivable')) : ?>
						<li data-name="Account_receivable" class="">
							<a href="<?= base_url('finance/account_receivable/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>

								Account Receivable
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('finance/account_payable')) : ?>
						<li data-name="Account_payable" class="">
							<a href="<?= base_url('finance/account_payable/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>

								Account Payable
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
				</ul>
			</li>
		<?php endif ?>
		<?php if ($this->aauth->is_allowed('report')) : ?>
			<li data-name="Report" class="<?php echo menu_active('report', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa fa-book faa-shake"></i>
					<span class="menu-text">
						Report
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>
				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('report/buku_besar')) : ?>
						<li data-name="Buku_besar" class="">
							<a href="<?= base_url('report/buku_besar/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Buku Besar
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('report/neraca_saldo')) : ?>
						<li data-name="Neraca_saldo" class="">
							<a href="<?= base_url('report/neraca_saldo/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Neraca Saldo
								<b class="arrow"></b>
							</a>
						</li>
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('report/laba_rugi')) : ?>
						<li data-name="Laba_rugi" class="">
							<a href="<?= base_url('report/laba_rugi/'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Laba Rugi
								<b class="arrow"></b>
							</a>
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