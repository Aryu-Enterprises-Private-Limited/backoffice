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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/lms/list' ?>">LMS</a>
                        </li>
                        <?php if (isset($lmsDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $lmsDetails->first_name . ' ' . $lmsDetails->last_name; ?>
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
                            <th scope="row">First Name</th>
                            <td><?php if (isset($lmsDetails->first_name)) echo $lmsDetails->first_name; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Last Name </th>
                            <td><?php if (isset($lmsDetails->last_name)) echo  ucfirst($lmsDetails->last_name);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Address </th>
                            <td><?php if (isset($lmsDetails->address)) echo ucfirst($lmsDetails->address); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Phone </th>
                            <td><?php if (isset($lmsDetails->phone)) echo ucfirst($lmsDetails->phone); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Email </th>
                            <td><?php if (isset($lmsDetails->email)) echo ucfirst($lmsDetails->email); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Lead Source </th>
                            <td><?php if (isset($lmsDetails->lead_source)) echo ucfirst($lmsDetails->lead_source); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> LinkedIn </th>
                            <td><?php if (isset($lmsDetails->linked_in)) echo ucfirst($lmsDetails->linked_in); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Twitter </th>
                            <td><?php if (isset($lmsDetails->twitter)) echo ucfirst($lmsDetails->twitter); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> FaceBook </th>
                            <td><?php if (isset($lmsDetails->facebook)) echo ucfirst($lmsDetails->facebook); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Followup Alert </th>
                            <td><?php if (isset($lmsDetails->follow_up_alert)) echo ucfirst($lmsDetails->follow_up_alert); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($lmsDetails->status) && $lmsDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($lmsDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($lmsDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>