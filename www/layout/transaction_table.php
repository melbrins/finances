<div id="transaction_table">

        <?php
            $currentDay = 'start';
            $i          = 0;
            $amount     = 0;
            $income     = 0;


            while ($donnees = $reponse->fetch()) {
                $debit      = strpos($donnees['amount'],'-');
                $day        = $donnees['day'];


                // Transaction view page - Avoid duplicate transaction
                if( $donnees['id'] != $transactionId) {


                    if ($day != $currentDay){

                            if($currentDay != 'start'){

                            $currentDay = $day;
                        ?>

                                </ul>
                            </div>

                        <?php }else{ $currentDay = 'started'; } ?>

                        <div class="day-wrapper">
                            <div class="day-title">
                                <p><?php echo $donnees['day']; ?></p>
                                <span class="day-amount">- £<?php echo $amount; ?></span>
                            </div>

                            <ul>

                        <?php
                        if (strpos($donnees['amount'], '-') !== false) {
                            $amount = 0;
                        } else {
                            $amount -= $donnees['amount'];
                        }
                    }

                    ?>

                    <li class="<?php if ($debit === false) {
                        echo 'credit';
                    } else {
                        echo 'debit';
                    } ?>">

                        <a href="transaction-view.php?id=<?php echo $donnees['id']; ?>">
                            <div class="transaction-image"></div>

                            <div class="transaction-name">
                                <p><?php echo $donnees['name']; ?></p>
                                <span class="category">category: <?php echo $donnees['category_id']; ?></span>
                                <span class="account">account: <?php echo $donnees['account_id']; ?></span>
                            </div>

                            <div class="transaction-amount">
                                <p class="amount"><?php echo $donnees['amount']; ?></p>
                            </div>
                        </a>
                    </li>

                    <?php
                    $i++;

                    if (strpos($donnees['amount'], '-') !== false) {
                        $amount -= $donnees['amount'];
                    } else {
                        $income += $donnees['amount'];
                    }
                }
            }

            $reponse->closeCursor();

        ?>
    </ul>
</div>