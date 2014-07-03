<?php
	class Rate extends DB_connect {
		public $rate_send;

		//Construct
		public function __construct() {
			//Parent construct connect to DB
			parent::__construct();
		}

		//Print form for rate post
		public function form_rate() {
			//Set user ID and post ID
			$rate_user_id = $_SESSION['user_id'];
			$rate_post_id = $_GET['id'];
			//Check double vote
			$double = $this->db->query("SELECT COUNT(*) FROM `rate` WHERE `r_user` = {$rate_user_id} AND `r_post` = {$rate_post_id}")->fetchColumn(0);
			//If double vote then save rate value
			if ($double) {
				$rate_id = $this->db->query("SELECT `r_id` FROM `rate` WHERE `r_user` = {$rate_user_id} AND `r_post` = {$rate_post_id}")->fetchColumn(0);
				$rate_value = $this->db->query("SELECT `r_value` FROM `rate` WHERE `r_user` = {$rate_user_id} AND `r_post` = {$rate_post_id}")->fetchColumn(0);
			}

			if ($_SESSION["user_role"] == "admin") {
				$form_delete_all = <<<FORM
					<form action="" method="post" class="rate_form">
						<input type="hidden" name="rate_post_id" value="{$_GET['id']}">
						<input type="submit" name="rate_del_all" value="Delete all votes">
					</form>
FORM;
			}
			else {
				$form_delete_all = NULL;
			}

			//If vote send
			if ($double) {
				return <<<FORM
					<form action="" method="post" class="rate_form">
						<input type="hidden" name="rate_id" value="$rate_id">
						<label>Your assessment of the material: $rate_value</label>
						<input type="submit" name="rate_del" value="Delete">
					</form>
					$form_delete_all
FORM;
			}
			//Else
			return <<<FORM
				<form action="" method="post" class="rate_form">
					<input type="hidden" name="rate_user_id" value="{$_SESSION['user_id']}">
					<input type="hidden" name="rate_post_id" value="{$_GET['id']}">
					<select name="rate_val">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3" selected>3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
					<input type="submit" name="rate_go" value="Rate">
				</form>
				$form_delete_all
FORM;
		}

		//Write rate to DB
		public function write_rate() {
			$rate_user_id = $_POST['rate_user_id'];
			$rate_post_id = $_POST['rate_post_id'];
			$rate_value   = $_POST['rate_val'];

			//Check double vote
			$double = $this->db->query("SELECT COUNT(*) FROM `rate` WHERE `r_user` = {$rate_user_id} AND `r_post` = {$rate_post_id}")->fetchColumn(0);
			//If double vote
			if ($double) {
				throw new Exception("You have already voted for this news");
			}

			$insert_rate  = $this->db->exec("INSERT INTO `rate` (`r_user`, `r_post`, `r_value`) VALUE ($rate_user_id, $rate_post_id, $rate_value)");

			if ($insert_rate) {
				$_SESSION["user_rate"] = "Y";
			}
		}

		//Delete own rate from DB
		public function delete_rate() {
			$rate_id = $_POST['rate_id'];
			$delete_rate  = $this->db->exec("DELETE FROM `rate` WHERE `r_id` = $rate_id");
		}

		//Delete all rate to this news from DB
		public function delete_all_rate() {
			$rate_post_id = $_POST['rate_post_id'];
			$delete_rate  = $this->db->exec("DELETE FROM `rate` WHERE `r_post` = $rate_post_id");
		}

		//Print message for vote
		public function message_rate() {
			if ($_SESSION["user_rate"] == "Y") {
				echo "<p class=\"ok\">Thank you for vote</p>";
				$_SESSION["user_rate"] = "N";
			}
		}

		//Average rate and count votes
		public function av_rate() {
			$rate_post_id = $_GET['id'];
			$count_votes  = $this->db->query("SELECT COUNT(*) FROM `rate` WHERE `r_post` = $rate_post_id")->fetchColumn(0);

			if ($count_votes) {
				$avg_rates    = $this->db->query("SELECT AVG(`r_value`) FROM `rate` WHERE `r_post` = $rate_post_id")->fetchColumn(0);
				return "Average rate: $avg_rates, count votes: $count_votes";
			}
			else {
				return "For this news nobody voted";
			}
		}
	}
?>
