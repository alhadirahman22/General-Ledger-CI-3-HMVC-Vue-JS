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

								<?php if ($this->aauth->is_allowed('administration/user_management/museum')) : ?>
									<li data-name="Museum" class="">
										<a href="<?= base_url('administration/user_management/museum'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Museum
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

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
					<?php if ($this->aauth->is_allowed('master/negara')) : ?>
						<li data-name="Negara" class="">
							<a href="<?= base_url('master/negara'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Negara
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/currency')) : ?>
						<li data-name="Currency" class="">
							<a href="<?= base_url('master/currency'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Mata Uang
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/jenis_perolehan')) : ?>
						<li data-name="Jenis_perolehan" class="">
							<a href="<?= base_url('master/jenis_perolehan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Jenis Perolehan
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/fungsi')) : ?>
						<li data-name="Fungsi" class="">
							<a href="<?= base_url('master/fungsi'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Fungsi Benda
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/kategori')) : ?>
						<li data-name="Kategori" class="">
							<a href="<?= base_url('master/kategori'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Kategori
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/klasifikasi_department')) : ?>
						<!-- <li data-name="Klasifikasi_department" class="">
							<a href="<?= base_url('master/klasifikasi_department'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Klasifikasi Department
							</a>

							<b class="arrow"></b>
						</li> -->
					<?php endif ?>
					<?php if ($this->aauth->is_allowed('master/bahan')) : ?>
						<li data-name="Bahan" class="">
							<a href="<?= base_url('master/bahan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Bahan
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/pola')) : ?>
						<li data-name="Pola" class="">
							<a href="<?= base_url('master/pola'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Pola Benda
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/kondisi')) : ?>
						<li data-name="Kondisi" class="">
							<a href="<?= base_url('master/kondisi'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Kondisi
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/situs')) : ?>
						<li data-name="Situs" class="">
							<a href="<?= base_url('master/situs'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Situs
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/status')) : ?>
						<li data-name="Status" class="">
							<a href="<?= base_url('master/status'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Status
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/periode')) : ?>
						<li data-name="Periode" class="">
							<a href="<?= base_url('master/periode'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Periode
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/lokasi_penyimpanan')) : ?>
						<li data-name="Lokasi_penyimpanan" class="">
							<a href="<?= base_url('master/lokasi_penyimpanan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Lokasi Penyimpanan
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/kepemilikan')) : ?>
						<li data-name="Kepemilikan" class="">
							<a href="<?= base_url('master/kepemilikan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Kepemilikan
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/attribute')) : ?>
						<li data-name="Attribute" class="">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-caret-right"></i>
								Atribut
								<b class="arrow fa fa-angle-down"></b>
							</a>
							<b class="arrow"></b>
							<ul class="submenu">
								<?php if ($this->aauth->is_allowed('master/attribute/satuan')) : ?>
									<li data-name="Satuan" class="">
										<a href="<?= base_url('master/attribute/satuan'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Satuan
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

								<?php if ($this->aauth->is_allowed('master/attribute/attribute_data')) : ?>
									<li data-name="Attribute_data" class="">
										<a href="<?= base_url('master/attribute/attribute_data'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Data Atribut
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>

								<?php if ($this->aauth->is_allowed('master/attribute/attribute_groups')) : ?>
									<li data-name="Attribute_groups" class="">
										<a href="<?= base_url('master/attribute/attribute_groups'); ?>">
											<i class="menu-icon fa fa-leaf green"></i>
											Group Atribut
										</a>

										<b class="arrow"></b>
									</li>
								<?php endif ?>


							</ul>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('master/tags')) : ?>
						<li data-name="Tags" class="">
							<a href="<?= base_url('master/tags'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Tags
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif ?>

				</ul>

			</li>

		<?php endif ?>

		<?php if ($this->aauth->is_allowed('benda')) : ?>

			<li data-name="Benda" class="<?php echo menu_active('benda', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-folder-open faa-shake"></i>
					<span class="menu-text">
						Data Koleksi
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('benda/data_benda')) : ?>
						<li data-name="Data_benda" class="">
							<a href="<?= base_url('benda/data_benda'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Benda
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('benda/history')) : ?>
						<li data-name="History" class="">
							<a href="<?= base_url('benda/history'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Cerita
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
				</ul>

			</li>

		<?php endif ?>


		<?php if ($this->aauth->is_allowed('pencarian')) : ?>
			<!-- <li data-name="pencarian" class="<?php echo menu_active('pencarian', false); ?>">
				<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
					<i class="menu-icon fa fa-search faa-shake"></i>
					<span class="menu-text">
						Pencarian
					</span>
				</a>
				<b class="arrow"></b>
			</li> -->
		<?php endif ?>

		<!-- <?php if ($this->aauth->is_allowed('benda')) : ?>
			<li data-name="Benda" class="<?php echo menu_active('benda', false); ?>">
				<a href="<?= base_url('benda'); ?>" class="faa-parent animated-hover">
					<i class="menu-icon fa fa-folder-open faa-shake"></i>
					<span class="menu-text">
						Data Benda Koleksi
					</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif ?> -->

		<?php if ($this->aauth->is_allowed('mutasi')) : ?>

			<li data-name="Mutasi" class="<?php echo menu_active('mutasi', false); ?>">
				<a href="#" class="dropdown-toggle faa-parent animated-hover">
					<i class="menu-icon fa fa-exchange faa-shake"></i>
					<span class="menu-text">
						Mutasi
					</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if ($this->aauth->is_allowed('mutasi/mutasi_benda')) : ?>
						<li data-name="Mutasi_benda" class="">
							<a href="<?= base_url('mutasi/mutasi_benda'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Mutasi Benda
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('mutasi/jenis_mutasi')) : ?>
						<li data-name="Jenis_mutasi" class="">
							<a href="<?= base_url('mutasi/jenis_mutasi'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Jenis Mutasi
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>

					<?php if ($this->aauth->is_allowed('mutasi/management_persetujuan')) : ?>
						<li data-name="Management_persetujuan" class="">
							<a href="<?= base_url('mutasi/management_persetujuan'); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								Management Persetujuan
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif ?>
				</ul>
			</li>
		<?php endif ?>
		<?php if ($this->aauth->is_allowed('data_benda')) : ?>
			<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
				<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
					<i class="menu-icon fa fa-archive faa-shake"></i>
					<span class="menu-text">
						Penyimpanan Benda
					</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif ?>

		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-flag faa-shake"></i>
				<span class="menu-text">
					Pameran
				</span>
			</a>
			<b class="arrow"></b>
		</li>
		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-globe faa-shake"></i>
				<span class="menu-text">
					Konservasi
				</span>
			</a>
			<b class="arrow"></b>
		</li>
		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-tags faa-shake"></i>
				<span class="menu-text">
					Manaj. Refrensi
				</span>
			</a>
			<b class="arrow"></b>
		</li>
		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-cloud-download faa-shake"></i>
				<span class="menu-text">
					Pusat Unduhan
				</span>
			</a>
			<b class="arrow"></b>
		</li>
		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-align-right faa-shake"></i>
				<span class="menu-text">
					Rekapitulasi
				</span>
			</a>
			<b class="arrow"></b>
		</li>
		<li data-name="data_benda" class="<?php echo menu_active('data_benda', false); ?>">
			<a href="<?= base_url('dashboard'); ?>" class="faa-parent animated-hover">
				<i class="menu-icon fa fa-users faa-shake"></i>
				<span class="menu-text">
					Manaj. Pengguna
				</span>
			</a>
			<b class="arrow"></b>
		</li>
	</ul><!-- /.nav-list -->

	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>
</div>