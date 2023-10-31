<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
if ((isset($_GET['date'])) && ($_GET['date'] != '')) {
    $daterange = $_GET['date'];
} else {
    $daterange = '';
}
if ((isset($_GET['month'])) && ($_GET['month'] != '')) {
    $monthrange = $_GET['month'];
} else {
    $monthrange = '';
}
if ((isset($_GET['category'])) && ($_GET['category'] != '')) {
    $category = $_GET['category'];
} else {
    $category = '';
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
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <?php
            if (isset($alert_msg) && !empty($alert_msg)) {
                foreach ($alert_msg as $data) {
            ?>
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </symbol>
                    </svg>
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div>
                            <?php echo ucfirst($data->alert_name); ?>
                            <button type="button" class="btn-close" aria-label="Close" data-row_id="<?php echo $data->id; ?>"></button>
                        </div>
                    </div>
            <?php  }
            } ?>
            <div class="row">
                <div class="col-md-10">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-md-1">
                    <a id="export_all_user">
                        <button type="button" class="btn btn-primary  butn-back text-white"><?php echo 'Export'; ?></button>
                    </a>
                </div>
                <div class="col-md-1">
                    <a href="<?= (base_url(ADMIN_PATH . '/expense/add')); ?>">
                        <button type="button" class="btn btn-primary btn-sm butn-back text-white"><?php echo 'Add New'; ?></button>
                    </a>
                </div>

                <div class="col-lg-12 p-2 my_t">
                    <form autocomplete="off">
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Date Range :</p>
                            <input type="text" class="form-control required" name="daterange" id="datepicker" placeholder="Enter date Range" readonly value="<?= $daterange; ?>">
                        </div>
                        <div class="col-lg-1 float-start">
                            <p>&nbsp;</p>
                            <input type="button" id="FilterBtns1" class="btn btn-success btn-sm btn-bordered py-2 m-filter" value="Filter">
                        </div>
                    </form>
                    <form autocomplete="off">
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Month Range :</p>
                            <input type="text" class="form-control required custom-datepicker" name="monthrange" id="monthpicker" placeholder="Enter month Range" readonly value="<?= $monthrange; ?>">
                        </div>
                        <div class="col-lg-2 float-start m-filter me-1">
                            <p> Category :</p>
                            <select name="category" id="category" class=" form-control">
                                <option value="">select</option>
                                <?php
                                foreach ($cat_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($category) && $category == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected ?>>
                                        <?php echo ucfirst($value->category_name); ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
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

            <hr>
            <div class="list-label">

                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> Date </th>
                            <th> Invoice No </th>
                            <th> Category Name </th>
                            <th> Description </th>
                            <th> Amount </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr class="general_ttl">
                            <td colspan="3" style="text-align: right;"> General Total </th>
                            <td id="totalAmountElement" ></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
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
        var qryString = get_filter_datestrings();
        var qryString2 = get_filter_monthstrings();
        var qryString3 = get_filter_yearstrings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>admin/expense/list_ajax?" + qryString;
        } else if (qryString2 != '') {
            var url = "<?php echo base_url(); ?>admin/expense/list_ajax?" + qryString2;
        } else if (qryString3 != '') {
            var url = "<?php echo base_url(); ?>admin/expense/list_ajax?" + qryString3;
        } else {
            var url = "<?php echo base_url(); ?>admin/expense/list_ajax";
        }


        // var url = "<?php echo base_url(); ?>admin/expense/list_ajax";
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
                    targets: [4]
                }
            ],
            'pageLength': 25,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            ajax: {
                url: url,
                dataFilter: function(data) {
                    var json = jQuery.parseJSON(data);
                    $('#totalAmountElement').text(json.totalAmount);
                    return JSON.stringify(json); // return JSON string
                }
            },
            'columns': [{
                    data: 'date'
                },
                {
                    data: 'invoice_no'
                },
                {
                    data: 'category_name'
                },
                {
                    data: 'description'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                },
            ]
        });

        $(document).on("click", ".stsconfirm", function(evt) {
            var act_url = $(this).attr('data-act_url');
            var row_id = $(this).attr('data-row_id');
            var stsmode = $(this).attr('data-stsmode');
            var verify_status = $(this).attr('data-status');
            var mainEvt = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to change the staus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id,
                            'mode': stsmode,
                            'verify_status': verify_status
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Status Changed!",
                                    icon: 'success',
                                    text: res.response,
                                    type: "success"
                                });
                                if (stsmode == '0') {
                                    mainEvt.attr('data-stsmode', '1');
                                    mainEvt.html('<button type="button" class="btn btn-danger btn-sm waves-effect waves-light">Inactive</button>');
                                } else if (stsmode == '1') {
                                    mainEvt.attr('data-stsmode', '0');
                                    mainEvt.html('<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Active</button>');
                                } else {
                                    $('.drRideBox').hide();
                                }
                                //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    icon: 'error',
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click", ".delconfirm", function(evt) {
            var row_id = $(this).attr('data-row_id');
            var act_url = $(this).attr('data-act_url');
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to Delete the Record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "No, Cancel!",

            }).then((result) => {

                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: 'success',
                                    text: res.response,
                                    type: "success"
                                });
                                $('#' + row_id).remove();
                                setTimeout(function() {
                                    location.reload();
                                }, 2500);
                                //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    icon: 'error',
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });


                }
            })
        });

        $("#datepicker").datepicker();

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

        function get_filter_datestrings() {
            var qryString = '';
            if ($('#datepicker').val() != '') {
                qryString += '&filter=1&date=' + $('#datepicker').val();
            }
            return qryString;
        }

        function get_filter_monthstrings() {
            var qryString2 = '';
            if ($('#monthpicker').val() != '') {
                qryString2 += '&filter=1&month=' + $('#monthpicker').val();
            }
            if ($('#category').val() != '') {
                qryString2 += '&filter=1&category=' + $('#category').val();
            }
            return qryString2;
        }

        function get_filter_yearstrings() {
            var qryString3 = '';
            if ($('#yearpicker').val() != '') {
                qryString3 += '&filter=1&year=' + $('#yearpicker').val();
            }
            return qryString3;
        }

        $('#FilterBtns1').on('click', function() {
            var qryString = get_filter_datestrings();
            if (qryString != '') {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list";
            }
        });

        $('#FilterBtns2').on('click', function() {
            var qryString2 = get_filter_monthstrings();
            if (qryString2 != '') {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list?" + qryString2;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list";
            }
        });

        $('#FilterBtns3').on('click', function() {
            var qryString3 = get_filter_yearstrings();
            if (qryString3 != '') {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list?" + qryString3;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/expense/list";
            }
        });


        $("#export_all_user").click(function(event) {
            event.preventDefault();
            var query_strings = "<?php echo $_SERVER["QUERY_STRING"]; ?>";
            window.location.href = "<?php echo base_url(); ?>admin/expense/list?" + query_strings + "&filter=1&export=excel";
        });
        $('body').on("click", ".btn-close", function() {
            var id = $(this).attr('data-row_id');
            var url = "<?php echo base_url(); ?>admin/expense/alert_close";
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    'id': id,
                },
                dataType: 'json',
                success: function(res) {
                    if (res['status'] == 1) {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>