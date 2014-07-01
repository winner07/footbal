<aside class="right_sb">
	<div class="widget">
		<h2><?php echo $translate->get_word('tournament_table'); ?></h2>
			<?php
		    $right_SB = new Right_sidebar();
				$l_table  = isset($_GET["category"]) ? $_GET["category"] : 13;
				$right_SB->print_l_table($l_table);
			?>
	</div>
</aside>