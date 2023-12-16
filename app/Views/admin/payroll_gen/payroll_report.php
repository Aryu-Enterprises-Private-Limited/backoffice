<!-- Start content -->
<?= $this->extend('layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>

<?php
if (isset($_GET['month']) && $_GET['month'] != '') {
    $date = $_GET['month'];
} else {
    $date = date("Y-m");
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
                <div class="col-md-5">
                    <button type="button" class="btn btn-primary btn-sm butn-back text-white btn-size" id="update_pay_status"><?php echo 'Update Payment status'; ?></button>
                </div>
            </div>

            <hr>
            <div class="list-label">
                <table id="displayDataTbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th><input name="checkbox_id[]" type="checkbox" value="on" class="checkall"></th>
                            <th> S.No </th>
                            <th> Employee Name </th>
                            <th> Salary </th>
                            <th> No.of days Present </th>
                            <th> No.of days Absent </th>
                            <th> Take Home </th>
                            <th> Payment Status </th>
                            <th> Paid Date </th>
                            <th> Notes </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" id="show_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payroll Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="back_c" autocomplete="off">
                    <!-- <p>Modal body text goes here.</p> -->
                    <div id="show_data">

                    </div>
                    <div class="mb-3 row copy hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group  removemore" style="margin-top:10px">
                                <input type="text" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="d-none" id="updateBgBtn" type="submit">Update post</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary doc_sub_Btn">Save changes</button>
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
        $(".hide").hide();
        const myModal = new bootstrap.Modal('#show_modal');

        $('body').on("click", ".pay_notes", function() {
            $('#show_data').html('');
            var pay_id = $(this).attr('data-pay_id');
            var act_url = $(this).attr('data-act_url');
            $.ajax({
                type: 'post',
                url: act_url,
                data: {
                    'pay_id': pay_id,
                },
                dataType: 'json',
                success: function(res) {
                    myModal.show();
                    $('#show_data').append(res);
                }
            });
        });

        $('body').on("click", ".add-more", function() {
            var html = $(".copy:hidden").clone().removeClass('hide').removeAttr('style');
            html.find('input').attr('name', 'addmore[]');
            $('.input-container').append(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });

        $('body').on("click", ".doc_sub_Btn", function() {
            $('#txt-err').empty();
            const form = document.getElementById("back_c");
            const submitter = document.querySelector("button#updateBgBtn[type=submit]");
            var formdata = new FormData(form, submitter);
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>admin/payroll_gen/update_notes',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(res) {
                    res = JSON.parse(res);
                    if(res == "1"){
                        location.reload();
                    }else if(res == "0"){
                       var err =  '<span id ="txt-err" class="text-danger">This field is Required</span>';
                       $('.input-container').append(err);
                    }
 
                }
            });
        });



        $(document).on("change", ".checkall", function() {
            $(".checkRows").prop('checked', $(this).prop("checked"));
        });

        $(document).on("click", ".paginate_button", function() {
            if ($(".checkall ").is(':checked')) {
                $(".checkall ").trigger('click');
            }
        });

        $(document).on("click", "#update_pay_status", function(evt) {
            var checkedValues = [];
            var stsmode = [];
            $('.checkRows:checked').each(function() {
                checkedValues.push($(this).val());
                stsmode.push($(this).attr('data-stsmode'));
            });
            // var stsmode = $(this).attr('data-stsmode');
            // console.log(stsmode);
            // console.log("Checked Values: ", checkedValues);
            var url = "<?php echo base_url(); ?>admin/payroll_gen/update-payment-status";
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    'checkedValues_id': checkedValues,
                    'stsmode': stsmode,
                },
                dataType: 'json',
                success: function(res) {
                    location.reload();
                }
            });
        });
        var qryString = get_filter_strings();
        if (qryString != '') {
            var url = "<?php echo base_url(); ?>admin/payroll_gen/list_ajax?" + qryString;
        } else {
            var url = "<?php echo base_url(); ?>admin/payroll_gen/list_ajax";
        }
        var dataTbl = $("#displayDataTbl").DataTable({
            "scrollX": true,
            "aaSorting": [2, "asc"],
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
                    data: 'checker_box'
                },
                {
                    data: 's_no'
                },
                {
                    data: 'employee_name'
                },
                {
                    data: 'month_salary'
                },
                {
                    data: 'no_present_day'
                },
                {
                    data: 'no_absent_day'
                },
                {
                    data: 'take_home'
                },
                {
                    data: 'payment_status'
                },
                {
                    data: 'paid_date'
                },
                {
                    data: 'notes'
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
                                    mainEvt.html('<button type="button" class="btn btn-danger btn-sm waves-effect waves-light"> Unpaid </button>');
                                } else if (stsmode == '1') {
                                    mainEvt.attr('data-stsmode', '0');
                                    mainEvt.html('<button type="button" class="btn btn-success btn-sm waves-effect waves-light"> Paid </button>');
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
                window.location.href = "<?php echo base_url(); ?>admin/payroll_gen/payroll_report?" + qryString;
            } else {
                window.location.href = "<?php echo base_url(); ?>admin/payroll_gen/payroll_report";
            }
        });

    });
</script>
<?= $this->endSection() ?>