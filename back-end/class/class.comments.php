<?php

class Comments extends DB_connect{
	public $comment_count;    //Кількість коментарів у базі даних
	public $comment_post_id;  //ID поста для виведення / відправлення коментарів
	public $comment_user_id;  //ID користувача для відправки коментарів
	
	//Конструктор
	public function __construct($post_id, $user_id){
		parent::__construct();
		$this->comment_post_id = $post_id;
		$this->comment_count   = $this->db->query("SELECT COUNT(*) FROM `comments` WHERE `comment_post_id` = {$this->comment_post_id}")->fetchColumn(0);
		$this->comment_user_id = $user_id;
	}
	
	//Вивід коментарів
	public function get_comments(){
		$select_comments = $this->db->query("SELECT `comment_text`, `comment_date`, `u_login`, `u_avatar` FROM `comments`, `user` WHERE `comment_post_id` = {$this->comment_post_id} AND `comment_user_id` = `u_id` ORDER BY `comment_date`");
		
		echo "<section><h2>Коментарі</h2>";
		while($comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
			echo <<<COMMENT
				<article class="comment">
					<header>
						<img src="{$comment["u_avatar"]}">
						<strong class="author">{$comment["u_login"]}</strong>
						<time datetime="{$comment["comment_date"]}">{$comment["comment_date"]}</time>
					</header>
					{$comment["comment_text"]}
				</article>
COMMENT;
		}
		echo "</section>";
	}
	
	//Форма відправлення коментарів
	public function print_form(){
		echo <<<FORM
		<h2>Додати коментар</h2>
		<form method="post" action="{$_SERVER["PHP_SELF"]}?{$_SERVER["QUERY_STRING"]}" class="comment_form">
			<input type="hidden" id="comment_post_id" name="comment_post_id" value="{$this->comment_post_id}">
			<input type="hidden" id="comment_user_id" name="comment_user_id" value="{$this->comment_user_id}">
			<textarea id="comment_text" name="comment_text"></textarea>
			<input type="submit" id="comment_send" name="comment_send" value="Відправити">
		</form>
FORM;
	}
	
	//Запис коментарів у базу даних
	public function write_comment(){
		$send_comment_text    = strip_tags(trim($_POST["comment_text"]));
		$send_comment_post_id = strip_tags(trim($_POST["comment_post_id"]));
		$send_comment_user_id = strip_tags(trim($_POST["comment_user_id"]));
		
		if($send_comment_text){
			$insert_comment = $this->db->query("INSERT INTO `comments`(`comment_text`, `comment_date`, `comment_user_id`, `comment_post_id`) VALUE('$send_comment_text', NOW(), $send_comment_user_id, $send_comment_post_id)");
			if(!$insert_comment){
				throw new Exception("Помилка додавання коментарів, спробуйте пізніше");
			}
		} else {
			throw new Exception("Ви не ввели текст коментаря");
		}
	}
}

?>