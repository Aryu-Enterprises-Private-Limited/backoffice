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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/job/list' ?>">Job</a>
                        </li>
                        <?php if (isset($jobDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $jobDetails->jobs_name; ?>
                            </li>
                        <?php } ?>
                        <li class="breadcrumb-item active">
                            <?php echo 'view'; ?>
                        </li>
                    </ol>
                    <hr>
                    <h3><?= $title; ?></h3>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered m-b-0">
                    <tbody>
                        <tr>
                            <th scope="row"> Job Name </th>
                            <td><?php if (isset($jobDetails->jobs_name)) echo ucfirst($jobDetails->jobs_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Job description </th>
                            <td><?php if (isset($jobDetails->job_desc)) echo ucfirst($jobDetails->job_desc); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Job Budget </th>
                            <td><?php if (isset($jobDetails->job_budget)) echo ucfirst($jobDetails->job_budget); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Job Type </th>
                            <td><?php if (isset($job_type_details->job_type_name)) echo ucfirst($job_type_details->job_type_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Job Requirement </th>
                            <td><?php if (isset($jobDetails->job_requirement)) echo ucfirst($jobDetails->job_requirement); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($jobDetails->status) && $jobDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($jobDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($jobDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>