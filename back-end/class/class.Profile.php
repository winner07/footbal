<?php

class Profile extends DB_connect {
	//User Id
	protected $user_id;

	public function __construct($user_id){
		parent::__construct();
		$this->user_id = $user_id;
	}

	//Get info about user
	public function get_info(){
		$user = $this->db->query("SELECT * FROM `user` WHERE `u_id` = {$this->user_id}")->fetch(PDO::FETCH_ASSOC);
		
		if ($_SESSION["user_id"] == $user['u_id']) {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>User: {$user['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$user['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>Full name: <b>{$user['u_name']} {$user['u_surname']}</b></p>
		          <p>E-mail: <b>{$user['u_email']}</b></p>
		          <hr>
		          <p>Registration date: <b>{$user['u_date_reg']}</b></p>
		          <p>Last logged: <b>{$user['u_date_logged']}</b></p>
		          <hr>
		          <p><a href="edit_profile.php">Edit profile</a></p>
		          <p><a href="delete_profile.php">Delete profile</a></p>
		        </div>
		    </article>
USER;
		}
		else {
			echo <<<USER
				<article class="user_profile">
		      <header>
		        <h2>User: {$user['u_login']}</h2>
		      </header>
		        <div class="user_avatar">
		          <img src="{$user['u_avatar']}" width="150" height="150">
		        </div>
		        <div class="user_info">
		          <p>Full name: <b>{$user['u_name']} {$user['u_surname']}</b></p>
		          <hr>
		          <p>Registration date: <b>{$user['u_date_reg']}</b></p>
		          <p>Last logged: <b>{$user['u_date_logged']}</b></p>
		        </div>
		    </article>
USER;
		}
	}
}

?>