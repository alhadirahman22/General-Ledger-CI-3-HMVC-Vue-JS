<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>


    <div class="container" style="margin-top: 10%;">

        <div class="row">
            <div class="col-md-4">
                <form action="<?= base_url('migrate/create_module') ?>" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Create Module
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control">
                            </div>
                            <?php
                            if ($feedback = $this->session->flashdata('feedback')) {
                                echo $feedback;
                            }
                            ?>
                        </div>
                        <div class="panel-footer">
                            <div class="">
                                <div class="text-right">
                                    <button class="btn btn-success">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            <div class="col-md-6">
                <form action="<?= base_url('migrate/create_page') ?>" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Create Page
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Module</label>
                                <select name="path" class="form-control">
                                    <?php foreach ($module as $item) {
                                        echo '<option value="' . $item . '">' . $item . '</option>';
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" />
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="controller" checked> Controller
                                </label>
                                <?php
                                if ($feedback = $this->session->flashdata('feedback_c')) {
                                    echo '<div>'.$feedback.'</div>';
                                }
                                ?>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="language" checked> Language
                                </label>
                                <?php
                                if ($feedback = $this->session->flashdata('feedback_l')) {
                                    echo '<div>'.$feedback.'</div>';
                                }
                                ?>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="model" checked> Model
                                </label>
                                <?php
                                if ($feedback = $this->session->flashdata('feedback_m')) {
                                    echo '<div>'.$feedback.'</div>';
                                }
                                ?>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="view"> View
                                </label>
                                <?php
                                if ($feedback = $this->session->flashdata('feedback_v')) {
                                    echo '<div>'.$feedback.'</div>';
                                }
                                ?>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <div class="">
                                <div class="text-right">
                                    <button class="btn btn-success">Create</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</body>

</html>