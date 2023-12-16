<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu ">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"></div>
                <ul class="has_sub">
                    <li>
                        <a class="nav-link <?php echo $isActive = (isset($curr_module) && $curr_module == 'dashboard') ? 'active subdrop' : ''; ?>" href="<?= '/' . MANAGER_PATH . '/dashboard' ?>">
                            <div class="sb-nav-link-icon"><i class="fa fa-home" aria-hidden="true"></i></div>
                            Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="has_sub">
                    <li>
                        <a class="nav-link  <?php echo $isActive = (isset($curr_module) && $curr_module == 'employee') ? 'active subdrop' : ''; ?>" href="<?= '/' . MANAGER_PATH . '/employee/list' ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Employee Details
                        </a>
                    </li>
                </ul>
                <ul class="has_sub">
                    <li>
                        <a class="nav-link <?php echo $isActive = (isset($curr_module) && $curr_module == 'att_report') ? 'active subdrop' : ''; ?>" href="<?= '/' . MANAGER_PATH . '/att_report/list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa fa-clock" aria-hidden="true"></i></div>
                            Attendance Report
                        </a>
                    </li>
                </ul>
                <ul class="has_sub">
                    <li>
                        <a class="nav-link <?php echo $isActive = (isset($curr_module) && $curr_module == 'public_holiday') ? 'active subdrop' : ''; ?>" href="<?= '/' . MANAGER_PATH . '/public_holiday/list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa fa-table" aria-hidden="true"></i></div>
                            Public Holidays
                        </a>
                    </li>
                </ul>
                <ul class="has_sub">
                    <li>
                        <a class="nav-link <?php echo $isActive = (isset($curr_module) && $curr_module == 'candidates') ? 'active subdrop' : ''; ?>" href="<?= '/' . MANAGER_PATH . '/candidates/list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa fa-table" aria-hidden="true"></i></div>
                            Candidate Application
                        </a>
                    </li>
                </ul>

                <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fa fa-balance-scale"></i></div>
                    Finance
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/income/list' ?>"> Income </a>
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/expense/list' ?>"> Expense </a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-percent"></i></div>
                    Tax
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/gst/list' ?>"> GST </a>
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/itr/list' ?>"> ITR </a>
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/pf/list' ?>"> PF </a>
                        <a class="nav-link" href="<?= '/' . MANAGER_PATH . '/tds/list' ?>"> TDS </a>
                    </nav>
                </div>


                <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pages
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="login.html">Login</a>
                                <a class="nav-link" href="register.html">Register</a>
                                <a class="nav-link" href="password.html">Forgot Password</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            Error
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="401.html">401 Page</a>
                                <a class="nav-link" href="404.html">404 Page</a>
                                <a class="nav-link" href="500.html">500 Page</a>
                            </nav>
                        </div>
                    </nav>
                </div> -->
                <!-- <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a>
                <a class="nav-link" href="tables.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a> -->
            </div>
        </div>
        <!-- <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Start Bootstrap
        </div> -->
    </nav>
</div>