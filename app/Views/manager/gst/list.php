<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-5"><?= $title ; ?></h2>
        <hr>
        <div class="card mb-4">
            <div class="card-body">
                <!-- <table id="datatablesSimple"> -->
                <table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> S.No </th>
                            <th> Month </th>
                            <th> Filed Date </th>
                            <th> Reference No </th>
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
        var url = "<?php echo base_url(); ?>manager/gst/list_ajax";
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
            'columns': [
                {
                    data: 's_no'
                },
                {
                    data: 'month'
                },
                {
                    data: 'filed_date'
                },
                {
                    data: 'ref_no'
                },
            ]
        });

       
    });
</script>

