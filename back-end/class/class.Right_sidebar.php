<?php

class Right_sidebar extends DB_connect{
	public function __construct() {
		//Виклик батьківського конструктора підключення бази даних
		parent::__construct();
	}
	
	//Вивід конкретної турнірної таблиці
	public function print_l_table($champ_id = 13) {
		$isset_table = $this->db->query("SELECT COUNT(*) FROM `league_tables` WHERE `l_id` = $champ_id")->fetchColumn(0);
		
		if($isset_table) {
			$select_l_table = $this->db->query("SELECT `l_widget` FROM `league_tables` WHERE `l_id` = $champ_id")->fetchColumn(0);
			echo <<<TABLE_WIDGET
				<div class="widget">
    				<h2>Tournament table</h2>
					$select_l_table
				</div>
TABLE_WIDGET;
		}
	}
}

?>