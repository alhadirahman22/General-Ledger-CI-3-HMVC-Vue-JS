<style>
    .table-atr td {
        border-top: 1px solid #fff !important;
    }
</style>

<div class="page-header">
    <h1>
        Detail Perubahan <span id="tanggal"></span> - <?= $created_by; ?>
    </h1>
</div>

<div class="row">
    <div class="col-xs-12" id="header_widget_box">
        <div class="widget-body">
            <div class="widget-main">
                <div class="table-responsive" id="viewTable"></div>

                <hr />
                <div class="row">

                    <div class="col-xs-12">
                        <div class="pull-left">
                            <a href="<?php echo $module_url; ?>" class="btn btn-purple">Kembali</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if (isset($script_add)) : ?>
    <?php echo $script_add ?>
<?php endif ?>