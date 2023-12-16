<?= $this->extend('layout') ?>
<?php
if (isset($_GET['month']) && $_GET['month'] != '') {
    $date = $_GET['month'];
    $firstDayOfMonth = date('Y-m-01', strtotime($date));
    $dateTime = new DateTime($date);
    $monthInLetters = $dateTime->format('F');
} else {
    $date = date("Y-m");
    $firstDayOfMonth = date('Y-m-01', strtotime($date));
    $dateTime = new DateTime($date);
    $monthInLetters = $dateTime->format('F');
}
?>
<?= $this->section('content') ?>

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
                        <input type="month" id="month_filter" value="<?= $date ?>" class="form-control col-sm-5 len">
                    </div>
                </div>
            </div>

            <hr>
            <form id="payroll_form" method="post" action="<?= (base_url(ADMIN_PATH . '/payroll_gen/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                <div class="list-label">
                    <div class="card-body">
                        <div id="ajax_append">
                            <div class="table-responsive tableFixHead" id="myHeader">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th> S.No </th>
                                            <th> Employee Name </th>
                                            <th> Salary </th>
                                            <th> No.of days Present </th>
                                            <th> No.of days Absent </th>
                                            <th> Take Home </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" name="date" value="<?php if (isset($firstDayOfMonth)) echo $firstDayOfMonth; ?>">
                                        <input type="hidden" name="month" value="<?php if (isset($monthInLetters)) echo $monthInLetters; ?>">
                                        <?php
                                        if (!empty($employee_dts)) {
                                            $x = 1;
                                            foreach ($employee_dts as $item) { ?>

                                                <input type="hidden" name="id[]" value="<?php if (isset($item->id)) echo $item->id; ?>">
                                                <input type="hidden" name="employee_name[]" value="<?php if (isset($item->first_name)) echo $item->first_name . ' ' . $item->last_name; ?>">
                                                <?php echo "<tr>";
                                                echo "<td> " . $x . "</td>";
                                                echo '<td><a href="/' . ADMIN_PATH . '/employee/view/' . $item->id . '" class="text-decoration-none">' . ucfirst($item->first_name) . ' ' . ucfirst($item->last_name) . '</a></td>';

                                                // Find the corresponding pay details for the employee
                                                $employeePayDetails = array_filter($pay_details, function ($pay) use ($item) {
                                                    return $pay->employee_id == $item->id;
                                                });

                                                if (!empty($employeePayDetails)) {
                                                    foreach ($employeePayDetails as $payDetail) {
                                                        $one_day_sal = $payDetail->month_sal / $month_totl_days;
                                                ?>
                                                        <input type="hidden" name="month_sal[]" value="<?php if (isset($payDetail->month_sal)) echo $payDetail->month_sal; ?>">
                                                <?php echo "<td> " . $payDetail->month_sal . "</td>";
                                                    }
                                                } else {
                                                    echo "<td> - </td>";
                                                }

                                                // Find the corresponding employee tracking details
                                                $employeeTrackingDetails = array_filter($emp_track_dts, function ($employee) use ($item) {
                                                    return $employee->employee_id == $item->id;
                                                });

                                                // Initialize counts
                                                $presentCount = 0;
                                                $absentCount = 0;

                                                // Calculate counts
                                                foreach ($employeeTrackingDetails as $employee) {
                                                    // Decode the 'date' field
                                                    $dates = json_decode($employee->date, true);

                                                    // Count the occurrences of 'P' and 'A'
                                                    $presentCount += count(array_filter($dates, function ($status) {
                                                        // return $status === 'P';
                                                        return in_array($status, ['P', 'CL', 'CO', 'WO', 'WFH', 'PH']);
                                                    }));

                                                    $absentCount += count(array_filter($dates, function ($status) {
                                                        return $status === 'A';
                                                    }));
                                                } ?>
                                                <input type="hidden" name="present_cnt[]" value="<?php if (isset($presentCount)) echo $presentCount; ?>">
                                                <input type="hidden" name="absent_cnt[]" value="<?php if (isset($absentCount)) echo $absentCount; ?>">
                                                <input type="hidden" name="take_home[]" value="<?php if (isset($one_day_sal)) echo round($one_day_sal * $presentCount); ?>">
                                        <?php   // Output the counts for each employee
                                                $take_home = $one_day_sal * $presentCount;
                                                echo "<td> " . $presentCount . "</td>";
                                                echo "<td> " . $absentCount . "</td>";
                                                // Add other columns as needed

                                                echo "<td> " . number_format(round($take_home, 2)) . "</td>";

                                                echo "</tr>";
                                                $x++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No Records Found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn butn-submit text-white sbmtBtn <?= $disable_btn; ?>"> Generate </button>
                        </div>
                    </div>

            </form>
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
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
        // $(".sbmtBtn").click(function(evt) {
        //     if ($('#payroll_form').valid()) {
        //         $('#sbmtBtn').attr("disabled", true);
        //         $('#payroll_form').submit();
        //     }
        // });
        const buttons = document.querySelectorAll('.disableMe');

        // Loop through each button and disable it
        buttons.forEach(button => {
            button.disabled = true;
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
                window.location.href = "<?php echo base_url(); ?>admin/payroll_gen/generate_payroll?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/payroll_gen/generate_payroll";
            }
        });


        $(document).on("click", ".sbmtBtn", function(evt) {
            var act_url = $(this).attr('data-act_url');
            var row_id = $(this).attr('data-row_id');
            var stsmode = $(this).attr('data-stsmode');
            var verify_status = $(this).attr('data-status');
            var mainEvt = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to Generate this Month Salary!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    if ($('#payroll_form').valid()) {
                        $('#sbmtBtn').attr("disabled", true);
                        $('#payroll_form').submit();
                    }
                    // $.ajax({
                    //     type: 'post',
                    //     url: act_url,
                    //     data: {
                    //         'record_id': row_id,
                    //         'mode': stsmode,
                    //         'verify_status': verify_status
                    //     },
                    //     dataType: 'json',
                    //     success: function(res) {
                    //         $('.btn-success').removeAttr("disabled");
                    //         if (res.status == '1') {
                    //             Swal.fire({
                    //                 title: "Status Changed!",
                    //                 icon: 'success',
                    //                 text: res.response,
                    //                 type: "success"
                    //             });
                    //             if (stsmode == '0') {
                    //                 mainEvt.attr('data-stsmode', '1');
                    //                 mainEvt.html('<button type="button" class="btn btn-danger btn-sm waves-effect waves-light">Inactive</button>');
                    //             } else if (stsmode == '1') {
                    //                 mainEvt.attr('data-stsmode', '0');
                    //                 mainEvt.html('<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Active</button>');
                    //             } else {
                    //                 $('.drRideBox').hide();
                    //             }
                    //             //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                    //         } else {
                    //             Swal.fire({
                    //                 title: "Error",
                    //                 icon: 'error',
                    //                 text: res.response,
                    //                 type: "error"
                    //             });
                    //         }
                    //         if (res.status == '00') {
                    //             setTimeout(function() {
                    //                 location.reload();
                    //             }, 1500);
                    //         }
                    //     }
                    // });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>