<?php

class Profile extends DB_connect {
	public function __construct() {
		parent::__construct();
	}

	//Get full info about user
	public function get_full_info($user_id) {
		global $translate;
		$user_info = $this->db->query("SELECT * FROM `user` WHERE `u_id` = $user_id")->fetch(PDO::FETCH_ASSOC);

		if ($_SESSION["user_id"] == $user_info['u_id']) {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>{$translate->get_word('user')}: {$user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>{$translate->get_word('full_name')}: <b>{$user_info['u_name']} {$user_info['u_surname']}</b></p>
		          <p>{$translate->get_word('email')}: <b>{$user_info['u_email']}</b></p>
		          <hr>
		          <p>{$translate->get_word('reg_date')}: <b>{$user_info['u_date_reg']}</b></p>
		          <p>{$translate->get_word('last_logg')}: <b>{$user_info['u_date_logged']}</b></p>
		          <hr>
		          <p><a href="edit_profile.php">{$translate->get_word('link_edit_prof')}</a></p>
		          <p><a href="#" id="del_profile_link">{$translate->get_word('link_del_prof')}</a></p>
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
		        <h2>{$translate->get_word('link_edit_prof')}: {$user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>{$translate->get_word('full_name')}: <b>{$user_info['u_name']} {$user_info['u_surname']}</b></p>
		          <hr>
		          <p>{$translate->get_word('reg_date')}: <b>{$user_info['u_date_reg']}</b></p>
		          <p>{$translate->get_word('last_logg')}: <b>{$user_info['u_date_logged']}</b></p>
		        </div>
		    </article>
USER;
		}
	}

	//Get full info about user onto admin panel
	public function get_full_info_admin($user_id) {
		global $translate;
		$user_info = $this->db->query("SELECT * FROM `user` WHERE `u_id` = $user_id")->fetch(PDO::FETCH_ASSOC);

			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>{$translate->get_word('link_edit_prof')}: {$user_info['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="../{$user_info['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>{$translate->get_word('full_name')}: <b>{$user_info['u_name']} {$user_info['u_surname']}</b></p>
		          <p>{$translate->get_word('email')}: <b>{$user_info['u_email']}</b></p>
		          <hr>
		          <p>{$translate->get_word('reg_date')} date: <b>{$user_info['u_date_reg']}</b></p>
		          <p>{$translate->get_word('last_logg')}: <b>{$user_info['u_date_logged']}</b></p>
		        </div>
		    </article>
USER;
	}

	//Check permission for user
	public function check_permission($user_id, $action) {
		$user_id = $user_id ? $user_id : 0;
		$user_role = $this->db->query("SELECT `u_role` FROM `user` WHERE `u_id` = $user_id")->fetchColumn(0);
		$perm_role = $user_role ? $user_role : "guest";
		$perm_sel  = $this->db->query("SELECT `$action` FROM `permission` WHERE `p_role` = '$perm_role'")->fetchColumn(0);
		return $perm_sel == "Y" ? true : false;
	}
}

?>