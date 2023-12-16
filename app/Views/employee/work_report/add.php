<!-- Start content -->
<?= $this->extend('employee_layout') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="/assets/richtexteditor/rte_theme_default.css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . EMPLOYEE_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . EMPLOYEE_PATH . '/work_report/list' ?>"><?php echo  'Work Report'; ?> </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo 'Add New'; ?>
                        </li>
                    </ol>
                    <hr>
                    <h3><?= $title;  ?></h3>
                </div>
            </div>


            <div class="create-label">
                <form id="report_form" method="post" action="<?= (base_url(EMPLOYEE_PATH . '/work_report/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="date" id="date" value="<?php if (isset($candidates_info->date)) echo $candidates_info->date;
                                                                                                                else echo date("Y-m-d"); ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Project Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="proj_name[]" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Task Detail <span class="text-danger">*</span></label>
                        <div class="col-sm-10 reduce_len">
                            <textarea class="form-control create-input task_dt" rows="3" name="task_dt[]" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Work Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="wrk_sts[]" required>
                                <option value=""> Select </option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="in_review">IN Review</option>
                                <option value="in_progress">In Progress</option>

                            </select>
                        </div>
                    </div>
                    <div id="addFieldDiv">
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Add Task </label>
                        <div class="col-sm-6 ">
                            <div class="create-input text-end"><a href="javascript:void(0);" class="add_button " title="Add field"><img src="/images/add-icon.png" /></a></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Comments </label>
                        <div class="col-sm-10">
                            <textarea class="form-control create-input" rows="3" name="comment" id="comment"></textarea>
                        </div>
                    </div>


                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="/assets/richtexteditor/rte.js"></script>
<script type="text/javascript" src="/assets/richtexteditor/plugins/all_plugins.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

<!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> -->
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
<script type="text/javascript">
    $(function() {
        $(".sbmtBtn").click(function(evt) {
            // e.preventDefault();
            if ($('#report_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#report_form').submit();
            }
        });
        var editor1 = new RichTextEditor(".task_dt");

        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var wrapper2 = $('.field_wrapper2');
        // var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="/images/remove-icon.png"/></a></div>'; //New input field html 

        var fieldHTML2 = '<div class="mb-3 row field_wrapper2"><label class="col-sm-2 col-form-label fw-bold"> Task Detail </label><div class="col-sm-10 reduce_len"><textarea class="form-control create-input task_dt" rows="3" name="task_dt[]"  required></textarea><div class="text-end create-input"><a href="javascript:void(0);" class="remove_button"><img /></a></div></div></div>';

        var fieldHTML = '<div class="mb-3 row field_wrapper"><label class="col-sm-2 col-form-label fw-bold"> Project Name </label><div class="col-sm-10"><input type="text" class="form-control create-input" name="proj_name[]"   required></div><a href="javascript:void(0);" class="remove_button"><img /></a></div>';

        var fieldHTML3 = '<div class="mb-3 row "><label class="col-sm-2 col-form-label fw-bold">Work Status </label><div class="col-sm-10"><select class="form-select form-control create-input" name="wrk_sts[]"  required><option value=""> Select </option><option value="completed" >Completed</option><option value="pending">Pending</option><option value="in_review">IN Review</option><option value="in_progress" >In Progress</option></select><div class="text-end create-input"><a href="javascript:void(0);" class="remove_button"><img src="/images/remove-icon.png"/></a></div></div></div>';

        var x = 1; //Initial field counter is 1

        // Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                x++; //Increase field counter
                $('#addFieldDiv').append('<div class="fieldDiv' + x + '">' + fieldHTML + fieldHTML2 + fieldHTML3 + '</div>');

                const $fieldDiv2 = $('.fieldDiv' + x + '');

                // Find all elements with the class "task_dt" within "fieldDiv2"
                const $taskDtElements = $fieldDiv2.find('.task_dt');

                // Initialize RichTextEditor for each "task_dt" element
                $taskDtElements.each(function() {
                    new RichTextEditor(this);
                });
            } else {
                alert('A maximum of ' + maxField + ' fields are allowed to be added. ');
            }

        });

        // Once remove button is clicked
        $('body').on('click', '.remove_button', function(e) {
            e.preventDefault();
            $('.remove_button').parent().parent().parent().parent('div.fieldDiv' + x + '').remove(); //Remove field html
            x--; //Decrease field counter
        });

    });
</script>

<?= $this->endSection() ?>