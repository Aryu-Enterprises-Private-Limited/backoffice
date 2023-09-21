<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/interview_task/list' ?>">Interview Task</a>
                        </li>
                        <?php if (isset($interview_taskDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $interview_taskDetails->candidate_name; ?>
                            </li>
                        <?php } ?>
                        <li class="breadcrumb-item active">
                            <?php echo 'view'; ?>
                        </li>
                    </ol>
                    <hr>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered m-b-0">
                    <tbody>
                        <tr>
                            <th scope="row"> Date </th>
                            <td><?php if (isset($interview_taskDetails->date)) echo ucfirst($interview_taskDetails->date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Candidate Name </th>
                            <td><?php if (isset($interview_taskDetails->candidate_name)) echo ucfirst($interview_taskDetails->candidate_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Interview Task Status </th>
                            <td><?php if (isset($interview_taskDetails->interview_task_sts)) echo ucfirst($interview_taskDetails->interview_task_sts); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Comments </th>
                            <td><?php if (isset($interview_taskDetails->comments)) echo ucfirst($interview_taskDetails->comments); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($interview_taskDetails->status) && $interview_taskDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($interview_taskDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($interview_taskDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>