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
if ((isset($_GET['employee_name'])) && ($_GET['employee_name'] != '')) {
    $employee_id = $_GET['employee_name'];
} else {
    $employee_id = '';
} ?>
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
                            <p> Date Range :</p>
                            <input type="text" class="form-control required" name="daterange" id="datepicker" placeholder="Enter date Range" value="<?php echo $daterange; ?>">
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Employee Name :</p>
                            <select name="employee_name" id="employee_name" class=" form-control">
                                <option value="">select</option>
                                <?php foreach ($employee_details as $key => $value) {
                                    $selected = '';
                                    if (isset($employee_id) && $employee_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->first_name) . ' ' . ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
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
                            <th> Employee Name </th>
                            <th> Employee Email </th>
                            <th> Attendance Date </th>
                            <th> Working Hours (HH:MM:SS)</th>
                            <th> Break Hours (HH:MM:SS)</th>
                            <th> Total Work Hours (HH:MM:SS)</th>
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
            var url = "<?php echo base_url(); ?>admin/report/list_ajax?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>admin/report/list_ajax";
        }
        // var url = "<?php echo base_url(); ?>admin/report/list_ajax";
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
                    data: 'employee_name'
                },
                {
                    data: 'employee_email'
                },
                {
                    data: 'att_current_date'
                },
                {
                    data: 'id'
                },
                {
                    data: 'reason'
                },
                {
                    data: 'att_current_time'
                },
            ]
        });

        $("#datepicker").datepicker();

        function get_filter_strings() {
            var qryString = '';
            if ($('#datepicker').val() != '') {
                qryString += '&daterange=' + $('#datepicker').val();
            }
            if ($('#employee_name').val() != '') {
                qryString += '&employee_name=' + $('#employee_name').val();
            }
            return qryString;
        }
        $('#FilterBtns').on('click', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>admin/report/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/report/list";
            }
        });
    });
</script>
<?= $this->endSection() ?>