<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body id="index">

    <?php include 'layout/header.php'; ?>

	<?php

		$import  	= new import();

		$render  	= new render(); 



		$accounts  	= $render->getAllAccounts();
		$categories = $render->getAllCategories();

		$currentYear   = date('Y');
		$previousYear  = $currentYear - 1;

        $currentMonth   = date('m');
        $previousMonth  = $currentMonth - 1;

        $currentDay     = date('d');

        $reponse  	= $render->getTransactions('1', '2018-'.$currentMonth.'-01', '2018-'.$currentMonth.'-31');

		$months     = array( "9", "10", "11", "12");
		$maxSpent   = $render->getMonthSpendingRange("1", $months, $previousYear);

        $previousYearSpendingAverage = $render->yearAverage('1',$previousYear);
		$currentYearSpendingAverage  = $render->yearAverage('1', $currentYear);

        $evolutionSpendingAverage = - 100 + ( $currentYearSpendingAverage * 100 ) / $previousYearSpendingAverage;
        $evolutionSpendingAverage = sprintf("%.2f%%", $evolutionSpendingAverage);

		$currentMonthSpending   = $render->getMonthSpending('1', $currentMonth, $currentYear);
        $previousMonthSpending  = $render->getMonthSpending('1', $previousMonth, $currentYear);

        $evolutionMonthSpending = - 100 + ( $currentMonthSpending * 100 ) / $previousMonthSpending;
        $evolutionMonthSpending = sprintf("%.2f%%", $evolutionMonthSpending);

        $YoY =  $render->yearOnYear('1', '2018', 'debit');

        $lastMonthSpending_array = $render->spendingMonthToDate('1', $currentYear, $previousMonth, $currentDay);
        $lastMonthSpendingToDate = array_sum($lastMonthSpending_array);
        $nbrTransactionLastMonth = array_sum($render->nbrTransaction('1', $currentYear . '-' . $previousMonth . '-01', $currentYear . '-' . $previousMonth . '-' . $currentDay));

        $lastTransaction = $render->lastTransaction('1');

	?>
    <script>

        YoY = <?php print(json_encode($YoY)); ?>;
        lastMonthSpending_array = <?php print(json_encode($lastMonthSpending_array)); ?>;
        console.log(YoY);
    </script>

    <div class="wrapper">
        <h4>Dashboard</h4>
        <h1>Finance Overview</h1>

        <div id="root"></div>

        <div class="overview grid-4">
            <section>
                <h4>Last Transaction</h4>
                <p><?php echo $lastTransaction['name']; ?></p>
            </section>
            <section>
                <h3 style="display:block;text-align:center;">Current Month Spending</h3>
                <div class="canvas-wrapper">
                    <canvas id="monthToDate2" style="position:absolute; top:0; left:0; width: 100%; height: 100%;"></canvas>
                </div>
            </section>

            <section class="monthly-average">
                <h4>Average Monthly Spending</h4>
                <h2 class="positive-amount amount">£<?php echo money_format('%.2n', $currentYearSpendingAverage); ?></h2>
                <h3 class="<?php if(strpos('-', $evolutionSpendingAverage)){ ?> negative-amount <?php }else{ ?> positive-amount <?php } ?>" ><?php echo $evolutionSpendingAverage; ?></h3>
                <p>
                    Compare to last year:
                    <span class="amount">£<?php echo money_format('%.2n', $previousYearSpendingAverage); ?></span>
                </p>
            </section>

            <section class="monthly-average">
                <h4>Current Monthly Spending</h4>
                <h2 class="positive-amount amount">£<?php echo money_format('%.2n', $currentMonthSpending); ?></h2>
                <h3 class="<?php if(strpos('-', $evolutionSpendingAverage)){ ?> negative-amount <?php }else{ ?> positive-amount <?php } ?>" ><?php echo $evolutionMonthSpending; ?></h3>
                <p>
                    Compare to last month:
                    <span class="amount">£<?php echo money_format('%.2n', $previousMonthSpending); ?></span>
                </p>
            </section>

            <section class="monthly-average">
                <h4>This time last month</h4>
                <h2 class="positive-amount amount">£<?php echo money_format('%.2n', $lastMonthSpendingToDate); ?></h2>
                <p>
                    with <?php echo $nbrTransactionLastMonth; ?> transaction(s)
                </p>
            </section>
        </div>

        <?php include 'layout/categories.php'; ?>

        <section class="Dashboard">
            <h2>Income Vs Spending</h2>
            <div class="canvas-wrapper">
                <div class="canvas-wrapper">
                    <canvas id="incomeVsSpending"></canvas>
                </div>
            </div>
        </section>

        <section class="Dashboard">
            <h2>Year on Year</h2>
            <div class="canvas-wrapper">
                <div class="canvas-wrapper">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </section>


        <!-- SPENDING MONTH TO DATE -->
        <section>
            <h2> SPENDING MONTH TO DATE </h2>
            <form id="MonthtoDate" class="mtd-type" action="index.php">
                <input type="hidden" id="chart" name="chart" value="monthToDate"/>
                <select id="mtd-type" class="chartType" name="chartType">
                    <option value="line">Line</option>
                    <option value="bar">Bar</option>
                    <option value="horizontalBar">Horizontal Bar</option>
                    <option value="radar">Radar</option>
                    <option value="polarArea">Polar Area</option>
                    <option value="doughnut">Doughnut</option>
                    <option value="pie">Pie</option>
                    <option value="bubble">Bubble</option>
                    <option value="scatter">Scatter</option>
                </select>

                <button type="submit">submit</button>
            </form>
            <div class="canvas-wrapper">
                <canvas id="monthToDate"></canvas>
            </div>
        </section>

        <!-- SPENDING MONTH TO DATE PER CATEGORY -->
        <section>
            <h2> SPENDING MONTH TO DATE PER CATEGORY </h2>
            <form id="spendingCategory" action="index.php">
                <input type="text" id="rangeDate" class="daterangepicker">
                <button type="submit">Submit</button>
            </form>
            <div class="canvas-wrapper">
                <canvas id="monthSpentCategory"></canvas>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

        </section>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <?php include 'layout/transaction_table.php'; ?>

        <section class="transactions" style="position: relative; padding-top: 40px;">

            <div style="width:100%; height: 40px; background-color: rgba(0,0,0,0.8); color: #fff; position:absolute; top:0; left:0;box-sizing: border-box; padding: 10px;"><span style="font-size: 14px; color: #fff;">Nbr Transactions: <?php echo $i; ?></span></div>
            <div style="width:100%; height: 40px; background-color: rgba(0,0,0,0.8); color: #fff; position:absolute; top:40px; left:0;box-sizing: border-box; padding: 10px;"><span style="font-size: 14px; color: #fff;">Debit: <?php echo $amount; ?></span></div>
            <div style="width:100%; height: 40px; background-color: rgba(0,0,0,0.8); color: #fff; position:absolute; top:80px; left:0;box-sizing: border-box; padding: 10px;"><span style="font-size: 14px; color: #fff;">Income: <?php echo $income; ?></span></div>

        </section>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <section class="account">
            <table id="account_table">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Number
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                        foreach( $accounts as $account)
                        {

                    ?>

                            <tr>
                                <td>
                                    <?php echo $account['id']; ?>
                                </td>

                                <td>
                                    <?php echo $account['name']; ?>
                                </td>

                                <td>
                                    <?php echo $account['type']; ?>
                                </td>

                                <td>
                                    <?php echo $account['account_number']; ?>
                                </td>
                            </tr>

                    <?php

                        }

                    ?>
                </tbody>
            </table>
            <form method="post" action="block/addAccount.php">

                <label for="account_name">Account Name</label><br>
                <input type="text" name="account_name"><br>

                <label for="account_type">Account Type</label><br>
                <select name="account_type">
                    <option value="personal">Personal</option>
                    <option value="business">Business</option>
                </select><br>

                <label for="account_number">Account Number</label><br>
                <input type="text" name="account_number"><br>

                <br>

                <button type="submit">Add Account</button>
            </form>
        </section>

    </div>

    <!--                    <td>-->
    <!--                        --><?php
    //
    //                        $account_id = str_replace('0', '', $donnees['account_id']);
    //                        $account_id--;
    //                        echo $accounts[$account_id]['name'];
    //
    //                        ?>
    <!--                    </td>-->

</body>

<script type="text/babel">

    class YearOnYear extends React.Component{
        constructor(props){
            super(props);
            this.state = {
                data : {
                    labels: months,
                    datasets: [{
                        label: 'Current Year',
                        borderColor: '#000',
                        fill: '-1',
                        data: YoY.currentYear
                    }]
                }
            }
        }

        render(){
            return(
                <section className="yearOnYear2">
                    <h2>Year on Year</h2>

                    <div className="canvas-wrapper">
                        <canvas className="chart"></canvas>
                    </div>

                </section>
            );
        }
    }


    ReactDOM.render(
        <YearOnYear />,
        document.getElementById('root')
    );


    var ctx = document.getElementById("myChart").getContext('2d');

    var $defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scaleShowVerticalLines: false,
        drawBorder: false,
        tension: 0,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:false
                }
            }],
            xAxes: [{
                display: false
            }]
        }
    };

    $.ajax({

        url: '/block/updateChart.php',

        data: {
            function2call 	: 'yearOnYear',
            year 			: '2018'
        },
        type: 'post',
        dataType: "json",

        success: function(output){

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Current Year',
                        borderColor: '#000',
                        fill: '-1',
                        data: output.currentYear
                    },
                        {
                            label: 'Previous Year',
                            borderColor: '#af0000',
                            fill: '-1',
                            data: output.previousYear
                        }]
                },
                options: $defaultOptions
            });

        }

    });

</script>

<?php include 'layout/after-body.php'; ?>

</html>

