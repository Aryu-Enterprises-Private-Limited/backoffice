<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['month'])) && ($_GET['month'] != '')) {
    $date = $_GET['month'];
} else {
    $date = date("Y-m");
}

?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-sm-4">
                    <div class="row form-group ">
                        <label class="col-sm-3 mt-2">Select Month:</label>
                        <input type="month" id="month_filter" value="<?= $date; ?>" class="form-control col-sm-5 len">
                    </div>
                </div>
            </div>

            <hr>
            <div class="list-label">
                <div class="card-body">
                    <div id="ajax_append">
                        <div class="table-responsive tableFixHead" id="myHeader">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <?php foreach ($dates as $date) : ?>
                                            <th><?= date('j/m', strtotime($date)); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    if (is_array($emp_details)) {
                                        // Create a mapping of date information
                                        $dateCategoryDataMap = [];
                                        foreach ($att_tracker_details as $data_details) {
                                            $dateCategoryData = json_decode($data_details->date, true);
                                            $dateCategoryDataMap[$data_details->employee_id] = $dateCategoryData;
                                        }

                                        foreach ($emp_details as $data) {
                                            echo "<tr>";
                                            echo "<td>" . $data->email . "</td>";

                                            foreach ($dates as $dt) {
                                                $dateCategoryData = $dateCategoryDataMap[$data->id] ?? [];

                                                echo '<td><select class="form-select select-input SelectChange' . $data->id . '" name="category_details" >';
                                                foreach ($att_category_details as $category) {
                                                    // Check if the category code exists for the current date
                                                    if (isset($dateCategoryData[$dt]) && $dateCategoryData[$dt] == $category->category_code) {
                                                        // If it matches, mark it as selected
                                                        echo "<option value='$category->category_code,$data->email,$data->id,$dt' 
                            data-employee_email='$data->email' data-category=$category->category_code 
                            data-employee_id='$data->id' data-date=$dt selected> " . ucfirst($category->category_code) . "</option>";
                                                    } else {
                                                        echo "<option value='$category->category_code,$data->email,$data->id,$dt' 
                            data-employee_email='$data->email' data-category=$category->category_code 
                            data-employee_id='$data->id' data-date=$dt> " . ucfirst($category->category_code) . "</option>";
                                                    }
                                                }
                                                echo '</select></td>';
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
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
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

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
            var url = "<?php echo base_url(); ?>admin/employee_tracker/list?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>admin/employee_tracker/list";
        }

        $.ajax({
            url: url,
            // type: "post",
            data: {

            },

        });

        function get_filter_strings() {
            var qryString = '';
            if ($('#month_filter').val() != '') {
                qryString += '&month=' + $('#month_filter').val();
            }
            return qryString;
        }
        $("#month_filter").on('change', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>admin/employee_tracker/list?" + qryString;;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/employee_tracker/list";
            }
        });
    });



    $(document).ready(function() {
        $('select.select-input').on('change', function() {
            var selectedSelect = $(this);

            var parentRow = selectedSelect.closest('tr');

            var selectsInRow = parentRow.find('select.select-input');

            var selectedValues = [];

            selectsInRow.each(function() {
                selectedValues.push($(this).val());
            });

            var splitValues = [];

            selectedValues.forEach(function(value) {
                var parts = value.split(',');
                splitValues.push(parts);
            });

            // console.log(splitValues);
            var jsonSplitValues = JSON.stringify(splitValues);
            var url = "<?php echo base_url(); ?>admin/employee_tracker/update";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    splitValues: jsonSplitValues
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response.status);
                    if (response.status == 1) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>