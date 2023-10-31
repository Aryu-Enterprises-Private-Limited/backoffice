<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['month'])) && ($_GET['month'] != '')) {
    $date = $_GET['month'];
} else {
    $date = date("Y-m");
}

?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-sm-4">
                    <div class="row form-group ">
                        <label class="col-sm-3 mt-2">Select Month:</label>
                        <input type="month" id="month_filter" value="<?= $date; ?>" class="form-control col-sm-5 len">
                    </div>
                </div>
            </div>

            <hr>
            <div class="list-label">
                <div class="card-body">
                    <div id="ajax_append">
                        <div class="table-responsive tableFixHead" id="myHeader">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <?php
                                        foreach ($dates as $date) : ?>
                                            <th><?= date('j/m D', strtotime($date)); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($att_details)) {
                                        foreach ($att_details as $item) {
                                            $hrs = $item->concatenated_hrs;
                                            $seconds = explode("~", $hrs);

                                            $dt = $item->concatenated_date;
                                            $att_dt  = explode("~", $dt);

                                            echo "<tr>";
                                            echo "<td> " . $item->employee_email . "</td>";

                                            if (is_array($dates)) {
                                                foreach ($dates as $dt) {
                                                    // print_r($att_dt);
                                                    // print_r($dt);
                                                    // if (isset($att_dt[$x]) && $dt == $att_dt[$x]) {
                                                    if (in_array($dt, $att_dt)) {
                                                        $total_hrs = gmdate("H:i", $seconds[$x]);
                                                        list($hours, $minutes) = explode(":", $total_hrs);

                                                        // Format the time duration
                                                        $total_hrs = sprintf("%02d hr %02d mins", $hours, $minutes);
                                                        echo "<td> " . $total_hrs  . "</td>";
                                                        $x++;
                                                    } else {
                                                        echo "<td> - </td>";
                                                        $x = 0;
                                                    }
                                                    // echo $counter;
                                                }
                                            } else {
                                                echo "Invalid data format";
                                            }
                                            echo "</tr>";
                                        }
                                    } else if (!empty($emp_details) && empty(($att_details))) {
                                        // if (!empty($emp_details)) {
                                        foreach ($emp_details as $item) {
                                            echo "<tr>";
                                            echo "<td> " . ucfirst($item->email) . "</td>";
                                            if (is_array($dates)) {
                                                $att_dt = array();
                                                foreach ($dates as $dt) {
                                                    // print_r($att_dt);
                                                    // print_r($dt);
                                                    // if (isset($att_dt[$x]) && $dt == $att_dt[$x]) {
                                                    if (in_array($dt, $att_dt)) {
                                                        $total_hrs = gmdate("H:i", $seconds[$x]);
                                                        list($hours, $minutes) = explode(":", $total_hrs);

                                                        // Format the time duration
                                                        $total_hrs = sprintf("%02d hr %02d mins", $hours, $minutes);
                                                        echo "<td> " . $total_hrs  . "</td>";
                                                        $x++;
                                                    } else {
                                                        echo "<td>  </td>";
                                                        $x = 0;
                                                    }
                                                    // echo $counter;
                                                }
                                            }
                                            echo "</tr>";
                                        }
                                    } else { ?>
                                        <td colspan="33">No Records Found</td>
                                    <?php   }
                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (session('success_message')) : ?>
    <script>
        toastr.success('<?= session('success_message') ?>');
    </script>
<?php endif; ?>
<?php if (session('error_message')) : ?>
    <script>
        toastr.error('<?= session('error_message') ?>');
    </script>
<?php endif; ?>
<script>
    $(function() {
        var qryString = get_filter_strings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>admin/report/monthly_list?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>admin/report/monthly_list";
        }

        $.ajax({
            url: url,
            // type: "post",
            data: {

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
                window.location.href = "<?php echo base_url(); ?>admin/report/monthly_list?" + qryString;;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/report/monthly_list";
            }
        });

    });
</script>
<?= $this->endSection() ?>