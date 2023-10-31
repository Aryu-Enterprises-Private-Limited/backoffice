<!-- Start content -->
<?= $this->extend('employee_layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3 class="text-center text-decoration-underline"><?= $title .' - '. $curr_yr; ?></h3>
                </div>
            </div>
            <div class="row mt-3">
                <div class="row col-md-2"></div>
                <div class="list-label col-md-8 flex-d justify-content-center">
                    <table class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="table-primary">
                                <th> Date </th>
                                <th> Day </th>
                                <th> Reason </th>
                                <!-- <th> Notes </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($holiday_list)) {
                                foreach ($holiday_list as $holiday_data) {
                                    $date = new DateTime($holiday_data->date);
                                    $dayOfWeek = $date->format('l');
                                    echo "<tr>";
                                    echo "<td> " . $holiday_data->date . "</td>";
                                    echo "<td> " . $dayOfWeek . "</td>";
                                    echo "<td> " . ucfirst($holiday_data->reason) . "</td>";
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

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<!-- Include jQuery UI -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

<!-- 
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->



<?= $this->endSection() ?>