<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/student_info/list' ?>"><?php echo  'Student Info'; ?> </a>
                        </li>
                        <?php if (isset($stu_info) && $stu_info->first_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $stu_info->first_name; ?>
                            </li>
                            <li class="breadcrumb-item active">
                                <?php echo 'Edit'; ?>
                            </li>
                        <?php } else { ?>
                            <li class="breadcrumb-item active">
                                <?php echo 'Add New'; ?>
                            </li>
                        <?php } ?>
                    </ol>
                    <hr>
                    <h3><?= $title;  ?></h3>
                </div>
            </div>

            <div class="create-label">
                <form id="follow_up_form" method="post" action="<?= (base_url(ADMIN_PATH . '/student_info/followup_update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($stu_info) && $stu_info->id) echo $stu_info->id; ?>">

                    <div id="addFieldDiv">
                        <?php
                        if (isset($stu_info) && !empty($stu_info->follow_up_dt) && !empty($stu_info->follow_up_cmnt)) {
                            $follow_up_dt = json_decode($stu_info->follow_up_dt);
                            $follow_up_cmnt = json_decode($stu_info->follow_up_cmnt);
                            for ($i = 0; $i < count($follow_up_dt); $i++) {
                        ?>
                                <div class="fieldDiv">
                                    <div class="mb-3 row field_wrapper"><label class="col-sm-2 col-form-label fw-bold "> Date </label>
                                        <div class="col-sm-10"><input placeholder="revised date" type="date" class="form-control create-input " name="date[]" value="<?php if (isset($follow_up_dt)) echo $follow_up_dt[$i]; ?>" required></div><a href="javascript:void(0);" class="remove_button"><img /></a>
                                    </div>
                                    <div class="mb-3 row field_wrapper2"><label class="col-sm-2 col-form-label fw-bold"> Comments </label>
                                        <div class="col-sm-10">
                                            <textarea placeholder="comments" class="form-control create-input" rows="3" name="comments[]" required><?php if (isset($follow_up_cmnt)) echo $follow_up_cmnt[$i]; ?></textarea>
                                            <div class="text-end create-input"><a href="javascript:void(0);" class="remove_button"><img src="/images/remove-icon.png" /></a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php  }
                        } else { ?>
                            <div class="fieldDiv">
                                <div class="mb-3 row field_wrapper">
                                    <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control create-input" name="date[]" id="date" value="" required>
                                    </div>
                                </div>
                                <div class="mb-3 row field_wrapper2">
                                    <label class="col-sm-2 col-form-label fw-bold"> Comments <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea required class="form-control create-input" rows="3" name="comments[]" id="comments"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php   }
                        ?>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> </label>
                        <div class="col-sm-6 ">
                            <div class="create-input text-end"><a href="javascript:void(0);" class="add_button " title="Add field"><img src="/images/add-icon.png" /></a></div>
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

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#follow_up_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#follow_up_form').submit();
            }
        });

        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var wrapper2 = $('.field_wrapper2');
        // var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="/images/remove-icon.png"/></a></div>'; //New input field html 

        var fieldHTML2 = '<div class="mb-3 row field_wrapper2"><label class="col-sm-2 col-form-label fw-bold">Comments </label><div class="col-sm-10"><textarea placeholder="comments"class="form-control create-input" rows="3" name="comments[]" required></textarea><div class="text-end create-input"><a href="javascript:void(0);" class="remove_button"><img src="/images/remove-icon.png"/></a></div></div></div>';

        var fieldHTML = '<div class="mb-3 row field_wrapper"><label class="col-sm-2 col-form-label fw-bold "> Date </label><div class="col-sm-10"><input placeholder="revised date" type="date" class="form-control create-input " name="date[]"  required></div><a href="javascript:void(0);" class="remove_button"><img /></a></div>';

        var x = 1;

        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                x++; //Increase field counter
                // $(wrapper).append(fieldHTML2); //Add field html
                // $(wrapper2).append(fieldHTML);
                $('#addFieldDiv').append('<div class="fieldDiv">' + fieldHTML + fieldHTML2 + '</div>');
                // $('#addFieldDiv').append(fieldHTML2);
            } else {
                alert('A maximum of ' + maxField + ' fields are allowed to be added. ');
            }
        });

        // Once remove button is clicked
        $('body').on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent().parent().parent().parent('div.fieldDiv').remove(); //Remove field html
            x--; //Decrease field counter
        });
    });
</script>
<?= $this->endSection() ?>