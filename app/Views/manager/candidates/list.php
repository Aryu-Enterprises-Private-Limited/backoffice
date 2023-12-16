<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<?php
if ((isset($_GET['job_open'])) && ($_GET['job_open'] != '')) {
    $job_open = $_GET['job_open'];
} else {
    $job_open = '';
}
if ((isset($_GET['month'])) && ($_GET['month'] != '')) {
    $monthrange = $_GET['month'];
} else {
    $monthrange = '';
}
if ((isset($_GET['year'])) && ($_GET['year'] != '')) {
    $year = $_GET['year'];
} else {
    $year = '';
}

?>
<style>
    .hide-calendar>.ui-datepicker-calendar {
        display: none !important;
    }

    .hide-datecalendar>.ui-datepicker-calendar {
        display: none !important;
    }

    .hide-monthcalendar .ui-datepicker-month {
        display: none !important;
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-5"><?= $title; ?></h2>
        <hr>
        <div class="row">
            <div class="col-lg-12 p-2 my_t">
                <form autocomplete="off">
                    <div class="col-lg-2 float-start m-filter me-1">
                        <p> Job Opening :</p>
                        <select name="job_opening_id" id="job_opening_id" class=" form-control">
                            <option value="">Select</option>
                            <?php foreach ($job_open_opt as $key => $value) {
                                $selected = '';
                                if (isset($job_open) && $job_open == $value->id) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                    <?php echo ucfirst($value->jobs_name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-1 float-start">
                        <p>&nbsp;</p>
                        <input type="button" id="FilterBtns" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                    </div>
                </form>
                <form autocomplete="off">
                    <div class="col-lg-2 float-start m-filter me-1">
                        <p> Month Range :</p>
                        <input type="text" class="form-control required custom-datepicker" name="monthrange" id="monthpicker" placeholder="Enter month Range" readonly value="<?= $monthrange; ?>">
                    </div>

                    <div class="col-lg-1 float-start">
                        <p>&nbsp;</p>
                        <input type="button" id="FilterBtns2" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                    </div>
                </form>
                <form autocomplete="off">
                    <div class="col-lg-2 float-start m-filter me-1">
                        <p> Year Range :</p>
                        <input type="text" class="form-control required" name="yearrange" id="yearpicker" placeholder="Enter Year Range" readonly value="<?= $year; ?>">
                    </div>
                    <div class="col-lg-1 float-start">
                        <p>&nbsp;</p>
                        <input type="button" id="FilterBtns3" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
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
                            <th> Candidate Name </th>
                            <th> Contact Number </th>
                            <th> Email </th>
                            <th> Applied Date </th>
                            <th> Application Status </th>
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
        var qryString2 = get_filter_monthstrings();
        var qryString3 = get_filter_yearstrings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>manager/candidates/list_ajax?" + qryString;
        } else if (qryString2 != '') {
            var url = "<?php echo base_url(); ?>manager/candidates/list_ajax?" + qryString2;
        } else if (qryString3 != '') {
            var url = "<?php echo base_url(); ?>manager/candidates/list_ajax?" + qryString3;
        } else {
            var url = "<?php echo base_url(); ?>manager/candidates/list_ajax";
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
                    data: 'contact_no'
                },
                {
                    data: 'email'
                },
                {
                    data: 'date'
                },
                {
                    data: 'application_status_id'
                },
            ]
        });

        $("#monthpicker").datepicker({
            dateFormat: "M, yy", // Display only month and year
            changeMonth: true, // Show month dropdown
            changeYear: true,
            showButtonPanel: true,
            beforeShow: function(input) {
                $(input).datepicker("widget").addClass('hide-calendar');
            },
            onClose: function(dateText, inst) {
                // $('#monthpicker').datepicker( "refresh" );
                $(this).datepicker("widget").removeClass('hide-calendar');
                $(this).datepicker("widget").attr('style', 'display: none;');
            },
            onChangeMonthYear: function(year, month, inst) {
                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var formattedMonth = monthNames[month - 1];
                // Update the input field with the formatted date
                $(this).val(formattedMonth + "," + year);
            },

        });

        $("#yearpicker").datepicker({
            dateFormat: "yy",
            changeMonth: false, // Show month dropdown
            changeYear: true,
            showButtonPanel: true,
            beforeShow: function(input) {
                $(input).datepicker("widget").addClass('hide-datecalendar');
                $(input).datepicker("widget").addClass('hide-monthcalendar');
            },
            onClose: function(dateText, inst) {
                // $('#monthpicker').datepicker( "refresh" );
                $(this).datepicker("widget").removeClass('hide-datecalendar');
                $(this).datepicker("widget").removeClass('hide-monthcalendar');
                $(this).datepicker("widget").attr('style', 'display: none;');
            },
            onChangeMonthYear: function(year, month, inst) {
                $(this).val(year);
            },
        });

        function get_filter_strings() {
            var qryString = '';
            if ($('#job_opening_id').val() != '') {
                qryString += '&job_open=' + $('#job_opening_id').val();
            }
            return qryString;
        }

        function get_filter_monthstrings() {
            var qryString2 = '';
            if ($('#monthpicker').val() != '') {
                qryString2 += '&month=' + $('#monthpicker').val();
            }
            return qryString2;
        }

        function get_filter_yearstrings() {
            var qryString3 = '';
            if ($('#yearpicker').val() != '') {
                qryString3 += '&year=' + $('#yearpicker').val();
            }
            return qryString3;
        }
        $('#FilterBtns').on('click', function() {
            var qryString = get_filter_strings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list";
            }
        });

        $('#FilterBtns2').on('click', function() {
            var qryString2 = get_filter_monthstrings();
            if (qryString2 != '') {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list?" + qryString2;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list";
            }
        });

        $('#FilterBtns3').on('click', function() {
            var qryString3 = get_filter_yearstrings();
            if (qryString3 != '') {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list?" + qryString3;
            } else {
                window.location.href = "<?php echo base_url(); ?>manager/candidates/list";
            }
        });
    });
</script>