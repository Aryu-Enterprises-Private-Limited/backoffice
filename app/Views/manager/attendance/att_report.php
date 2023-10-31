<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php
if ((isset($_GET['month'])) && ($_GET['month'] != '')) {
    $date = $_GET['month'];
} else {
    $date = date("Y-m");
}
if ((isset($_GET['year'])) && ($_GET['year'] != '')) {
    $year = $_GET['year'];
} else {
    $year = '';
}

?>
<style>
    .hide-datecalendar>.ui-datepicker-calendar {
        display: none !important;
    }

    .hide-monthcalendar .ui-datepicker-month {
        display: none !important;
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-5"><?= $title; ?></h2>
        <div class="row">
            <div class="col-sm-3">
                <div class="row form-group ">
                    <label class="col-sm-6 mt-2">Select Month:</label>
                    <input type="month" id="month_filter" value="<?= $date; ?>" class="form-control col-sm-3 mt-2 len mx-2">
                </div>
            </div>
            <div class="col-sm-6">
                <form autocomplete="off">
                    <div class="col-lg-5 float-start m-filter me-1">
                        <p> Year Range :</p>
                        <input type="text" class="form-control required" name="yearrange" id="yearpicker" placeholder="Enter Year Range" readonly value="<?= $year; ?>">
                    </div>
                    <div class="col-lg-2 float-start">
                        <p>&nbsp;</p>
                        <input type="button" id="FilterBtns3" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                    </div>
                </form>
            </div>
            <p class="text-end col-sm-3 mt-2">
                <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-info" aria-hidden="true"></i>
                    category
                </button>
            </p>
        </div>
        <div class="collapse mt-4" id="collapseExample">
            <!-- <div class="card card-body"> -->
            <table id="" class="table  table-bordered ">
                <thead>
                    <tr>
                        <th> Category Name </th>
                        <th> category code </th>
                        <th> Category Value </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($att_cat_details)) {
                        foreach ($att_cat_details as $data) {
                            echo '<tr>';
                            echo '<td>' . $data->category_name . '</td>';
                            echo '<td>' . $data->category_code . '</td>';
                            echo '<td>' . $data->category_value . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <!-- </div> -->
        </div>
        <hr>

        <div class="card mb-4">
            <div class="card-body">
                <div id="ajax_append">
                    <div class="table-responsive tableFixHead" id="myHeader">
                        <table id="displayDataTbl" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>No. of present days</th>
                                    <?php
                                    if (isset($att_cat_details)) {
                                        foreach ($att_cat_details as $date) {
                                            echo '<th>' . $date->category_code . '</th>';
                                        }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($att_report as $key => $employee) {
                                    echo '<tr>';
                                    echo '<td>' . $employee['first_name'] . ' ' . $employee['last_name'] . '</td>';
                                    echo '<td>' . $employee['employeeid'] . '</td>';
                                    echo '<td>' . $employee['att_Counts'] . '</td>';
                                    if (isset($att_cat_details)) {
                                        foreach ($att_cat_details as $date) {
                                            echo '<td>';
                                            if (isset($employee['emp_tracker'][$date->category_code])) {
                                                echo $employee['emp_tracker'][$date->category_code];
                                            } else {
                                                echo '-';
                                            }
                                            echo '</td>';
                                        }
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php echo view(MANAGER_PATH . '/templates/footer'); ?>
</div>
</div>

<?php
echo view(MANAGER_PATH . '/templates/scripts');
?>
<script>
    $(function() {
        $("#yearpicker").datepicker({
            dateFormat: "yy",
            changeMonth: false, // Show month dropdown
            changeYear: true,
            showButtonPanel: true,
            beforeShow: function(input) {
                $(input).datepicker("widget").addClass('hide-datecalendar');
                $(input).datepicker("widget").addClass('hide-monthcalendar');
            },
            onClose: function(dateText, inst) {
                // $('#monthpicker').datepicker( "refresh" );
                $(this).datepicker("widget").removeClass('hide-datecalendar');
                $(this).datepicker("widget").removeClass('hide-monthcalendar');
                $(this).datepicker("widget").attr('style', 'display: none;');
            },
            onChangeMonthYear: function(year, month, inst) {
                $(this).val(year);
            },
        });




        function get_filter_strings() {
            var qryString = '';
            if ($('#month_filter').val() != '') {
                qryString += '&month=' + $('#month_filter').val();
            }
            return qryString;
        }
        $("#month_filter").on('change', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>manager/att_report/list?" + qryString;;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/att_report/list";
            }
        });


        function get_filter_yearstrings() {
            var qryString3 = '';
            if ($('#yearpicker').val() != '') {
                qryString3 += '&year=' + $('#yearpicker').val();
            }
            return qryString3;
        }

        $('#FilterBtns3').on('click', function() {
            var qryString3 = get_filter_yearstrings();
            if (qryString3 != '') {
                window.location.href = "<?php echo base_url(); ?>manager/att_report/list?" + qryString3;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/att_report/list";
            }
        });

    });
</script>