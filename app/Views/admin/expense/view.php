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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/expense/list' ?>"> Expenses </a>
                        </li>
                        <?php if (isset($exp_details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $exp_details->category_name; ?>
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
                            <td><?php if (isset($exp_details->date)) echo ucfirst($exp_details->date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Invoice Number </th>
                            <td><?php if (isset($exp_details->invoice_no)) echo strtoupper($exp_details->invoice_no); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Description </th>
                            <td><?php if (isset($exp_details->description)) echo strtoupper($exp_details->description); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Category Name </th>
                            <td><?php if (isset($exp_details->category_name)) echo strtoupper($exp_details->category_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Amount </th>
                            <td><?php if (isset($exp_details->amount)) echo strtoupper($exp_details->amount); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($exp_details->status) && $exp_details->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($exp_details->created_at)) echo date('d-m-Y h:i:s A', strtotime($exp_details->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>