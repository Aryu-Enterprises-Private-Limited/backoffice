<!-- Start content -->
<?= $this->extend('employee_layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title;  ?></h3>
                </div>
            </div>


            <div class="create-label">
                <form id="attendance_form" method="post" action="<?= (base_url(EMPLOYEE_PATH . '/attendance/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Current Date&Time </label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input time_option required" name="curr_date_time" id="curr_date_time">
                                <option id='ct5'> </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Reason <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <?php
                            $login_hs = '';
                            $break_i = 'hidden';
                            $break_o = 'hidden';
                            $logout_hs = 'hidden';
                            $oth = 'hidden';
                            if (isset($att_details) && !empty($att_details)) {
                                foreach ($att_details as $details) {
                                    // echo $att_details[0]->reason;die;
                                    if (isset($details->reason) && $details->reason == 'login') {
                                        $login_hs = 'hidden';
                                        $break_o = '';
                                        $oth = '';
                                        // $break_i = '';
                                        $logout_hs = '';
                                        if (isset($att_details[0]->reason) && $att_details[0]->reason == 'break_out') {
                                            $login_hs = 'hidden';
                                            $break_i = '';
                                            $break_o = 'hidden';
                                            $logout_hs = 'hidden';
                                            $oth = 'hidden';
                                        }
                                        if (isset($att_details[0]->reason) && $att_details[0]->reason == 'break_in') {
                                            $login_hs = 'hidden';
                                            $break_i = 'hidden';
                                            $break_o = '';
                                        }
                                        if (isset($att_details[0]->reason) && $att_details[0]->reason == 'logout') {
                                            $login_hs = 'hidden';
                                            $break_i = 'hidden';
                                            $break_o = 'hidden';
                                            $logout_hs = 'hidden';
                                            $oth = 'hidden';
                                        }
                                    }
                                }
                            } ?>
                            <select class="form-select form-control create-input" name="reason" id="reason" required>
                                <option value=""> Select </option>
                                <option value="login" class="<?php if (isset($login_hs)) {
                                                                    echo $login_hs;
                                                                } ?>">Login</option>
                                <option value="logout" class="<?php if (isset($logout_hs)) {
                                                                    echo $logout_hs;
                                                                } ?>">Logout</option>
                                <option value="break_out" class="<?php if (isset($break_o)) {
                                                                        echo $break_o;
                                                                    } ?>">Break Out</option>
                                <option value="break_in" class="<?php if (isset($break_i)) {
                                                                    echo $break_i;
                                                                } ?>">Break In</option>
                                <option value="others" class="<?php if (isset($oth)) {
                                                                    echo $oth;
                                                                } ?>">Others</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Submit</button>
                </form>
            </div>


            <div class="container-fluid mt-4">
                <div class="card create-box">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-11">
                                <h3><?= $atttitle; ?></h3>
                            </div>
                        </div>
                        <hr>
                        <div class="list-label">
                            <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th> <?php echo 'Employee Name' ?></th>
                                        <th> <?php echo 'Employee Email' ?></th>
                                        <th> <?php echo 'Attendance Date' ?></th>
                                        <th> <?php echo 'Attendance Time' ?></th>
                                        <th> <?php echo 'Reason'; ?> </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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




<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
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
<script type="text/javascript">
    $(function() {
        var url = "<?php echo base_url(); ?>employee/attendance/list_ajax";
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [3, "desc"],
            columnDefs: [{
                    orderable: false,
                    targets: [0, -1, -2, ]
                },
                {
                    responsivePriority: 1,
                    targets: [1]
                },
                {
                    responsivePriority: 2,
                    targets: [4]
                }
            ],
            'pageLength': 10,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            ajax: {
                url: url,
                dataFilter: function(data) {
                    var json = jQuery.parseJSON(data);
                    return JSON.stringify(json); // return JSON string
                }
            },
            'columns': [{
                    data: 'employee_name'
                },
                {
                    data: 'employee_email'
                },
                {
                    data: 'att_current_date'
                },
                {
                    data: 'att_current_time'
                },
                {
                    data: 'reason'
                },

            ]
        });






        $(".sbmtBtn").click(function(evt) {
            // e.preventDefault();
            if ($('#attendance_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#attendance_form').submit();
            }
        });

    });


    function display_ct5() {
        var x = new Date()
        var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
        var hours = x.getHours();
        hours = hours % 12;
        hours = hours ? hours : 12;
        // var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
        // x1 = '0' + x1 + " - " + x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds() + " " + ampm;

        month = ((x.getMonth() + 1) < 10 ? "0" : "") + (x.getMonth() + 1);
        date = (x.getDate() < 10 ? "0" : "") + x.getDate();
        year = (x.getFullYear() < 10 ? "0" : "") + x.getFullYear();

        x1 = month + "/" + date + "/" + year;

        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (x.getMinutes() < 10 ? "0" : "") + x.getMinutes();
        seconds = (x.getSeconds() < 10 ? "0" : "") + x.getSeconds();
        x1 = x1 + " - " + hours + ":" + minutes + ":" + seconds + " " + ampm;

        document.getElementById('ct5').innerHTML = x1;
        // document.getElementsByClassName('ct5').innerHTML = x1;
        display_c5();
    }

    function display_c5() {
        var refresh = 100;
        mytime = setTimeout('display_ct5()', refresh)
    }
    display_c5()
</script>

<?= $this->endSection() ?>