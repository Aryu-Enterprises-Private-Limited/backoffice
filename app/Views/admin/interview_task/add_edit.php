<!-- Start content -->
<?= $this->extend('layout') ?>
<?php if (isset($inter_task_info->id)) {
    $req = ''
?>

<?php } else {
    $req = 'required';
}  ?>
<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/interview_task/list' ?>"><?php echo  'Interview Task'; ?> </a>
                        </li>
                        <?php if (isset($inter_task_info) && $inter_task_info->candidate_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $inter_task_info->candidate_name; ?>
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
                <form id="interview_task_form" method="post" action="<?= (base_url(ADMIN_PATH . '/interview_task/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($inter_task_info) && $inter_task_info->id) echo $inter_task_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="date" id="date" value="<?php if (isset($inter_task_info->date)) echo $inter_task_info->date;
                                                                                                                else echo date("Y/m/d");  ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Candidate Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="candidate_name" class="form-control create-input required" id="candidate_name">
                                <option value="">Select</option>
                                <?php foreach ($candidate_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($inter_task_info->candidate_id) && $inter_task_info->candidate_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>,<?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Task Title link <span class="text-danger">*</span></label>
                        <div class="col-sm-10 input-container">
                            <div class="input-group input-groups control-group after-add-more col-sm-10">
                                <input type="text" name="addmore[]" class="form-control" id="addmore" placeholder="Task link" value="" <?= $req; ?>>
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                            <?php
                            if (isset($inter_task_info->task_title_link)) {
                                $decode = json_decode($inter_task_info->task_title_link);
                                foreach ($decode as $data) {
                                    if ($data != '') {
                            ?>
                                        <div class="mb-3 row copy hide_show">
                                            <div class="col-sm-10 ">
                                                <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                                    <input type="text" name="addmore[]" class="form-control" value="<?php if (isset($data)) echo $data; ?>">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                            <?php }
                            } ?>
                        </div>

                    </div>

                    <div class="mb-3 row copy hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                <input type="text" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Interview Task Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="interview_task_sts" class="form-control create-input required" id="interview_task_sts">
                                <option value="">Select</option>
                                <option value="pending" <?php if (isset($inter_task_info)) echo ($inter_task_info->interview_task_sts == 'pending') ? "selected" : ""
                                                        ?>>Pending</option>
                                <option value="in_review" <?php if (isset($inter_task_info)) echo ($inter_task_info->interview_task_sts == 'in_review') ? "selected" : ""
                                                            ?>>InReview</option>
                                <option value="done" <?php if (isset($inter_task_info)) echo ($inter_task_info->interview_task_sts == 'done') ? "selected" : ""
                                                        ?>>Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Comments </label>
                        <div class="col-sm-10">
                            <textarea class="form-control create-input" rows="3" name="comment" id="comment"><?php if (isset($inter_task_info) && $inter_task_info->comments) echo $inter_task_info->comments;  ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($inter_task_info) && isset($inter_task_info->status) && $inter_task_info->status == '1') $sT = 'checked="checked"';
                        else $sT = ''; ?>
                        <label class="col-sm-2 col-form-label fw-bold">Status</label>
                        <div class="form-check form-switch col-sm-10">
                            <input class="form-check-input form-control" type="checkbox" name="status" <?php echo $sT; ?>>
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
        <?php if (isset($form_data['id'])) { ?>
            $(".hide_show").show();
            $(".hide").hide();
        <?php  } else { ?>
            $(".hide").hide();
        <?php  } ?>

        $(".add-more").click(function() {
            var html = $(".copy:hidden").clone().removeClass('hide').removeAttr('style');
            html.find('input').attr('name', 'addmore[]');
            $('.input-container').append(html);
        });

        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });


        $(".sbmtBtn").click(function(evt) {
            if ($('#interview_task_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#interview_task_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>