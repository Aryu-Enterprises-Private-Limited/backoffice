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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/emp_bank_info/list' ?>">Employee Bank info</a>
                        </li>
                        <?php if (isset($emp_bank_Details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $emp_bank_Details->employee_name; ?>
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
                            <th scope="row"> Employee Name </th>
                            <td><?php if (isset($emp_bank_Details->employee_name)) echo ucfirst($emp_bank_Details->employee_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Account Number </th>
                            <td><?php if (isset($emp_bank_Details->acc_no)) echo ucfirst($emp_bank_Details->acc_no); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> IFSC Code </th>
                            <td><?php if (isset($emp_bank_Details->ifsc_code)) echo ucfirst($emp_bank_Details->ifsc_code); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Account Type </th>
                            <td><?php if (isset($emp_bank_Details->acc_type)) echo ucfirst($emp_bank_Details->acc_type); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Branch Name </th>
                            <td><?php if (isset($emp_bank_Details->branch_name)) echo ucfirst($emp_bank_Details->branch_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Employee Status </th>
                            <td><?php if (isset($emp_bank_Details->employee_sts)) echo ucfirst(str_replace("_", " ", $emp_bank_Details->employee_sts)); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($emp_bank_Details->status) && $emp_bank_Details->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($emp_bank_Details->created_at)) echo date('d-m-Y h:i:s A', strtotime($emp_bank_Details->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>