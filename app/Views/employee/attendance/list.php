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
                    <h3><?= $title; ?></h3>
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
    });
</script>
<?= $this->endSection() ?>