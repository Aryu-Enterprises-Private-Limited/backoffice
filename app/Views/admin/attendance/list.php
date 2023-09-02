<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['daterange'])) && ($_GET['daterange'] != '')) {
    $daterange = $_GET['daterange'];
} else {
    $daterange = '';
} ?>
<div class="container-fluid mt-4">

    <div class="card create-box">
        <div class="card-body">
            <div class="row">
            <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                    <!-- <hr> -->
                    <!-- <div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Range</label>
                                <input type="text" class="form-control required" id="datepicker" name="daterange" placeholder="Enter date Range" value="<?php echo $daterange;
                                                                                                                                                        ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="FilterBtns" class="btn btn-primary btn-sm"><i class="fi fi-rr-filter" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div> -->

                    <div class="col-lg-12 p-2 my_t">
                        <form autocomplete="off">
                            <div class="col-lg-2 float-start m-filter me-1">
                                <p> Date Range :</p>
                                <input type="text" class="form-control required" name="daterange" id="datepicker"  placeholder="Enter date Range" value="<?php echo $daterange; ?>">
                            </div>
                            <div class="col-lg-2 float-start">
                                <p>&nbsp;</p>
                                <input type="button" id="FilterBtns" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                            </div>
                            <div class="col-lg-12 float-start" style="margin-top: -26px;margin-left: 522px;">

                            </div>
                        </form>
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
            if (qryString != '') {
                var url = "<?php echo base_url(); ?>admin/attendance/list_ajax?" + qryString;
            } else {
                var url = "<?php echo base_url(); ?>admin/attendance/list_ajax";
            }
            // var url = "<?php echo base_url(); ?>admin/attendance/list_ajax";
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

            $("#datepicker").datepicker();
            // $('input[name="daterange"]').on('apply.datepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            // });
            // $('input[name="daterange"]').on('cancel.datepicker', function(ev, picker) {
            //     $(this).val('');
            // });

            function get_filter_strings() {
                var qryString = '';
                if ($('#datepicker').val() != '') {
                    qryString += '&daterange=' + $('#datepicker').val();
                }
                return qryString;
            }
            $('#FilterBtns').on('click', function() {
                var qryString = get_filter_strings();
                if (qryString != '') {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list?" + qryString;;
                } else {
                    window.location.href = "<?php echo base_url(); ?>admin/attendance/list";
                }
            });
        });
    </script>
    <?= $this->endSection() ?>