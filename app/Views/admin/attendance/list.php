<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
<link rel="stylesheet" href="/plugins/datatable/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['from_date'])) && ($_GET['from_date'] != '')) {
    $from_date = $_GET['from_date'];
} else {
    $from_date = '';
}
if ((isset($_GET['to_date'])) && ($_GET['to_date'] != '')) {
    $to_date = $_GET['to_date'];
} else {
    $to_date = '';
}

if ((isset($_GET['reason'])) && ($_GET['reason'] == 'login')) {
    $login = 'selected';
    $logout ='';
    $break_out ='';
    $break_in ='';
} else if((isset($_GET['reason'])) && ($_GET['reason'] == 'logout')) {
    $logout = 'selected';
    $login = '';
    $break_out ='';
    $break_in ='';
}else if((isset($_GET['reason'])) && ($_GET['reason'] == 'break_out')) {
    $break_out = 'selected';
    $logout = '';
    $login = '';
    $break_in ='';
}else if((isset($_GET['reason'])) && ($_GET['reason'] == 'break_in')) {
    $break_in = 'selected';
    $logout = '';
    $login = '';
    $break_out ='';
}else{
    $login = '';
    $logout ='';
    $break_out ='';
    $break_in ='';
}
 ?>
<div class="container-fluid mt-4">

    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-lg-12 p-2 my_t">
                    <form autocomplete="off">
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> From :</p>
                            <input type="text" class="form-control required" name="from_date" id="from_date" placeholder="Enter From date Range" value="<?php echo $from_date; ?>">
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> To :</p>
                            <input type="text" class="form-control required" name="to_date" id="to_date" placeholder="Enter To date Range" value="<?php echo $to_date; ?>">
                        </div>
                        <div class="col-lg-2 float-start">
                            <p>&nbsp;</p>
                            <input type="button" id="FilterBtns" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Reson :</p>
                            <select name="reason" id="reason" class=" form-control">
                                <option value="">Select</option>
                                    <option value="login" <?= $login; ?>> Login </option>
                                    <option value="logout" <?= $logout; ?>> Logout </option>
                                    <option value="break_out" <?= $break_out; ?>> Break Out </option>
                                    <option value="break_in" <?= $break_in; ?>> Break In </option>
                            </select>
                        </div>
                        <div class="col-lg-2 float-start">
                        <p>&nbsp;</p>
                            <input type="button" id="FilterBtns2" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                        </div>
                    </form>
                    
                </div>


                <hr>
                <div class="list-label">
                    <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th> <?php echo 'S.No' ?></th>
                                <th> <?php echo 'Attendance Date' ?></th>
                                <th> <?php echo 'Employee Name' ?></th>
                                <th> <?php echo 'Attendance Time' ?></th>
                                <th> <?php echo 'Reason'; ?> </th>
                                <th> <?php echo 'IP Address'; ?> </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>


    <?= $this->section('script') ?>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

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
    <script>
        $(function() {
            var qryString = get_filter_strings();
            var qryString2 = get_reason_filter_strings();
            if (qryString != '') {
                var url = "<?php echo base_url(); ?>admin/attendance/list_ajax?" + qryString;
            } else if (qryString2 != '') {
                var url = "<?php echo base_url(); ?>admin/attendance/list_ajax?" + qryString2;
            }else {
                var url = "<?php echo base_url(); ?>admin/attendance/list_ajax";
            }
            // var url = "<?php echo base_url(); ?>admin/attendance/list_ajax";
            var dataTbl = $("#displayDataTbl").DataTable({
                "scrollX": true,
                "aaSorting": [2, "asc"],
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
                        data: 's_no'
                    },
                    {
                        data: 'att_current_date'
                    },
                    {
                        data: 'employee_name'
                    },
                    // {
                    //     data: 'employee_email'
                    // },
                    {
                        data: 'att_current_time'
                    },
                    {
                        data: 'reason'
                    },
                    {
                        data: 'ip_address'
                    },
                ]
            });

            $("#from_date").datepicker();
            $("#to_date").datepicker();

            function get_filter_strings() {
                var qryString = '';
                if ($('#from_date').val() != '') {
                    qryString += '&from_date=' + $('#from_date').val();
                }
                if ($('#to_date').val() != '') {
                    qryString += '&to_date=' + $('#to_date').val();
                }
                return qryString;
            }
            $('#FilterBtns').on('click', function() {
                var qryString = get_filter_strings();
                if (qryString != '') {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list?" + qryString;
                } else {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list";
                }
            });

            function get_reason_filter_strings() {
                var qryString = '';
                if ($('#reason').val() != '') {
                    qryString += '&reason=' + $('#reason').val();
                }
                return qryString;
            }

            $('#FilterBtns2').on('click', function() {
                var qryString = get_reason_filter_strings();
                if (qryString != '') {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list?" + qryString;
                } else {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list";
                }
            });
        });
    </script>
    <?= $this->endSection() ?>