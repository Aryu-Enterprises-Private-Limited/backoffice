<!-- Start content -->
<?= $this->extend('layout') ?>
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
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-md-1">
                    <a href="<?= (base_url(ADMIN_PATH . '/employee/add')); ?>">
                        <button type="button" class="btn btn-primary btn-sm butn-back text-white"><?php echo 'Add New'; ?></button>
                    </a>
                </div>

            </div>

            <hr>
            <div class="list-label">

                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> <?php echo 'Firstname' ?></th>
                            <th> <?php echo 'Lastname' ?></th>
                            <th> <?php echo 'Role' ?></th>
                            <th> <?php echo 'Department' ?></th>
                            <th> <?php echo 'DOB' ?></th>
                            <th> <?php echo 'Phone' ?></th>
                            <th> <?php echo 'Email' ?></th>
                            <th> <?php echo 'Address'; ?> </th>
                            <th> <?php echo 'Pin code'; ?> </th>
                            <th> <?php echo 'City'; ?> </th>
                            <th> <?php echo 'State'; ?> </th>
                            <th> <?php echo 'Blood group'; ?> </th>
                            <th> <?php echo 'Aadhar Number'; ?> </th>
                            <th> <?php echo 'Pan Number'; ?> </th>
                            <th> <?php echo 'Relationship'; ?> </th>
                            <th> <?php echo 'Relation Name'; ?> </th>
                            <th> <?php echo 'Relation Phone'; ?> </th>
                            <th> <?php echo 'Relation Email'; ?> </th>
                            <th> <?php echo 'Relation Address'; ?> </th>
                            <th> <?php echo 'Experience of work'; ?> </th>
                            <th> <?php echo 'Status'; ?> </th>
                            <th> <?php echo 'Action'; ?> </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" id="show_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="show_data">
                <!-- <p>Modal body text goes here.</p> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

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
        var url = "<?php echo base_url(); ?>admin/employee/list_ajax";
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [3, "desc"],
            columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, -1, -2, -3, -4, -5, -6, -7, -8, -9, -10, -11]
                },
                {
                    responsivePriority: 1,
                    targets: [1]
                },
                {
                    responsivePriority: 2,
                    targets: [0]
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
                    data: 'first_name'
                },
                {
                    data: 'last_name'
                },
                {
                    data: 'role_id'
                },
                {
                    data: 'department_id'
                },
                {
                    data: 'dob'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'email'
                },
                {
                    data: 'address'
                },
                {
                    data: 'pin_code'
                },
                {
                    data: 'city'
                },
                {
                    data: 'state'
                },
                {
                    data: 'blood_grp'
                },
                {
                    data: 'aadhar_no'
                },
                {
                    data: 'pan_no'
                },
                {
                    data: 'relationship'
                },
                {
                    data: 'r_name'
                },
                {
                    data: 'r_phone'
                },
                {
                    data: 'r_email'
                },
                {
                    data: 'r_address'
                },
                {
                    data: 'work_exp'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                },

            ]
        });

        const myModal = new bootstrap.Modal('#show_modal');

        $('body').on("click", ".v_btn", function() {
            var row_id = $(this).attr('data-row_id');
            var act_url = $(this).attr('data-act_url');
            $.ajax({
                type: 'post',
                url: act_url,
                data: {
                    'lms_id': row_id,
                },
                dataType: 'json',
                success: function(res) {
                    // console.log(res[0].id);
                    myModal.show();
                    $('#show_data').append(res);
                }
            });
        });
    });


    //$(document).on("click", ".stsconfirm", function() {
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
                //if (t.value == true) {
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
                            // setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            // location.reload();
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
    //});
</script>
<?= $this->endSection() ?>