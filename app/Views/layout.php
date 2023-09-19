<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="icon" href="/images/favicon.png" type="image/x-icon">
    <link href="/css/style.css" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
    <title><?= $title; ?></title>
</head>

<body>

    <nav class="navbar navbar-light dasboard-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= '/' . ADMIN_PATH . '/dashboard' ?>">
                <img src="/images/aryulogo.png" alt="aryu-logo" class="dashboard-nav-img">
            </a>
            <div class="col-xl-1 col-sm-4 mt-5 m_header">
                <div class="dropdown pr-2 drop_m">
                    <div class="dropdown-toggle show" data-bs-toggle="dropdown" aria-expanded="true">
                        <span class="admintext">Admin</span>
                    </div>
                    <ul class="dropdown-menu" data-popper-placement="bottom-end" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-53px, 26px);">
                        <!-- <li><a class="dropdown-item" target="_blank" href="http://3.20.133.13/admin-setup/view/1"><span class="fa fa-user"></span><span class="px-2">My Profile</span></a></li> -->
                        <!-- <li>
                            <hr class="dropdown-divider">
                        </li> -->
                        <li><a class="dropdown-item logout" href="<?= '/' . ADMIN_PATH . '/logout' ?>"><span class="fa fa-sign-out"></span><span class="px-2">Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-white" href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home" aria-hidden="true"></i>Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="<?= '/' . ADMIN_PATH . '/lms/list' ?>"><i class="fa fa-list" aria-hidden="true"></i>LMS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="<?= '/' . ADMIN_PATH . '/crm/list' ?>"><i class="fa fa-list" aria-hidden="true"></i>CRM</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link  text-white dropdown-toggle show" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="true"><i class="fa fa-clock-o" aria-hidden="true"></i> Employee </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/employee/list' ?>"> Employee Setup </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/pay/list' ?>"> Pay </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/role/list' ?>"> Role </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/department/list' ?>"> Department </a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white g-5" href="<?= '/' . ADMIN_PATH . '/schedule/list' ?>"><i class="fa fa-calendar" aria-hidden="true"></i> Schedule </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link  text-white dropdown-toggle show" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="true"><i class="fa fa-clock-o" aria-hidden="true"></i>Attendance</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/attendance/list' ?>">Employee Attendance</a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/report/list' ?>">Attendance Report</a></li>
                <!-- <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/report/monthly_list' ?>">Attendance Report(Monthwise)</a></li> -->
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white g-5" href="<?= '/' . ADMIN_PATH . '/public_holiday/list' ?>"><i class="fa fa-table" aria-hidden="true"></i> Public Holiday </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link  text-white dropdown-toggle show" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="true"><i class="fa fa-clock-o" aria-hidden="true"></i> Recuritment </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/candidates/list' ?>"> Candidates </a></li>
                <!-- <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/interview_task/list' ?>"> Interview Task </a></li> -->
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/job/list' ?>"> Job Opening </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/job_type/list' ?>"> Job Type </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/application/list' ?>"> Application Status </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/interview/list' ?>"> Interview Status </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/stage/list' ?>"> Stage </a></li>
                <li><a class="dropdown-item" href="<?= '/' . ADMIN_PATH . '/reason_rejection/list' ?>"> Reason For Rejection </a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="<?= '/' . ADMIN_PATH . '/client/list' ?>"><i class="fa fa-user" aria-hidden="true"></i> Client </a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white g-5" href="<?= '/' . ADMIN_PATH . '/invoice' ?>"><i class='fa fa-fax'></i> In-Voice Generate </a>
        </li>
        <!--<li class="nav-item">
            <a class="nav-link  text-white" href="#"><i class="fa fa-file-text-o" aria-hidden="true"></i>Form</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i>Charts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="#"><i class="fa fa-table" aria-hidden="true"></i>Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  text-white" href="#"><i class="fa fa-file-o" aria-hidden="true"></i>Pages</a>
        </li> -->
    </ul>



    <?= $this->renderSection('content') ?>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark footer">
        <div class="container-fluid justify-content-center">
            <a class="navbar-brand " href="#">Copy
                © 2023 Aryu enterprises PVT. LTD. All rights reserved.
            </a>
        </div>
    </nav>
    <!-- Include jQuery library -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->




    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!-- Include Toastr library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


    <?= $this->renderSection('script') ?>
</body>

</html>