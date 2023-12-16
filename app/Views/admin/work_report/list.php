<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
if ((isset($_GET['employee_name'])) && ($_GET['employee_name'] != '')) {
    $employee_id = $_GET['employee_name'];
} else {
    $employee_id = '';
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
                            <p> Employee Name :</p>
                            <select name="employee_name" id="employee_name" class=" form-control">
                                <option value="">Select</option>
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
                            <input type="button" id="FilterBtns2" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                        </div>
                    </form>

                </div>
                <hr>
            </div>
            <hr>
            <div class="list-label">

                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> S.No </th>
                            <th> Employee Name </th>
                            <th> Date </th>
                            <th> Project and Task Details </th>
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
        var qryString2 = get_employee_strings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>admin/work_report/list_ajax?" + qryString;
        } else if (qryString2 != '') {
            var url = "<?php echo base_url(); ?>admin/work_report/list_ajax?" + qryString2;
        } else {
            var url = "<?php echo base_url(); ?>admin/work_report/list_ajax";
        }
        // var url = "<?php echo base_url(); ?>admin/work_report/list_ajax";
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [1, "asc"],
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
                    targets: []
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
                    data: 'employee_name'
                },
                {
                    data: 'date'
                },
                {
                    data: 'proj_task_dts'
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
                window.location.href = "<?php echo base_url(); ?>admin/work_report/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/work_report/list";
            }
        });

        function get_employee_strings() {
            var qryString = '';
            if ($('#employee_name').val() != '') {
                qryString += '&employee_name=' + $('#employee_name').val();
            }
            return qryString;
        }

        $('#FilterBtns2').on('click', function() {
            var qryString = get_employee_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>admin/work_report/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/work_report/list";
            }
        });
    });
</script>
<?= $this->endSection() ?>