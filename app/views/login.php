<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo "{$title}"; ?></title>
  <?php
  foreach ($css as $file) {
    echo "\n    ";
    echo '<link href="' . $file . '" rel="stylesheet" type="text/css" />';
  }
  echo "\n";
  ?>
  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
  <script type="text/javascript">
    var site_url = '<?php echo site_url(); ?>';
    var base_url = '<?php echo base_url(); ?>';
    var current_url = '<?php echo current_url(); ?>';
    var decimal_digit = '<?php echo settings('number_of_decimal'); ?>';
    var decimal_separator = '<?php echo settings('separator_decimal'); ?>';
    var thousand_separator = '<?php echo settings('separator_thousand'); ?>';
    const jwtKey = '<?php echo jwtKey ?>';
  </script>
</head>

<body class="login-layout light-login">
  <div class="main-container">
    <div class="main-content">
      <div class="row" style="margin-top: 50px;">
        <div class="col-sm-10 col-sm-offset-1">
          <div class="login-container">


            <div class="space-6"></div>

            <div class="position-relative">
              <div id="login-box" class="login-box visible widget-box no-border">
                <div class="widget-body">
                  <div class="widget-main">



                    <div style="text-align: center;">
                      <img src="<?= base_url('assets/images/brand/LogoSanaknet.png') ?>" style="width:70%;" />
                    </div>


                    <h4 class="header blue lighter bigger">
                      <i class="ace-icon fa fa-coffee green"></i>
                      Please Enter Your Information
                    </h4>

                    <div class="space-6"></div>

                    <form class="login-form ajax-token" action="<?php echo current_url(); ?>" method="post" id="form">
                      <fieldset>
                        <label class="block clearfix">
                          <span class="block input-icon input-icon-right">
                            <input type="hidden" value="<?php echo $this->input->get('back'); ?>" name="back">
                            <input type="text" name="identity" class="form-control" placeholder="Username" required value="<?php echo $username ?>" />
                            <i class="ace-icon fa fa-user"></i>
                          </span>
                        </label>

                        <label class="block clearfix">
                          <span class="block input-icon input-icon-right">
                            <input type="password" name="password" class="form-control" placeholder="Password" required value="<?php echo $password ?>" />
                            <i class="ace-icon fa fa-lock"></i>
                          </span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                          <!--  <label class="inline">
                                <input type="checkbox" class="ace" />
                                <span class="lbl"> Remember Me</span>
                              </label> -->

                          <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                            <i class="ace-icon fa fa-key"></i>
                            <span class="bigger-110">Login</span>
                          </button>
                        </div>

                        <div class="space-4"></div>
                      </fieldset>
                    </form>

                    <!--  <div class="social-or-login center">
                          <span class="bigger-110">Or Login Using</span>
                        </div> -->

                    <div class="space-6"></div>

                    <!-- <div class="social-login center">
                          <a class="btn btn-primary">
                            <i class="ace-icon fa fa-facebook"></i>
                          </a>

                          <a class="btn btn-info">
                            <i class="ace-icon fa fa-twitter"></i>
                          </a>

                          <a class="btn btn-danger">
                            <i class="ace-icon fa fa-google-plus"></i>
                          </a>
                        </div> -->
                  </div><!-- /.widget-main -->

                  <div class="toolbar clearfix">
                    <!--  <div>
                          <a href="#" data-target="#forgot-box" class="forgot-password-link">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            I forgot my password
                          </a>
                        </div> -->

                    <!-- <div>
                          <a href="#" data-target="#signup-box" class="user-signup-link">
                            I want to register
                            <i class="ace-icon fa fa-arrow-right"></i>
                          </a>
                        </div> -->
                  </div>
                </div><!-- /.widget-body -->
              </div><!-- /.login-box -->

              <div id="forgot-box" class="forgot-box widget-box no-border">
                <div class="widget-body">
                  <div class="widget-main">
                    <h4 class="header red lighter bigger">
                      <i class="ace-icon fa fa-key"></i>
                      Retrieve Password
                    </h4>

                    <div class="space-6"></div>
                    <p>
                      Enter your email and to receive instructions
                    </p>

                    <form>
                      <fieldset>
                        <label class="block clearfix">
                          <span class="block input-icon input-icon-right">
                            <input type="email" class="form-control" placeholder="Email" />
                            <i class="ace-icon fa fa-envelope"></i>
                          </span>
                        </label>

                        <div class="clearfix">
                          <button type="button" class="width-35 pull-right btn btn-sm btn-danger">
                            <i class="ace-icon fa fa-lightbulb-o"></i>
                            <span class="bigger-110">Send Me!</span>
                          </button>
                        </div>
                      </fieldset>
                    </form>
                  </div><!-- /.widget-main -->

                  <div class="toolbar center">
                    <a href="#" data-target="#login-box" class="back-to-login-link">
                      Back to login
                      <i class="ace-icon fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div><!-- /.widget-body -->
              </div><!-- /.forgot-box -->
            </div><!-- /.position-relative -->

            <!-- <div class="navbar-fixed-top align-right">
              <br />
              &nbsp;
              <a id="btn-login-dark" href="#">Dark</a>
              &nbsp;
              <span class="blue">/</span>
              &nbsp;
              <a id="btn-login-blur" href="#">Blur</a>
              &nbsp;
              <span class="blue">/</span>
              &nbsp;
              <a id="btn-login-light" href="#">Light</a>
              &nbsp; &nbsp; &nbsp;
            </div> -->
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.main-content -->
  </div><!-- /.main-container -->

</html>

<?php echo $MODAL ?>
<!-- load modal -->

<?php
foreach ($js as $file) {
  echo "\n    ";
  echo '<script src="' . $file . '"></script>';
}
echo "\n";
?>

<script type="text/javascript">
  if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
  jQuery(function($) {
    $(document).on('click', '.toolbar a[data-target]', function(e) {
      e.preventDefault();
      var target = $(this).data('target');
      $('.widget-box.visible').removeClass('visible'); //hide others
      $(target).addClass('visible'); //show target
    });
  });



  //you don't need this, just used for changing background
  jQuery(function($) {
    $('#btn-login-dark').on('click', function(e) {
      $('body').attr('class', 'login-layout');
      $('#id-text2').attr('class', 'white');
      $('#id-company-text').attr('class', 'blue');

      e.preventDefault();
    });
    $('#btn-login-light').on('click', function(e) {
      $('body').attr('class', 'login-layout light-login');
      $('#id-text2').attr('class', 'grey');
      $('#id-company-text').attr('class', 'blue');

      e.preventDefault();
    });
    $('#btn-login-blur').on('click', function(e) {
      $('body').attr('class', 'login-layout blur-login');
      $('#id-text2').attr('class', 'white');
      $('#id-company-text').attr('class', 'light-blue');

      e.preventDefault();
    });

  });
</script>