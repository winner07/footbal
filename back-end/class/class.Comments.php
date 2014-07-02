<?php

class Comments extends DB_connect {
	public $comment_count;       //Кількість коментарів у базі даних
	protected $comment_post_id;  //ID поста для виведення / відправлення коментарів
	protected $comment_user_id;  //ID користувача для відправки коментарів
	protected $n_page;           //Номер сторінки 
	protected $comm_on_page;     //Кількість новин на сторінці 
	
	//Конструктор
	public function __construct($post_id, $user_id) {
		parent::__construct();
		$this->comment_post_id = $post_id;
		$this->comment_count   = $this->db->query("SELECT COUNT(*) FROM `comments` WHERE `comment_post_id` = {$this->comment_post_id} AND `comment_lang` = '{$_SESSION['user_lang']}'")->fetchColumn(0);
		$this->comment_user_id = $user_id;
	}
	
	//Вивід коментарів
	public function get_comments($n_page, $comm_on_page) {
		//Визначення номера сторінки
		$this->n_page        = isset($n_page) ? $n_page : 1;
		$this->comm_on_page  = $comm_on_page;
		$start_offset        = $n_page <= 1 ? 0 : ($n_page - 1) * $comm_on_page;

		$select_comments = $this->db->query("SELECT `comment_text`, `comment_topic`, `comment_date`, `comment_user_id`, `u_login`, `u_avatar` FROM `comments`, `user` WHERE `comment_post_id` = {$this->comment_post_id} AND `comment_user_id` = `u_id` AND `comment_lang` = '{$_SESSION['user_lang']}' ORDER BY `comment_date` LIMIT $start_offset, $comm_on_page");
		
		echo "<section><h2>Comments</h2>";
		while ($comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
			echo <<<COMMENT
				<article class="comment">
					<header>
						<img src="{$comment["u_avatar"]}" width="50" height="50">
						<strong class="author"><a href="profile.php?id={$comment["comment_user_id"]}">{$comment["u_login"]}</a></strong>
						<time datetime="{$comment["comment_date"]}">{$comment["comment_date"]}</time>
						<h3>{$comment["comment_topic"]}</h3>
					</header>
					{$comment["comment_text"]}
				</article>
COMMENT;
		}
		echo "</section>";
	}
	
	//Форма відправлення коментарів
	public function print_form() {
		echo <<<FORM
		<h2>Add comment</h2>
		<form method="post" action="{$_SERVER["PHP_SELF"]}?{$_SERVER["QUERY_STRING"]}" class="comment_form">
			<input type="hidden" name="comment_post_id" value="{$this->comment_post_id}">
			<input type="hidden" name="comment_lang" value="{$_SESSION['user_lang']}">
			<input type="hidden" name="comment_user_id" value="{$this->comment_user_id}">
			<label for="comment_topic">Topic</label>
			<input type="text" id="comment_topic" name="comment_topic">
			<label for="comment_text">Message</label>
			<textarea id="comment_text" name="comment_text"></textarea>
			<input type="submit" id="comment_send" name="comment_send" value="Send">
		</form>
FORM;
	}
	
	//Запис коментарів у базу даних
	public function write_comment() {
		$comment_topic   = strip_tags(trim($_POST["comment_topic"]));
		$comment_text    = strip_tags(trim($_POST["comment_text"]));
		$comment_post_id = strip_tags(trim($_POST["comment_post_id"]));
		$comment_lang    = strip_tags(trim($_POST["comment_lang"]));
		$comment_user_id = strip_tags(trim($_POST["comment_user_id"]));
		
		if ($comment_text) {
			//Обрезка строки до 15 символ включая последнее слово
			if (!$comment_topic) {
				$end = mb_strlen($comment_text, 'UTF-8') >= 15 ? 15 : mb_strlen($comment_text, 'UTF-8');
				$comment_topic = mb_substr($comment_text, 0, $end, 'UTF-8');
				if (mb_strlen($comment_topic, 'UTF-8') == 15) {
					$comment_topic = mb_substr($comment_text, 0, mb_strpos($comment_text, ' ', 15), 'UTF-8');
				}
			}

			//Экранирование данных
			$comment_topic = $this->db->quote($comment_topic);
			$comment_text  = $this->db->quote($comment_text);
			$comment_lang  = $this->db->quote($comment_lang);

			//Вставка комментария в базу данных
			$insert_comment = $this->db->query("INSERT INTO `comments` (`comment_text`, `comment_topic`, `comment_date`, `comment_user_id`, `comment_post_id`, `comment_lang`) VALUE($comment_text, $comment_topic, NOW(), $comment_user_id, $comment_post_id, $comment_lang)");
			if (!$insert_comment) {
				throw new Exception("Помилка додавання коментарів, спробуйте пізніше");
			}
		} else {
			throw new Exception("Ви не ввели текст коментаря");
		}
	}

	//Вивід навігації
	public function print_nav($pos_pages) {
		//Налаштування позицій
		$max_pages = ceil($this->db->query("SELECT COUNT(*) FROM `comments` WHERE `comment_post_id` = {$this->comment_post_id} AND `comment_lang` = '{$_SESSION['user_lang']}'")->fetchColumn(0) / $this->comm_on_page);
		$pos_pages = $pos_pages > $max_pages ? $max_pages : $pos_pages;
		$n_page    = $this->n_page;
		
		//Встановлення номера сторінки
		if ($n_page > $max_pages) {
			$n_page = $max_pages;
		} 
		elseif (!isset($n_page) || $n_page < 1) {
			$n_page = 1;
		} 
		else {
			$n_page = $n_page;
		}
		
		//Зсув з боків сторінки
		$offset = floor($pos_pages / 2);
		
		//Налаштування зміщень
		if ($n_page - $offset <= 1) {
			$start = 1;
			$end = 1 + ($pos_pages - 1);
		} else {
			$start = $n_page - $offset;
		}
		
		if ($n_page + $offset >= $max_pages) {
			$start = $max_pages - ($pos_pages - 1);
			$end = $max_pages;
		} elseif ($start != 1) {
			$end = $n_page + $offset;
		}
		
		//Вивід
		if ($max_pages != 1) {
			echo "<ul id=\"page_nav\">";
			
			if ($start != 1) {
				echo "<li><a href=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}\">1</a></li>";
				echo "<li class=\"ellips\">...</li>";
			}
			
			for ($i = $start; $i <= $end; $i++) {
				if ($i == $n_page) {
					echo "<li class=\"current_page\">$i</li>";
					continue;
				}
				echo "<li><a href=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&comment_page=$i\">$i</a></li>";
			}
			
			if ($end != $max_pages) {
				echo "<li class=\"ellips\">...</li>";
				echo "<li><a href=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&comment_page=$max_pages\">$max_pages</a></li>";
			}
			
			echo "</ul>";
		}
	}
}

?>