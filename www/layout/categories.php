<div class="categories-overview">
    <ul>
        <?php

        foreach($categories as $category){
            $tmp_category_month_spending         = $render->getMonthSpendingPerCategory('1', $category['id'] ,$currentMonth, $currentYear);

            $tmp_category_last_month_spending    = $render->getMonthSpendingPerCategory('1', $category['id'] ,$previousMonth, $currentYear);
            $tmp_category_2x_month_spending      = $render->getMonthSpendingPerCategory('1', $category['id'] ,$previousMonth -1 , $currentYear);
            $tmp_category_3x_month_spending      = $render->getMonthSpendingPerCategory('1', $category['id'] ,$previousMonth -2, $currentYear);

            $tmp_category_compare_last_month    = $tmp_category_month_spending - $tmp_category_last_month_spending;
            $tmp_category_trend                 = ( ( $tmp_category_compare_last_month ) / $tmp_category_last_month_spending ) * 100;

            $tmp_category_year_spending         = $render->getMonthSpendingPerCategory('1', $category['id'] ,$currentMonth, $previousYear);

            if( $tmp_category_month_spending > 0 ) {
                ?>

                <li>
                    <a href="category-view.php?category=<?php echo $category['id']; ?>">
                        <div class="category-image"></div>

                        <div class="category-name">
                            <p><?php echo $category['name']; ?></p>
                            <p><?php echo $category['id']; ?></p>
                        </div>

                        <div class="category-amount">
                            <p class="amount" style="text-align:right;">£<?php echo $tmp_category_month_spending; ?></p>
                            <p style="font-size: 8px; text-align: right;"><?php echo round($tmp_category_trend, 2); ?> % (£<?php echo $tmp_category_last_month_spending; ?>)</p>
                        </div>
                    </a>
                </li>

                <!--                        <li><p>Last month:--><?php //echo $tmp_category_last_month_spending;
                ?><!--</p></li>-->
                <!--                        <li><p>Last year:--><?php //echo $tmp_category_year_spending;
                ?><!--</p></li>-->

                <?php
            }
        }

        ?>

    </ul>
</div>