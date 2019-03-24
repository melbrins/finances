<div id="transaction_table">

        <?php
            $currentDay = 'start';
            $i          = 0;
            $amount     = 0;
            $income     = 0;

            while ($donnees = $reponse->fetch()) {
                $debit          = strpos($donnees['amount'],'-');
                $day_id         = $donnees['day_id'];
                $day            = $donnees['day'];
                $dayData        = $render->getDay($day, $account);


                // Transaction view page - Avoid duplicate transaction
                if( $donnees['id'] != $transactionId) {


                    if ($day_id != $currentDay){

                            if ($currentDay != 'start'){
                        ?>

                                </ul>
                            </div>

                        <?php
                            }

                            $currentDay = $day_id;
                        ?>

                        <div class="day-wrapper">
                            <div class="day-title">
                                <p><?= $render->getDate($dayData['day']); ?></p>
                                <span class="day-amount"><?= $dayData['dayTotal']; ?></span>
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
                                <span class="category">category: <?= $render->getCategoryName($donnees['category_id']); ?></span>
                                <span class="account">account: <?= $render->getAccountName($donnees['account_id']); ?> (<?= $donnees['account_id'];?>)</span>
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