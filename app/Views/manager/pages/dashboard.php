<?php echo view(MANAGER_PATH . '/templates/header'); ?>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.34.0/dist/apexcharts.min.css"> -->

<main>
    <div class="container-fluid px-4 ">
        <h2 class="mt-5">Dashboard</h2>
        <hr>

        <!-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        <!-- <a href="<?= '/' . MANAGER_PATH . '/employee/list' ?>"> -->
        <div class="row">
            <div class="col-md-4  p-3 mt-3 mb-3 bg-color">
                <div class="card">
                    <div id="chart"></div>
                </div>
            </div>

            <div class="col-md-4   p-3 mt-3 mb-3 bg-color">
                <div class="card ">
                    <div id="gen-chart">
                    </div>
                </div>
            </div>
            <div class="col-md-4  p-3 mt-3 mb-3 bg-color">
                <div class="card ">
                    <div id="job-type-chart">
                    </div>
                </div>
            </div>
        </div>
        <!-- </a> -->
        <div class="row mt-3 bg-color">
            <div class="col-xl-6 mt-3">
                <div class="card mb-4 ">
                    <div class="card-body">Application Received by source<div id="bar-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mt-3">
                <div>
                    <div class="card mb-4 ">
                        <div class="card-body"> Finance <div id="finance-bar-chart"></div>
                        </div>
                    </div>
                </div>




                <!-- <div>
                    <div class="card mb-4 ">
                        <div class="card-body"> Finance <div id="finance-bar-chart"></div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row">
            <div class="card mb-4 ">
                <h5 class="text-center">Application Status</h5>
                <table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> Applied </th>
                            <th> Hired </th>
                            <th> Rejected </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php
                                if (isset($applied_cnt)) {
                                    echo $applied_cnt;
                                } else {
                                    echo '-';
                                } ?></td>
                            <td><?php
                                if (isset($hired_cnt)) {
                                    echo $hired_cnt;
                                } else {
                                    echo '-';
                                } ?></td>
                            <td><?php
                                if (isset($rej_cnt)) {
                                    echo $rej_cnt;
                                } else {
                                    echo '-';
                                } ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card mb-4">
                <h5 class="text-center">Interview Status</h5>
                <table id="displayDataTbl" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th> Status </th>
                            <th> Count </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($int_sts_dts)) {
                            foreach ($int_sts_dts as $data) { ?>
                                <tr>
                                    <td><?= $data->interview_sts; ?> </td>
                                    <?php if ($data->interview_sts == 'Scheduled') { ?>
                                        <td><?= $sch_cnt; ?></td>
                                    <?php  } else if ($data->interview_sts == 'Not Scheduled') { ?>
                                        <td><?= $n_sch_cnt; ?></td>
                                    <?php  } else if ($data->interview_sts == 'Re-Schedule') { ?>
                                        <td><?= $re_sch_cnt; ?></td>
                                    <?php } else if ($data->interview_sts == 'No Show') { ?>
                                        <td><?= $show_cnt; ?></td>
                                    <?php } else if ($data->interview_sts == 'Completed') { ?>
                                        <td><?= $completed_cnt; ?></td>
                                    <?php  } else if ($data->interview_sts == 'Not able to Come') { ?>
                                        <td><?= $not_able_cnt; ?></td>
                                    <?php } else if ($data->interview_sts == 'waiting list') { ?>
                                        <td><?= $wl_cnt; ?></td>
                                    <?php } else if ($data->interview_sts == 'offer process') { ?>
                                        <td><?= $off_cnt; ?></td>
                                    <?php    } ?>

                                </tr>
                            <?php  } ?>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php echo view(MANAGER_PATH . '/templates/footer'); ?>
</div>
</div>

<?php
echo view(MANAGER_PATH . '/templates/scripts');
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        //pie chart
        var chart;
        var chartContainer = document.querySelector("#chart");

        // Function to update the chart with data
        function updateChartWithData(data) {
            var options = {
                series: data,
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: ['Current Employee', 'Relieved Employee'],
                //    colors: ['#00ff00', '#ff0000', '#00ffff', '#ff00ff', '#ffff00'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (chart) {
                chart.updateOptions(options);
            } else {
                chart = new ApexCharts(chartContainer, options);
                chart.render();
            }
        }

        // Load data when the page loads
        fetch('<?php echo base_url(); ?>manager/chart/pie-fetch-data')
            .then(response => response.json())
            .then(data => {
                // Update the chart with the retrieved data
                updateChartWithData(data);
            })
            .catch(error => console.error('Error:', error));
    });
    //gender chart
    document.addEventListener("DOMContentLoaded", function() {
        //pie chart
        var chart;
        var chartContainer = document.querySelector("#gen-chart");

        // Function to update the chart with data
        function updateChartWithData(data) {
            var options = {
                series: data,
                chart: {
                    width: 320,
                    type: 'pie',
                },
                labels: ['Male', 'Female', 'Others'],
                //    colors: ['#00ff00', '#ff0000', '#00ffff', '#ff00ff', '#ffff00'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (chart) {
                chart.updateOptions(options);
            } else {
                chart = new ApexCharts(chartContainer, options);
                chart.render();
            }
        }

        // Load data when the page loads
        fetch('<?php echo base_url(); ?>manager/chart/gen-pie-fetch-data')
            .then(response => response.json())
            .then(data => {
                // Update the chart with the retrieved data
                updateChartWithData(data);
            })
            .catch(error => console.error('Error:', error));
    });


    //job type chart
    document.addEventListener("DOMContentLoaded", function() {
        //pie chart
        var chart;
        var chartContainer = document.querySelector("#job-type-chart");

        // Function to update the chart with data
        function updateChartWithData2(data) {
            var options = {
                series: data,
                chart: {
                    width: 330,
                    type: 'pie',
                },
                labels: ['Full time', 'Freelance', 'Intern'],
                //    colors: ['#00ff00', '#ff0000', '#00ffff', '#ff00ff', '#ffff00'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (chart) {
                chart.updateOptions(options);
            } else {
                chart = new ApexCharts(chartContainer, options);
                chart.render();
            }
        }

        // Load data when the page loads
        fetch('<?php echo base_url(); ?>manager/chart/job-pie-fetch-data')
            .then(response => response.json())
            .then(data => {
                // Update the chart with the retrieved data
                updateChartWithData2(data);
            })
            .catch(error => console.error('Error:', error));
    });


    function fetchDataAndUpdateChart() {
        // Make an AJAX request to your CI4 controller endpoint
        fetch('<?php echo base_url(); ?>manager/barchart/bar-fetch-data')
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                // Process the data into an array of objects
                var chartData = [];
                var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                for (var i = 0; i < monthNames.length; i++) {
                    var month = monthNames[i];
                    chartData.push({
                        x: month,
                        linkedin: data[i + 1].linkedin, // Assuming data is 1-based
                        naukri: data[i + 1].naukri, // Assuming data is 1-based
                        employee_referral: data[i + 1].employee_referral,
                    });
                }
                // console.log(chartData);
                // Create the chart using ApexCharts
                var options = {
                    chart: {
                        type: 'bar',
                        height: 600,
                    },
                    plotOptions: {
                        bar: {
                            // horizontal: true,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -3,
                        style: {
                            fontSize: '14px',
                            colors: ['#fff'],
                        },
                    },
                    stroke: {
                        show: true,
                        width: -6,
                        colors: ['#fff'],
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                    },
                    xaxis: {
                        type: 'category',
                        categories: monthNames, // Use three-word month names
                    },
                    // xaxis: {
                    //     title: {
                    //         text: 'Month',
                    //     },
                    // },
                    series: [{
                            name: 'Naukri',
                            data: chartData.map(item => item.naukri),
                        },
                        {
                            name: 'LinkedIn',
                            data: chartData.map(item => item.linkedin),
                        },
                        {
                            name: 'Employee Referral',
                            data: chartData.map(item => item.employee_referral),
                        },
                    ],
                };

                var chart = new ApexCharts(document.querySelector("#bar-chart"), options);
                chart.render();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Call the function to fetch data and update the chart
    fetchDataAndUpdateChart();


    function fetchDataAndUpdatefinanceChart() {
        // Make an AJAX request to your CI4 controller endpoint
        fetch('<?php echo base_url(); ?>manager/financechart/finance-fetch-data')
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                // Process the data into an array of objects
                var chartData = [];
                var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


                monthNames.forEach(function(month) {
                    var income = data.income[month] ? data.income[month] : 0;
                    var expense = data.expense[month] ? data.expense[month] : 0;

                    chartData.push({
                        x: month,
                        income: income,
                        expense: expense
                    });
                });

                // Create the chart using ApexCharts
                var options = {
                    chart: {
                        type: 'bar',
                        height: 600,
                    },
                    plotOptions: {
                        bar: {
                            // horizontal: true,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -3,
                        style: {
                            fontSize: '14px',
                            colors: ['#fff'],
                        },
                    },
                    stroke: {
                        show: true,
                        width: -6,
                        colors: ['#fff'],
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                    },
                    xaxis: {
                        type: 'category',
                        categories: monthNames // Use three-word month names
                    },
                    // xaxis: {
                    //     title: {
                    //         text: 'Month',
                    //     },
                    // },
                    series: [{
                        name: 'Income',
                        data: chartData.map(item => item.income)
                    }, {
                        name: 'Expense',
                        data: chartData.map(item => item.expense)
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#finance-bar-chart"), options);
                chart.render();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Call the function to fetch data and update the chart
    fetchDataAndUpdatefinanceChart();
</script>