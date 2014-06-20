<aside class="right_sb">
	<?php
    	$right_SB = new Right_sidebar();
		$l_table = isset($_GET["category"]) ? $_GET["category"] : 13;
		$right_SB->print_l_table($l_table);
	?>
</aside>