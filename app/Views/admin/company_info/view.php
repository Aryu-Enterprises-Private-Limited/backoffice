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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/company_info/list' ?>">Company info</a>
                        </li>
                        <?php if (isset($compDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $compDetails->company_title; ?>
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
                            <th scope="row"> Company Title </th>
                            <td><?php if (isset($compDetails->company_title)) echo ucfirst($compDetails->company_title); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> User Id /User Name </th>
                            <td><?php if (isset($compDetails->user_id_name)) echo ucfirst($compDetails->user_id_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Links </th>
                            <td><?php if (isset($compDetails->links)) echo ucfirst($compDetails->links); ?></td>
                        </tr>
                        <!-- <tr>
                            <th scope="row"> Value </th>
                            <td><?php if (isset($compDetails->value)) echo ucfirst($compDetails->value); ?></td>
                        </tr> -->
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($compDetails->status) && $compDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($compDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($compDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>