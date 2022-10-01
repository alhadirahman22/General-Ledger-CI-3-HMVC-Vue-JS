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
    <div class="row">
        <div class="col-xs-12" id="header_widget_box">
            <div class="widget-box">

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
                        <adm-employee :moduledata="<?php echo $moduleData ?>" :dataemployees="<?php echo $dataEmployees ?>" :iconbtn="<?php echo $iconBtn ?>"></adm-employee>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>