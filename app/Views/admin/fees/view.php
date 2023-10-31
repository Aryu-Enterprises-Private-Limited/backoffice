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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/fees/list' ?>">Fees</a>
                        </li>
                        <?php if (isset($feeDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $feeDetails->student_name; ?>
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
                            <th scope="row"> Student Name </th>
                            <td><?php if (isset($feeDetails->student_name)) echo ucfirst($feeDetails->student_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Course Name </th>
                            <td><?php if (isset($feeDetails->course_name)) echo ucfirst($feeDetails->course_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Total Fee </th>
                            <td><?php if (isset($feeDetails->total_fee)) echo ucfirst($feeDetails->total_fee); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> No Of Installment </th>
                            <td><?php if (isset($feeDetails->no_of_installment)) echo ucfirst($feeDetails->no_of_installment); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($feeDetails->status) && $feeDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($feeDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($feeDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>