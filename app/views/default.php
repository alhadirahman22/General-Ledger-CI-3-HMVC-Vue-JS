<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo "{$title}"; ?></title>

  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">

  <?php
  foreach ($css as $file) {
    echo "\n";
    echo '<link href="' . $file . '?v=0.0.43" rel="stylesheet" type="text/css" />';
  }
  echo "\n";
  ?>
  <script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var site_url = '<?php echo site_url(); ?>';
    var current_url = '<?php echo current_url(); ?>';
    var decimal_digit = '<?php echo settings('number_of_decimal'); ?>';
    var decimal_separator = '<?php echo settings('separator_decimal'); ?>';
    var thousand_separator = '<?php echo settings('separator_thousand'); ?>';
    const jwtKey = '<?php echo jwtKey ?>';
    const SessionData = <?php echo json_encode($this->session->all_userdata()) ?>;
  </script>

  <style>
    .dataTables_wrapper input[type=search],
    .dataTables_wrapper input[type=text],
    .dataTables_wrapper select {
      margin: 0px !important;
    }

    .table-center td {
      text-align: center
    }
  </style>

</head>

<body class="no-skin">

  <?= $navbar; ?>

  <div class="main-container ace-save-state" id="main-container">


    <?= $sidebar; ?>

    <div class="main-content">

      <div class="main-content-inner">
        <?= $breadcrumbs; ?>

        <div class="page-content">

          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->
              <?php echo $output; ?>
              <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.page-content -->
      </div>
    </div><!-- /.main-content -->

    <div class="footer">
      <div class="footer-inner">
        <div class="footer-content">
          <span class="bigger-120">
            <span class="blue bolder"><?php echo settings('app_company_name') ?></span>
            <!-- Application &copy; 2021-2026 -->
          </span>

        </div>
      </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
      <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
  </div><!-- /.main-container -->
  <?php echo $MODAL ?>
  <!-- load modal -->
</body>
<?php
foreach ($js as $file) {
  echo "\n    ";
  echo '<script src="' . $file . '?v=0.0.57"></script>';
}
echo "\n"
?>
<script type="text/javascript">
  try {
    ace.settings.loadState('main-container')
  } catch (e) {}
</script>
<script type="text/javascript">
  var segments = <?php echo json_encode($segments); ?>;
  $(document).ready(function() {
    $('.nav-list li').each(function() {
      var name = $(this).attr('data-name');

      if ($.inArray(name, segments) != -1) {
        if ($(this).find('ul.submenu').length > 0) {
          $(this).addClass('open');
        } else {
          $(this).addClass('active');
        }
      }

    });

    $('.img-fitter').imgFitter({

      // CSS background position
      backgroundPosition: 'center center',

      // for image loading effect
      fadeinDelay: 400,
      fadeinTime: 1200

    });
  })

  $('.show-desc-act').tooltip({
    show: {
      effect: "slideDown",
      delay: 250
    }
  });
</script>
<script type="text/javascript">
  <?php
  if ($this->session->flashdata('form_response_status')) {
    //echo 'console.log("dffsf");';
    echo $this->session->flashdata('form_response_status') . '_message("' . $this->session->flashdata('form_response_message') . '");';
    unset($_SESSION['form_response_status']);
  }
  ?>
</script>

</html>