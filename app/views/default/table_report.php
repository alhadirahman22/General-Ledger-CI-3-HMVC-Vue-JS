<table class="table table-hover datatable-responsive" id="table" data-url="<?php echo $table['url']; ?>">

    <thead>
        <tr class="column">
            <?php
            foreach ($table['columns'] as $key => $column) {
                echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . '" class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
            }
            ?>
        </tr>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align:right">Total:</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>