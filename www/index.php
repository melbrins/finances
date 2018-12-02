<!DOCTYPE html>
<html lang="en">
<head>

    <title>Dashboard</title>

	<?php include 'layout/head.php'; ?>

</head>

<body id="index">

    <?php include 'layout/header.php'; ?>

	<?php

        $account    = ($_GET['account']) ? $_GET['account'] : 0;

		$categories = $render->getAllCategories();

		$currentYear   = date('Y');
		$previousYear  = $currentYear - 1;

        $currentMonth   = date('m');
        $previousMonth  = $currentMonth - 1;

        $currentDay     = date('d');

        $reponse  	= $render->getTransactions($account, '2018-'.$previousMonth.'-01', '2018-'.$previousMonth.'-31');

		$months     = array( "9", "10", "11", "12");
		$maxSpent   = $render->getMonthSpendingRange($account, $months, $previousYear);

        $previousYearSpendingAverage = $render->yearAverage($account,$previousYear);
		$currentYearSpendingAverage  = $render->yearAverage($account, $currentYear);

        $evolutionSpendingAverage = - 100 + ( $currentYearSpendingAverage * 100 ) / $previousYearSpendingAverage;
        $evolutionSpendingAverage = sprintf("%.2f%%", $evolutionSpendingAverage);

		$currentMonthSpending   = $render->getMonthSpending($account, $currentMonth, $currentYear);
        $previousMonthSpending  = $render->getMonthSpending($account, $previousMonth, $currentYear);

        $evolutionMonthSpending = - 100 + ( $currentMonthSpending * 100 ) / $previousMonthSpending;
        $evolutionMonthSpending = sprintf("%.2f%%", $evolutionMonthSpending);

        $YoY =  $render->yearOnYear($account, '2018', 'debit');

        $lastMonthSpending_array = $render->spendingMonthToDate($account, $currentYear, $previousMonth, $currentDay);
        $lastMonthSpendingToDate = array_sum($lastMonthSpending_array);
        $nbrTransactionLastMonth = array_sum($render->nbrTransaction($account, $currentYear . '-' . $previousMonth . '-01', $currentYear . '-' . $previousMonth . '-' . $currentDay));

        $lastTransaction = $render->lastTransaction($account);
    ?>
    <script>
        YoY = <?php print(json_encode($YoY)); ?>;
        lastMonthSpending_array = <?php print(json_encode($lastMonthSpending_array)); ?>;
        window.account = <?php ($_GET['account']) ? print($_GET['account']) : print("'all'"); ?>;
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

        <?php include 'Components/Chart_IncomeVsSpending/template/incomeVsSpending.php'; ?>


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

    </div>

</body>

<?php include 'layout/after-body.php'; ?>

</html>

