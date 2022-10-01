<style>
	.infobox-custom {
		min-width: 337px !important;
	}
</style>

<div id="app">
	<!-- <?php if (settings('env_debug') == 'development') : ?>
		<pre>
	    <?php print_r($this->session->all_userdata()) ?>
	</pre>
	<?php else : ?>
		<div style="text-align: center;margin-top:10%;">
			<img style="max-width: 200px;" src="<?= base_url('assets/images/brand/logo4.png'); ?>" />
		</div>
	<?php endif ?> -->

	<div class="col-md-12">
		<div class="page-header">
			<h1>
				Dashboard
			</h1>
		</div>
	</div>

	<div class="col-xs-12 hide contentDashboard">
		<div class="row">
			<div class="space-6"></div>

			<div class="col-sm-8 infobox-container">
				<div class="infobox infobox-custom infobox-green">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-check-circle"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number bendaIsGood">32</span>
						<div class="infobox-content">Jumlah Kondisi Baik</div>
					</div>

					<div class="stat stat-success bendaIsGoodPercent">8%</div>
				</div>

				<div class="infobox infobox-custom infobox-red">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-bug"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number bendaIsBad">11</span>
						<div class="infobox-content">Jumlah Kondisi Rusak</div>
					</div>

					<div class="badge badge-success bendaIsBadPercent">
						+32%
						<i class="ace-icon fa fa-arrow-up"></i>
					</div>
				</div>

				<div class="infobox infobox-custom infobox-pink">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-database"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number bendaLast3Month">8</span>
						<div class="infobox-content">Koleksi 3 bulan trakhir</div>
					</div>
					<div class="stat stat-important bendaLast3MonthPercent">4%</div>
				</div>

				<div class="infobox infobox-custom infobox-blue">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-exchange"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number MutasiProcess">7</span>
						<div class="infobox-content">Dalam Proses Mutasi</div>
					</div>
				</div>

				<div class="infobox infobox-custom infobox-orange2">
					<div class="infobox-chart">
						<span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number pengunjung">0</span>
						<div class="infobox-content">Pengunjung</div>
					</div>

					<div class="badge badge-success pengunjungPercent">
						7.2%
						<i class="ace-icon fa fa-arrow-up"></i>
					</div>
				</div>

				<div class="infobox infobox-custom infobox-blue2">
					<div class="infobox-progress">
						<div class="easy-pie-chart percentage memoryPercentPie" data-percent="<?php echo (json_decode($dataParser, true))['memoryPercent']  ?>" data-size="46">
							<span class="percent memoryPercent">42</span>%
						</div>
					</div>

					<div class="infobox-data">
						<span class="infobox-text">Memory Usage</span>

						<div class="infobox-content">
							<span class="bigger-110">~</span>
							<?php echo (json_decode($dataParser, true))['ramRemaining']  ?> Mb
						</div>
					</div>
				</div>

				<div class="space-6"></div>

				<!-- <div class="infobox infobox-green infobox-small infobox-dark">
					<div class="infobox-progress">
						<div class="easy-pie-chart percentage" data-percent="61" data-size="39">
							<span class="percent">61</span>%
						</div>
					</div>

					<div class="infobox-data">
						<div class="infobox-content">Task</div>
						<div class="infobox-content">Completion</div>
					</div>
				</div>

				<div class="infobox infobox-blue infobox-small infobox-dark">
					<div class="infobox-chart">
						<span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
					</div>

					<div class="infobox-data">
						<div class="infobox-content">Earnings</div>
						<div class="infobox-content">$32,000</div>
					</div>
				</div>

				<div class="infobox infobox-grey infobox-small infobox-dark">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-download"></i>
					</div>

					<div class="infobox-data">
						<div class="infobox-content">Downloads</div>
						<div class="infobox-content">1,205</div>
					</div>
				</div> -->
			</div>


			<div class="col-sm-3">
				<div class="widget-box">
					<div class="widget-header widget-header-flat widget-header-small">
						<h5 class="widget-title">
							<i class="ace-icon fa fa-signal"></i>
							Koleksi Benda
						</h5>

						<div class="widget-toolbar no-border">
							<div class="inline dropdown-hover">
								<button class="btn btn-minier btn-primary">
									<?php echo date('d F Y', strtotime((json_decode($dataParser, true))['created_at']))  ?>
									<i class="ace-icon fa icon-on-right bigger-110"></i>
								</button>

								<!-- <ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
									<li class="active">
										<a href="#" class="blue">
											<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
											This Week
										</a>
									</li>

									<li>
										<a href="#">
											<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
											Last Week
										</a>
									</li>

									<li>
										<a href="#">
											<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
											This Month
										</a>
									</li>

									<li>
										<a href="#">
											<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
											Last Month
										</a>
									</li>
								</ul> -->
							</div>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<!-- <canvas id="myChart" width="400" height="400"></canvas> -->
							<canvas id="chartKoleksi" width="600" height="600"></canvas>
						</div><!-- /.widget-main -->
					</div><!-- /.widget-body -->
				</div><!-- /.widget-box -->
			</div><!-- /.col -->
		</div><!-- /.row -->

		<div class="hr hr32 hr-dotted"></div>

		<div class="row">
			<div class="col-sm-4">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="widget-title lighter">
							<i class="ace-icon fa fa-database orange"></i>
							Jumlah Koleksi
						</h4>

						<div class="widget-toolbar">
							<a href="#" data-action="collapse">
								<i class="ace-icon fa fa-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Koleksi
										</th>

										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Jumlah
										</th>

									</tr>
								</thead>

								<tbody class="benda-category">
									<tr>
										<td>Koleksi A</td>
										<td>
											<b class="green">14</b>
										</td>
									</tr>

								</tbody>
							</table>
						</div><!-- /.widget-main -->
					</div><!-- /.widget-body -->
				</div><!-- /.widget-box -->
			</div><!-- /.col -->

			<div class="col-sm-4">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="widget-title lighter">
							<i class="ace-icon fa fa-database orange"></i>
							Penyebaran Koleksi
						</h4>

						<div class="widget-toolbar">
							<a href="#" data-action="collapse">
								<i class="ace-icon fa fa-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Departemen
										</th>

										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Jumlah
										</th>

									</tr>
								</thead>

								<tbody class="benda-dept">
									<tr>
										<td>Koleksi A</td>
										<td>
											<b class="green">14</b>
										</td>
									</tr>

								</tbody>
							</table>
						</div><!-- /.widget-main -->
					</div><!-- /.widget-body -->
				</div><!-- /.widget-box -->
			</div><!-- /.col -->

			<div class="col-sm-4">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="widget-title lighter">
							<i class="ace-icon fa fa-database orange"></i>
							Tagging
						</h4>

						<div class="widget-toolbar">
							<a href="#" data-action="collapse">
								<i class="ace-icon fa fa-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Tag
										</th>

										<th>
											<i class="ace-icon fa fa-caret-right blue"></i>Jumlah
										</th>

									</tr>
								</thead>

								<tbody class="benda-tagging">
									<tr>
										<td>Koleksi A</td>
										<td>
											<b class="green">14</b>
										</td>
									</tr>

								</tbody>
							</table>
						</div><!-- /.widget-main -->
					</div><!-- /.widget-body -->
				</div><!-- /.widget-box -->
			</div><!-- /.col -->


		</div><!-- /.row -->

	</div>





</div>

<script>
	const dataParser = <?php echo $dataParser  ?>;
</script>