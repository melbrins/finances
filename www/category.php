<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body>

<?php include 'layout/header.php'; ?>

	<?php 
		$import  	= new import();
		$render  	= new render();

        $currentYear   = date('Y');
        $previousYear  = $currentYear - 1;

        $currentMonth   = date('m');
        $previousMonth  = $currentMonth - 1;

		$categories = $render->getAllCategories();

	?>

    <?php include 'layout/categories.php'; ?>

	<section class="category">

		<ul class="categories">
            <?php

                foreach( $categories as $category)
                {

            ?>

                    <li class="category">
                        <h3><?php echo $category['name']; ?></h3>

                        <span><?php echo $category['id']; ?></span>

                        <p><?php echo $category['category_trigger']; ?></p>

                        <a href="category-view.php?category=<?php echo $category['id']; ?>">See Transactions</a><br>
                        <a href="">remove</a>
                    </li>

            <?php

                }

            ?>
        </ul>

	</section>

</body>

<?php include 'layout/after-body.php'; ?>

</html>