<div id="transaction_table">
    <ul>
        <?php
        $i = 0;
        $amount = 0;
        $income = 0;
        $currentDay;
        while ($donnees = $reponse->fetch()) {
        $debit      = strpos($donnees['amount'],'-');
        $day        = $donnees['day'];
        if($day != $currentDay){
        $currentDay = $day;
        ?>
    </ul>

    <div class="day-title">
        <p><?php echo $donnees['day']; ?></p>
    </div>
    <ul>
        <?php
        }
        ?>

        <li class="<?php if($debit === false){ echo 'credit'; }else{ echo 'debit'; }?>">
            <a href="transaction-view.php?id=<?php echo $donnees['id']; ?>">
                <div class="transaction-image"></div>

                <div class="transaction-name">
                    <p><?php echo $donnees['name']; ?></p>
                    <span class="category"><?php echo $donnees['category_id']; ?></span>
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
        }else{
            $income += $donnees['amount'];
        }
        }

        $reponse->closeCursor();

        ?>
    </ul>
</div>