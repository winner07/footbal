<?php

class Profile extends DB_connect {
	//User Id
	public $user_id;
	//User info array
	public $user_info;

	public function __construct($user_id) {
		parent::__construct();
		$this->user_id   = $user_id;

		if ($this->user_id) {
			$this->user_info = $this->db->query("SELECT * FROM `user` WHERE `u_id` = {$this->user_id}")->fetch(PDO::FETCH_ASSOC);
		}
	}

	//Get full info about user
	public function get_full_info() {
		if ($_SESSION["user_id"] == $this->user_info['u_id']) {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>User: {$this->user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$this->user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>Full name: <b>{$this->user_info['u_name']} {$this->user_info['u_surname']}</b></p>
		          <p>E-mail: <b>{$this->user_info['u_email']}</b></p>
		          <hr>
		          <p>Registration date: <b>{$this->user_info['u_date_reg']}</b></p>
		          <p>Last logged: <b>{$this->user_info['u_date_logged']}</b></p>
		          <hr>
		          <p><a href="edit_profile.php">Edit profile</a></p>
		          <p><a href="#" id="del_profile_link">Delete profile</a></p>
		        </div>
		    </article>
		    <div id="del_profile" title="Delete profile" data-uid="{$_SESSION["user_id"]}">
		    	<p>Are you sure to delete your profile?</p>
		    </div>
USER;
		}
		else {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>User: {$this->user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$this->user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>Full name: <b>{$this->user_info['u_name']} {$this->user_info['u_surname']}</b></p>
		          <hr>
		          <p>Registration date: <b>{$this->user_info['u_date_reg']}</b></p>
		          <p>Last logged: <b>{$this->user_info['u_date_logged']}</b></p>
		        </div>
		    </article>
USER;
		}
	}

	//Get full info about user onto admin panel
	public function get_full_info_admin() {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>User: {$this->user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="../{$this->user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>Full name: <b>{$this->user_info['u_name']} {$this->user_info['u_surname']}</b></p>
		          <p>E-mail: <b>{$this->user_info['u_email']}</b></p>
		          <hr>
		          <p>Registration date: <b>{$this->user_info['u_date_reg']}</b></p>
		          <p>Last logged: <b>{$this->user_info['u_date_logged']}</b></p>
		        </div>
		    </article>
USER;
	}

	//Check permission for user
	public function check_permission($action) {
		$perm_role = $this->user_info['u_role'] ? $this->user_info['u_role'] : "guest";
		$perm_sel  = $this->db->query("SELECT `$action` FROM `permission` WHERE `p_role` = '$perm_role'")->fetchColumn(0);
		return $perm_sel == "Y" ? true : false;
	}
}

?>