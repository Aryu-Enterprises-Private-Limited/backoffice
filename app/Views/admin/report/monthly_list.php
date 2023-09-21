<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <!-- <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/schedule/list' ?>"><?php echo  'Schedule'; ?> </a>
                        </li>
                    </ol>
                    <hr> -->
                    <h3><?= $title; ?></h3>
                </div>

                <div class="col-sm-4">
                    <div class="row form-group ">
                        <label class="col-sm-3 mt-2">Select Month:</label>
                        <?php
                        // $year = date("Y", strtotime($filter_month));
                        // $month = date("m", strtotime($filter_month));
                        // $date = $year . '-' . $month;
                        $date = '';
                        ?>
                        <input type="month" id="month_filter" value="<?= $date; ?>" class="form-control col-sm-5 len">
                    </div>
                </div>
            </div>

            <!-- <div class="create-label"> -->
            <form id="schedule_form" method="post" action="<?= (base_url(ADMIN_PATH . '/schedule/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="date" name="date" value="<?php if (isset($dates)) echo $dates[0]; ?>">
                <div class="card-body">
                    <div id="ajax_append">
                        <div class="table-responsive tableFixHead" id="myHeader">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <?php foreach ($dates as $date) : ?>
                                            <th><?= date('j/m D', strtotime($date)); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($dataArray)) {
                                        foreach ($dataArray as $item) {
                                            echo "<tr>";
                                            echo "<td> " . $item->employee_email . "</td>";
                                            $workingHours = json_decode($item->daily_working_hrs);
                                            if (is_array($workingHours)) {
                                                // echo'<input type="text" name="id[]" value="'.$item->id.'">';
                                                foreach ($workingHours as $hour) {
                                                    echo '<td><input type="text" class="form-control create-inputs" name="sch_hrs[ ' . $item->employee_email . ',' . $item->employee_id . ',' . $item->id . '][]" id="" value="' . $hour . '" required></td>';
                                                }
                                            } else {
                                                echo "Invalid data format";
                                            }
                                            echo "</tr>";
                                        }
                                    } else {
                                        if(isset($emp_details)){

                                       
                                        foreach ($emp_details as $item) {
                                            echo "<tr>";
                                            echo "<td> " . $item->email . "</td>";
                                            for ($x = 1; $x <= count($dates); $x++) {
                                                echo '<td><input type="text" class="form-control create-inputs" name="sch_hrs[' . $item->email . ',' . $item->id . '][]" id="" value="9" required></td>';
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Save</button> -->
            </form>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#schedule_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#schedule_form').submit();
            }
        });


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