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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/public_holiday/list' ?>">Holiday</a>
                        </li>
                        <?php if (isset($pholidayDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $pholidayDetails->reason; ?>
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
                            <th scope="row"> Reason </th>
                            <td><?php if (isset($pholidayDetails->reason)) echo ucfirst($pholidayDetails->reason); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Current Year </th>
                            <td><?php if (isset($pholidayDetails->current_year)) echo ucfirst($pholidayDetails->current_year); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Date </th>
                            <td><?php if (isset($pholidayDetails->date)) echo ucfirst($pholidayDetails->date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($pholidayDetails->status) && $pholidayDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($pholidayDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($pholidayDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>