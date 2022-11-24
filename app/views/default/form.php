<style type="text/css">
  [v-cloak] {
    display: none;
  }
</style>
<div id="app">
  <div class="page-header">
    <h1>
      <?php if (isset($headingOverwrite)) : ?>
        <?php echo $headingOverwrite ?>
      <?php else : ?>
        <?php echo ($data ? lang('edit') : lang('add')) . ' ' . (isset($heading) ? $heading : lang('heading')); ?>
      <?php endif ?>
    </h1>
  </div>

  <form id="form" <?php echo (isset($form['class'])) ? 'class = "' . $form['class'] . '"' : '' ?> action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-xs-12" id="header_widget_box">
        <div class="widget-box">
          <!-- <div class="widget-header clearfix">
                    <h4 class="smaller pull-left" style="">
                        <?php if (isset($headingOverwrite)) : ?>
                            <?php echo $headingOverwrite ?>
                        <?php else : ?>
                          <?php echo ($data ? lang('edit') : lang('add')) . ' ' . (isset($heading) ? $heading : lang('heading')); ?>
                        <?php endif ?>
                    </h4>
                </div> -->

          <!-- <pre>
            <?php print_r($data); ?>
          </pre> -->

          <?php if ($data) {

            $updated = ($data->updated_at != '' && $data->updated_at != null && $data->updated_at != 'null')
              ? '&nbsp;&nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i> &nbsp;&nbsp;
            <strong>Updated : </strong> ' . get_date_time($data->updated_at) . ' | ' . $this->m_master->get_username_by($data->updated_by) : '';

          ?>
            <div class="alert alert-info">
              <strong>Created : </strong> <?= get_date_time($data->created_at) ?> | <?= $this->m_master->get_username_by($data->created_by) ?>
              <?= $updated; ?>
            </div>
          <?php } ?>

          <div class="widget-body">
            <div class="widget-main">
              <div class="">
                <?php echo $form['build']; ?>
              </div>
              <hr />
              <div class="row">

                <div class="col-xs-12">
                  <div class="pull-left">
                    <a href="<?php echo $module_url; ?>" class="btn btn-purple"><?php echo lang('cancel_w_icon'); ?></a>
                  </div>
                  <div class="pull-right">
                    <button type="submit" class="<?= (isset($form['class_btn_submit'])) ? $form['class_btn_submit'] : 'btn btn-success'; ?>"><?= (isset($form['class_btn_submit_text'])) ? $form['class_btn_submit_text'] : lang('save_w_icon'); ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="panel-footer">
                  
                </div> -->
        </div>
      </div>
    </div>
  </form>
</div>

<?php if (isset($script_add)) : ?>
  <?php echo $script_add ?>
<?php endif ?>