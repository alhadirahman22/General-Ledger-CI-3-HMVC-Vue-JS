<style>
    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        padding-top: 15px;
        /* width: 60%; */
    }

    #sortable li {
        margin: 0 3px 3px 3px;
        padding: 0.4em;
        padding-left: 1.5em;
        font-size: 1.4em;
        min-height: 18px;
        cursor: pointer;
    }

    #sortable li span {
        position: absolute;
        margin-left: -1.3em;
    }
</style>

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
        <div class="col-xs-12">
            <div class="widget-box">

                <div class="widget-body">
                    <div class="widget-main">

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <ul id="sortable">
                                    <?php foreach ($data as $key => $item) {
                                        echo '<li class="ui-state-default">
                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span> ' . $item[$name] . '
                                    <input name="id[' . $key . ']" class="id hide" value="' . $item[$this->table_id_key] . '" />
                                    </li>';
                                    } ?>
                                </ul>
                            </div>
                        </div>



                        <hr />
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
        </div>
    </div>

</form>