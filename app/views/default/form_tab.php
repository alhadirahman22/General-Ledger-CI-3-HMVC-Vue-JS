<?php $this->load->view('default/style_nav'); ?>
<div class="page-header">
    <h1>
        <?php if (isset($headingOverwrite)): ?>
            <?php echo $headingOverwrite ?>
            <?php else: ?>
                <?php echo ($data ? lang('edit') : lang('add')) . ' ' . (isset($heading) ? $heading : lang('heading')); ?>
                <?php endif ?>
    </h1>
</div>

<form id="form" <?php echo (isset($form['class'])) ? 'class = "'.$form['class'].'"' : '' ?> action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
    <div class="tabbable">
        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
            <?php foreach ($form['build'] as $key => $build) { ?>
                <li class="<?php echo ($key == 0) ? 'active' : ''; ?>"><a href="#tab-<?php echo $key; ?>"  data-toggle="tab"><?php echo $build['title']; ?></a></li>
                <?php } ?>     
        </ul>
        <div class="tab-content">
            <?php foreach ($form['build'] as $key => $build) { ?>
            <div class="tab-pane fade <?php echo ($key == 0) ? 'active in' : ''; ?>" id="tab-<?php echo $key; ?>" form-tab = "<?php echo $key; ?>">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo $build['build']; ?>
                    </div>
                </div>
            </div>
                <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left">
                        <a href="<?php echo $module_url; ?>" class="btn btn-purple"><?php echo lang('cancel_w_icon'); ?></a>
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-success"><?php echo lang('save_w_icon'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    
</form>


<!-- <form id="form" <?php echo (isset($form['class'])) ? 'class = "'.$form['class'].'"' : '' ?> action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
<div class="row">
    <div class="col-xs-12">
        <div class="panel with-nav-tabs panel-success">
            <div class="panel-heading">
                <ul class="nav nav-tabs formClassNav">
                    <?php foreach ($form['build'] as $key => $build) { ?>
                        <li class="<?php echo ($key == 0) ? 'active' : ''; ?>"><a href="#tab-<?php echo $key; ?>"  data-toggle="tab"><?php echo $build['title']; ?></a></li>
                    <?php } ?>                    
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <?php foreach ($form['build'] as $key => $build) { ?>
                    <div class="tab-pane fade <?php echo ($key == 0) ? 'active in' : ''; ?>" id="tab-<?php echo $key; ?>" form-tab = "<?php echo $key; ?>">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php echo $build['build']; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-xs-12">
                <div class="pull-left">
                   <a href="<?php echo $module_url; ?>" class="btn btn-purple"><?php echo lang('cancel_w_icon'); ?></a>
                </div>
                <div class="pull-right">
                   <button type="submit" class="btn btn-success"><?php echo lang('save_w_icon'); ?></button>
                </div>
            </div>
          </div>
        </div>    
    </div>   
</div>
</form> -->