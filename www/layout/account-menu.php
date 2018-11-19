<ul id="account-menu">
    <?php
        $accounts  	= $render->getAllAccounts();
        foreach ($accounts as $account){
    ?>

            <li>
                <a href="index.php?account=<?php echo $account['id']; ?>">
                    <dt>
                        <?php echo $account['type']; ?> - <?php echo $account['name']; ?>
                    </dt>
                    <dd>£3,798.47</dd>
                </a>
            </li>
    <?php

        }
    ?>
</ul>