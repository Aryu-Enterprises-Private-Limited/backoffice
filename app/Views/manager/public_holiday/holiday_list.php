<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-5"><?= $title .' - '. date("Y"); ?></h2>
        <hr>
        <div class="card mb-4">
            <div class="card-body">
                <!-- <table id="datatablesSimple"> -->
                <table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> Date </th>
                            <th> Day </th>
                            <th> Reason </th>
                        </tr>
                    </thead>
                    <!-- <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </tfoot> -->
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
        var url = "<?php echo base_url(); ?>manager/public_holiday/list_ajax";
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [0, "asc"],
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
            'columns': [
                {
                    data: 'date'
                },
                {
                    data: 'day'
                },
                {
                    data: 'reason'
                },
            ]
        });

       
    });
</script>

