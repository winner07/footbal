<?php
	class Page_navigator extends DB_connect {

		protected $lang;
		protected $n_page; //Номер сторінки 
		protected $news_on_page; //Кількість новин на сторінці 
		protected $parent_category; //Головна категорія новин 
		protected $list_categories; //Список категорій новин 
		protected $news_category; //Вибрана категорія
		
		//Конструктор
		public function __construct($lang) {
			//Виклик батьківського конструктора підключення бази даних
			parent::__construct();
			$this->lang = $lang;
		}
		
		//Вивід новин для заданої сторінки
		/**
		 * Bla bla bla.
		 *
		 * @param int $news_category
		 *   asfdsdfdsf dsf.
		 * @param object $
		 *   asd as das das .
		 *
		 * @return 
		 */
		public function print_news($news_category, $n_page, $news_on_page) {
			global $translate;
			//Якщо обрана якась категорія
			if (isset($news_category)) {
				$this->news_category = $news_category;
				//Визначення чи є обрана категорія батьківською
				$is_main = $this->db->query("SELECT `cat_parent` FROM `categories` WHERE `cat_id` = $news_category")->fetchColumn(0);
				$is_child = $this->db->query("SELECT COUNT(*) FROM `categories` WHERE `cat_parent` = $news_category")->fetchColumn(0);
				//Якщо категорія головна, то зчитати номера дочірніх категорій
				if (!$is_main && $is_child) {
					$this->parent_category = $news_category;
					$this->list_categories = array();
					$child_categories = $this->db->query("SELECT `cat_id` FROM `categories` WHERE `cat_parent` = $news_category")->fetchAll();
					
					for ($i = 0; $i < count($child_categories); $i++) {
						$this->list_categories[] = $child_categories[$i][0];
					}
					
					$this->list_categories = implode(",", $this->list_categories);
				} else {
					$this->list_categories = $news_category;
				}
			}
			
			//Визначення номера сторінки
			$this->n_page        = isset($n_page) ? $n_page : 1;
			$this->news_on_page  = $news_on_page;
			$start_offset        = $n_page <= 1 ? 0 : ($n_page - 1) * $news_on_page;
			//Визначення кількості новин для обраної категорії
			$posts_count = $this->db->query("SELECT COUNT(*) FROM `posts`". (isset($this->list_categories) ? "WHERE `post_cat_id` IN({$this->list_categories})" : NULL))->fetchColumn(0);
			
			if ($posts_count) {
				$posts_query = $this->db->query("SELECT `post_id`, `post_title_{$this->lang}`, `post_short_desc_{$this->lang}`, `post_cat_id`, `post_date`, `u_id`, `u_login`, `cat_name_{$this->lang}` FROM `posts`, `categories`, `user` WHERE `post_status` = 'post' AND `post_cat_id` = `cat_id`". (isset($this->list_categories) ? " AND `post_cat_id` IN({$this->list_categories})" : NULL) ." AND `post_author_id` = `u_id` ORDER BY `post_date` DESC LIMIT $start_offset, $news_on_page");
				
				if ($posts_query) {
					while ($post = $posts_query->fetch(PDO::FETCH_ASSOC)) {
						$date = explode(" ", $post["post_date"]);
						
						//Обрезка текста до 150 символов
						preg_match("/<p><img.+\/>(.+)<\/p>/", $post["post_short_desc_{$this->lang}"], $short_text);
						$cut_length = 150;

						if (strlen(htmlspecialchars_decode($short_text[1])) > $cut_length) {
							$chars_150 = mb_substr(htmlspecialchars_decode($short_text[1]), 0, $cut_length, 'UTF-8') ."...";
							$post["post_short_desc_{$this->lang}"] = preg_replace("/(<p><img.+\/>).+(<\/p>)/", "$1{$chars_150}$2", $post["post_short_desc_{$this->lang}"]);
						}

						//Вывод новостей
						echo <<<POSTS
							<article class="post">
								<header>
									<h2><a href="index.php?category={$post["post_cat_id"]}">{$post["cat_name_{$this->lang}"]}</a></h2>
									<span class="arrow">&rarr;</span>
									<h2><a href="post.php?category={$post["post_cat_id"]}&id={$post["post_id"]}">{$post["post_title_{$this->lang}"]}</a></h2>
								</header>
								<div class="content">
									{$post["post_short_desc_{$this->lang}"]}
									<a href="post.php?category={$post["post_cat_id"]}&id={$post["post_id"]}">{$translate->get_word('more_link')}</a>
								</div>
								<footer>
									<time datetime="{$post["post_date"]}" pubdate>{$date[0]}</time>
									<span class="author"><a href="profile.php?id={$post["u_id"]}">{$post["u_login"]}</a></span>
								</footer>
							</article>
POSTS;
					}
				} else {
					echo "<p class=\"error\">Помилка виведення новин</p>";
				}
			} else {
				echo "<p class=\"warning\">Новин в цій категорії немає, зайдіть пізніше</p>";
			}
		}
		
		//Вивід повної новини
		public function full_post($post_id) {
			$is_post = $this->db->query("SELECT COUNT(*) FROM `posts` WHERE `post_id` = $post_id")->fetchColumn(0);
			//Якщо новина існує, вивести її
			if ($is_post) {
				$post = $this->db->query("SELECT `post_id`, `post_title_{$this->lang}`, `post_short_desc_{$this->lang}`, `post_full_desc_{$this->lang}`, `post_cat_id`, `post_date`, `post_author_id`, `u_id`, `u_login` FROM `posts`, `user` WHERE `post_status` = 'post' AND `post_id` = $post_id AND `post_author_id` = `u_id` LIMIT 1")->fetch(PDO::FETCH_ASSOC);
				
				if (!$post) {
					throw new Exception("Помилка виведення новини, спробуйте пізніше");
				}
				$date = explode(" ", $post["post_date"]);
				
					echo <<<POSTS
						<article class="post">
							<header>
								<h1>{$post["post_title_{$this->lang}"]}</h1>
							</header>
							<div class="content">
								{$post["post_short_desc_{$this->lang}"]}
								{$post["post_full_desc_{$this->lang}"]}
							</div>
							<footer>
								<time datetime="{$post["post_date"]}" pubdate>{$date[0]}</time>
								<span class="author"><a href="profile.php?id={$post["u_id"]}">{$post["u_login"]}</a></span>
							</footer>
						</article>
POSTS;
			} 
			else {
				throw new Exception("Такої новини немає");
			}
		}
		
		//Вивід навігації
		public function print_nav($pos_pages) {
			//Налаштування позицій
			$max_pages = ceil($this->db->query("SELECT COUNT(*) FROM `posts` WHERE `post_status` = 'post'". (isset($this->list_categories) ? " AND `post_cat_id` IN({$this->list_categories})" : ""))->fetchColumn(0) / $this->news_on_page);
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
			echo "<ul id=\"page_nav\">";
			
			if ($start != 1) {
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}" : "") ."\">1</a></li>";
				echo "<li class=\"ellips\">...</li>";
			}
			
			for ($i = $start; $i <= $end; $i++) {
				if ($i == $n_page) {
					echo "<li class=\"current_page\">$i</li>";
					continue;
				}
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}&" : "?") ."page=$i\">$i</a></li>";
			}
			
			if ($end != $max_pages) {
				echo "<li class=\"ellips\">...</li>";
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}&" : "?") ."page=$max_pages\">$max_pages</a></li>";
			}
			
			echo "</ul>";
		}
	}
?>
