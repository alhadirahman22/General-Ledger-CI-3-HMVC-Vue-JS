<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                  <div class="col-md-4"><h3><?php echo ($data ? lang('edit') : lang('add')) . ' ' . (isset($heading) ? $heading : lang('heading')); ?></h3></div>
                  <div class="col-md-4 offset-md-4" style="text-align: right;">
                      <a href="<?php echo $module_url; ?>" class="btn bg-grey"><?php echo lang('back_w_icon'); ?></a>
                  </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                       <form id="form" <?php echo (isset($form['class'])) ? 'class = "'.$form['class'].'"' : '' ?> action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
                           <div class="card-body">
                               <?php echo $form['build']; ?>
                           </div>
                           <div class="card-footer bg-white">
                            <div class="float-left">
                               <a href="<?php echo $module_url; ?>" class="btn btn-secondary"><?php echo lang('cancel_w_icon'); ?></a>
                            </div>
                            <div class="float-right">
                               <button type="submit" class="btn btn-primary ml-3"><?php echo lang('save_w_icon'); ?></button>
                            </div>
                           </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  let oData = <?php echo json_encode($data) ?>;
</script>
