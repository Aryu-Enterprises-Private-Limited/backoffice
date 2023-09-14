<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['daterange'])) && ($_GET['daterange'] != '')) {
    $daterange = $_GET['daterange'];
} else {
    $daterange = '';
}
if ((isset($_GET['job_open'])) && ($_GET['job_open'] != '')) {
    $job_open = $_GET['job_open'];
} else {
    $job_open = '';
}
if ((isset($_GET['app_sts'])) && ($_GET['app_sts'] != '')) {
    $app_sts = $_GET['app_sts'];
} else {
    $app_sts = '';
}
if ((isset($_GET['int_sts'])) && ($_GET['int_sts'] != '')) {
    $int_sts = $_GET['int_sts'];
} else {
    $int_sts = '';
}
if ((isset($_GET['stage'])) && ($_GET['stage'] != '')) {
    $stage = $_GET['stage'];
} else {
    $stage = '';
}
if ((isset($_GET['stage'])) && ($_GET['stage'] != '')) {
    $stage = $_GET['stage'];
} else {
    $stage = '';
}
?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-md-1">
                    <a href="<?= (base_url(ADMIN_PATH . '/candidates/add')); ?>">
                        <button type="button" class="btn btn-primary btn-sm butn-back text-white"><?php echo 'Add New'; ?></button>
                    </a>
                </div>
                <div class="col-lg-12 p-2 my_t">
                    <form autocomplete="off">
                        <!-- <div class="col-lg-2 float-start m-filter me-1">
                            <p> Date Date :</p>
                            <input type="text" class="form-control required" name="daterange" id="datepicker" placeholder="Enter date Range" value="<?php echo $daterange; ?>">
                        </div> -->
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Job Opening :</p>
                            <select name="job_opening_id" id="job_opening_id" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($job_open_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($job_open) && $job_open == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->jobs_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Application Status :</p>
                            <select name="app_sts" id="app_sts" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($app_sts_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($app_sts) && $app_sts == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->app_status); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Interview Status :</p>
                            <select name="int_sts" id="int_sts" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($int_sts_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($int_sts) && $int_sts == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->interview_sts); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Stage :</p>
                            <select name="stage_id" id="stage_id" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($stage_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($stage) && $stage == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->stage_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- <div class="col-lg-2 float-start m-filter me-1">
                            <p> Reason for Rejection :</p>
                            <select name="rr_id" id="rr_id" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($rr_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($stage) && $stage == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->reason_for_rej); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div> -->
                        <div class="col-lg-2 float-start">
                            <p>&nbsp;</p>
                            <input type="button" id="FilterBtns" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                        </div>
                        <div class="col-lg-12 float-start" style="margin-top: -26px;margin-left: 522px;">

                        </div>
                    </form>
                </div>
            </div>

            <hr>
            <div class="list-label">
                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> Name </th>
                            <th> Date </th>
                            <th> Location </th>
                            <th> Contact No </th>
                            <th> Email </th>
                            <th> Job Opening </th>
                            <th> Application Status </th>
                            <th> Interview Status </th>
                            <th> Stage </th>
                            <th> Backgound Check </th>
                            <th> Source </th>
                            <th> Reason For Rejection </th>
                            <th> Reason </th>
                            <th> Created AT </th>
                            <th> Status </th>
                            <th> Action </th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


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
            var url = "<?php echo base_url(); ?>admin/candidates/list_ajax?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>admin/candidates/list_ajax";
        }
        // var url = "<?php echo base_url(); ?>admin/candidates/list_ajax";
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [1, "desc"],
            columnDefs: [{
                    orderable: false,
                    targets: [0]
                },
                {
                    responsivePriority: 1,
                    targets: [1]
                },
                {
                    responsivePriority: 2,
                    targets: [3]
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
                    data: 'name'
                },
                {
                    data: 'date'
                },
                {
                    data: 'location'
                },
                {
                    data: 'contact_no'
                },
                {
                    data: 'email'
                },
                {
                    data: 'job_opening_id'
                },
                {
                    data: 'application_status_id'
                },
                {
                    data: 'interview_status_id'
                },
                {
                    data: 'stage_id'
                },
                {
                    data: 'background_check'
                },
                {
                    data: 'source'
                },
                {
                    data: 'reason_rejection'
                },
                {
                    data: 'reason'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                },
            ]
        });

        $(document).on("click", ".stsconfirm", function(evt) {
            var act_url = $(this).attr('data-act_url');
            var row_id = $(this).attr('data-row_id');
            var stsmode = $(this).attr('data-stsmode');
            var verify_status = $(this).attr('data-status');
            var mainEvt = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to change the staus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id,
                            'mode': stsmode,
                            'verify_status': verify_status
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Status Changed!",
                                    text: res.response,
                                    type: "success"
                                });
                                if (stsmode == '0') {
                                    mainEvt.attr('data-stsmode', '1');
                                    mainEvt.html('<button type="button" class="btn btn-danger btn-sm waves-effect waves-light">Inactive</button>');
                                } else if (stsmode == '1') {
                                    mainEvt.attr('data-stsmode', '0');
                                    mainEvt.html('<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Active</button>');
                                } else {
                                    $('.drRideBox').hide();
                                }
                                //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click", ".delconfirm", function(evt) {
            var row_id = $(this).attr('data-row_id');
            var act_url = $(this).attr('data-act_url');
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to Delete the Record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "No, Cancel!",

            }).then((result) => {

                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: res.response,
                                    type: "success"
                                });
                                $('#' + row_id).remove();
                                setTimeout(function() {
                                    location.reload();
                                }, 2500);
                                //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });


                }
            })
        });

        function get_filter_strings() {
            var qryString = '';
            // if ($('#datepicker').val() != '') {
            //     qryString += '&daterange=' + $('#datepicker').val();
            // }
            if ($('#job_opening_id').val() != '') {
                qryString += '&job_open=' + $('#job_opening_id').val();
            }
            if ($('#app_sts').val() != '') {
                qryString += '&app_sts=' + $('#app_sts').val();
            }
            if ($('#int_sts').val() != '') {
                qryString += '&int_sts=' + $('#int_sts').val();
            }
            if ($('#stage_id').val() != '') {
                qryString += '&stage=' + $('#stage_id').val();
            }
            return qryString;
        }

        $('#FilterBtns').on('click', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>admin/candidates/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/candidates/list";
            }
        });
    });
</script>
<?= $this->endSection() ?>