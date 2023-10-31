<?= $this->extend('employee_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . EMPLOYEE_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . EMPLOYEE_PATH . '/work_report/list' ?>">Work Report</a>
                        </li>
                        <?php if (isset($report_details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $report_details->employee_name; ?>
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
                            <th scope="row"> Employee Name </th>
                            <td><?php if (isset($report_details->employee_name)) echo ucfirst($report_details->employee_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Date </th>
                            <td><?php if (isset($report_details->date)) echo ucfirst($report_details->date); ?></td>
                        </tr>
                        <?php
                        $projTaskDetails = json_decode($report_details->proj_task_dts);
                        $taskDetailsHTML = '';
                        //  echo"<pre>";print_r($projTaskDetails);die;

                        if (is_array($projTaskDetails)) {
                            foreach ($projTaskDetails as $projTask) {
                                $taskDetailsHTML .= "<strong>Project name </strong>:- " . ucfirst($projTask->proj_name) . "<br>";
                                $taskDetailsHTML .= "<strong>Task details </strong>:- " . ucfirst($projTask->task_dt) . "<br>";
                                $taskDetailsHTML .= "<strong>Task status </strong>:- " . ucfirst(str_replace("_", " ", $projTask->wrk_sts)) . "<br><br>";
                            }
                        } ?>
                        <tr>
                            <th scope="row"> Project And Task Details </th>
                            <td><?php echo  $taskDetailsHTML; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>