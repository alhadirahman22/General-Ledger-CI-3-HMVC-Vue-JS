<style>
    .filter-active {
        /* border:1px solid red; */
        background-color: #fcffd4 !important;
    }
</style>



<div class="table-responsive">
    <div class="hide">
        <?php
        $filter_2 = $this->session->userdata($filter_name . '_2');
        if (isset($filter_2)) {
            echo '<input value="' . $filter_2['start'] . '" id="filter_2_start"> <input value="' . $filter_2['length'] . '" id="filter_2_length"> <input value="' . $filter_2['page'] . '" id="filter_2_page">';
        } ?>
    </div>
    <table class="table table-striped table-bordered table-hover" id="table_default" data-filter-name="<?= $filter_name ?>" data-url="<?php echo $table['url']; ?>">
        <thead>
            <tr class="column">
                <?php
                foreach ($table['columns'] as $key => $column) {
                    echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . '" ' . (isset($column['id']) ? 'id="' . $column['id'] . '"' : '') . ' class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
                }
                ?>
            </tr>
            <tr class="filterSearch">
                <?php $filter = $this->session->userdata($filter_name); ?>
                <?php foreach ($table['columns'] as $key => $column) { ?>
                    <td class="<?= ($filter && isset($filter[$key]) && $filter[$key] != '') ? 'filter-active' : ''; ?>">
                        <?php if ($column['filter']) : ?>
                            <?php if ($column['filter']['type'] == 'text') : ?>
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" name="<?php echo $key; ?>" class="form-control form-control-sm" autocomplete="off" value="<?php echo ($filter && isset($filter[$key])) ? $filter[$key] : ''; ?>" <?php echo (isset($column['placeholder'])) ? 'placeholder = "' . $column['placeholder'] . '"' : 'placeholder = "Search . . ."' ?>>
                                </div>
                            <?php elseif ($column['filter']['type'] == 'date') : ?>
                                <div class="" style="width: 100%;">
                                    <input type="<?= $column['filter']['type'] ?>" style="max-width:145px;" name="<?php echo $key; ?>" class="form-control input-sm" autocomplete="off" value="<?php echo ($filter && isset($filter[$key])) ? $filter[$key] : ''; ?>" <?php echo (isset($column['placeholder'])) ? 'placeholder = "' . $column['placeholder'] . '"' : '' ?>>
                                </div>
                            <?php elseif ($column['filter']['type'] == 'dropdown') : ?>
                                <div class="form-group has-feedback-left" style="width: 100%;">
                                    <?php echo form_dropdown($key, $column['filter']['options'], ($filter && isset($filter[$key])) ? $filter[$key] : NULL, ['class' => 'form-control form-control-sm', 'style' => 'max-width:50px;']); ?>
                                </div>
                            <?php elseif ($column['filter']['type'] == 'action') : ?>
                                <div style="text-align: center;"><i class="fa fa-cog margin-right"></i></div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div style="text-align: center;"><label>No Filter</label></div>
                        <?php endif ?>
                    </td>
                <?php } ?>

            </tr>
        </thead>
    </table>
</div>