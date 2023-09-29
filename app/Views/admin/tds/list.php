<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <?php
            if (isset($alert_msg) && !empty($alert_msg)) {
                foreach ($alert_msg as $data) {
            ?>
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </symbol>
                    </svg>
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div>
                            <?php echo ucfirst($data->alert_name); ?>
                            <button type="button" class="btn-close" aria-label="Close" data-row_id="<?php echo $data->id; ?>"></button>
                        </div>
                    </div>
            <?php  }
            } ?>
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-md-1">
                    <a href="<?= (base_url(ADMIN_PATH . '/tds/add')); ?>">
                        <button type="button" class="btn btn-primary btn-sm butn-back text-white"><?php echo 'Add New'; ?></button>
                    </a>
                </div>
            </div>
            <hr>
            <div class="list-label">
                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> year </th>
                            <th> Type </th>
                            <th> Filed Date </th>
                            <th> paid Date </th>
                            <th> Amount </th>
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

<?= $this->endSection(); ?>


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
        var url = "<?php echo base_url(); ?>admin/tds/list_ajax";
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
                    data: 'year'
                },
                {
                    data: 'type'
                },
                {
                    data: 'filed_date'
                },
                {
                    data: 'paid_date'
                },
                {
                    data: 'amount'
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
                                    icon: 'success',
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
                                    icon: 'error',
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
                                    icon: 'success',
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
                                    icon: 'error',
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

        $('body').on("click", ".btn-close", function() {
            var id = $(this).attr('data-row_id');
            var url = "<?php echo base_url(); ?>admin/tds/alert_close";
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    'id': id,
                },
                dataType: 'json',
                success: function(res) {
                    if (res['status'] == 1) {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>