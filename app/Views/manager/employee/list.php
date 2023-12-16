<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<?php
if ((isset($_GET['status'])) && ($_GET['status'] == '1')) {
    $current = 'selected';
    $relieved ='';
} else if((isset($_GET['status'])) && ($_GET['status'] == '0')) {
    $relieved = 'selected';
    $current = '';
}else{
    $current = '';
    $relieved ='';
}

?>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-5"><?= $title; ?></h2>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <form autocomplete="off">
                    <div class="col-lg-5 float-start m-filter me-1">
                        <p> Status :</p>
                        <select name="status" id="employee_sts" class=" form-control">
                            <option value="">Select</option>
                            <option value="1" <?= $current; ?>>Current Employee</option>
                            <option value="0" <?= $relieved; ?>>Relieved Employee</option>
                        </select>
                    </div>
                    <div class="col-lg-3 float-start">
                        <p>&nbsp;</p>
                        <input type="button" id="FilterBtns" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                    </div>
                    
                </form>
            </div>

        </div>
        <div class="card mb-4 mt-3">
            <div class="card-body">
                <!-- <table id="datatablesSimple"> -->
                <table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> S.NO </th>
                            <th> Employee Name </th>
                            <th> Employee ID </th>
                            <th> Designation </th>
                            <th> Status </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php echo view(MANAGER_PATH . '/templates/footer'); ?>
</div>
</div>

<?php
echo view(MANAGER_PATH . '/templates/scripts');
?>

<script>
    $(function() {
        var qryString = get_filter_strings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>manager/employee/list_ajax?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>manager/employee/list_ajax";
        }
        // var url = "<?php echo base_url(); ?>manager/employee/list_ajax";
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
                    data: 'first_name'
                },
                {
                    data: 'employeeid'
                },
                // {
                //     data: 'email'
                // },
                {
                    data: 'role_id'
                },
                {
                    data: 'status'
                },
            ]
        });

        function get_filter_strings() {
            var qryString = '';
            if ($('#employee_sts').val() != '') {
                qryString += '&status=' + $('#employee_sts').val();
            }
            return qryString;
        }

        $('#FilterBtns').on('click', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>manager/employee/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/employee/list";
            }
        });
    });
</script>