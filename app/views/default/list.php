<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1><?php echo isset($heading) ? $heading : lang('heading'); ?></h1>
        </div>
    </div>

    <div class="col-md-12">

        <?php if (isset($btn_option) && $btn_option != '') : ?>
            <div class="toolbar no-padding pull-right">
                <?php echo $btn_option; ?>
            </div>
        <?php endif ?>
    </div>

    <div class="col-xs-12">

        <?php if (isset($filter_custom)) { ?>

            <div class="">
                <div style="background: #e3ecf6;padding: 15px;">
                    <div class="row">
                        <?php foreach ($filter_custom['data_filter'] as $key => $value) { ?>

                            <div class="<?= $filter_custom['class']; ?>">
                                <select name="" id="" data-key="<?= $key ?>" class="form-control custom-filter">
                                    <?php foreach ($filter_custom['data_filter'][$key] as $key_filter => $value_filter) {
                                        echo '<option value="' . $key_filter . '">' . $value_filter . '</option>';
                                    } ?>
                                </select>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php $this->load->view('default/table'); ?>

    </div>
</div>

<?php if (isset($get_language)) : ?>
    <?php echo $get_language ?>
<?php endif ?>

<?php if (isset($script_add)) : ?>
    <?php echo $script_add ?>
<?php endif ?>