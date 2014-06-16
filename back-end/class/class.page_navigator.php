<?php
	class Page_navigator extends DB_connect{
		protected $n_page; //����� ������� 
		protected $news_on_page; //ʳ������ ����� �� ������� 
		protected $parent_category; //������� �������� ����� 
		protected $list_categories; //������ �������� ����� 
		protected $news_category; //������� ��������
		
		//�����������
		public function __construct(){
			//������ ������������ ������������ ���������� ���� �����
			parent::__construct();
		}
		
		//���� ����� ��� ������ �������
		public function print_news($news_category, $n_page, $news_on_page){
			//���� ������ ����� ��������
			if(isset($news_category)){
				$this->news_category = $news_category;
				//���������� �� � ������ �������� �����������
				$is_main = $this->db->query("SELECT `cat_parent` FROM `categories` WHERE `cat_id` = $news_category")->fetchColumn(0);
				$is_child = $this->db->query("SELECT COUNT(*) FROM `categories` WHERE `cat_parent` = $news_category")->fetchColumn(0);
				//���� �������� �������, �� ������� ������ ������� ��������
				if(!$is_main && $is_child){
					$this->parent_category = $news_category;
					$this->list_categories = array();
					$child_categories = $this->db->query("SELECT `cat_id` FROM `categories` WHERE `cat_parent` = $news_category")->fetchAll();
					
					for($i = 0; $i < count($child_categories); $i++){
						$this->list_categories[] = $child_categories[$i][0];
					}
					
					$this->list_categories = implode(",", $this->list_categories);
				} else {
					$this->list_categories = $news_category;
				}
			}
			
			//���������� ������ �������
			$this->n_page        = isset($n_page) ? $n_page : 1;
			$this->news_on_page  = $news_on_page;
			$start_offset        = $n_page <= 1 ? 0 : ($n_page - 1) * $news_on_page;
			//���������� ������� ����� ��� ������ �������
			$posts_count = $this->db->query("SELECT COUNT(*) FROM `posts`". (isset($this->list_categories) ? "WHERE `post_cat_id` IN({$this->list_categories})" : NULL))->fetchColumn(0);
			
			if($posts_count){
				$posts_query = $this->db->query("SELECT `post_id`, `post_title`, `post_short_desc`, `post_cat_id`, `post_date`, `u_login`, `cat_name` FROM `posts`, `categories`, `user` WHERE `post_status` = 'post' AND `post_cat_id` = `cat_id`". (isset($this->list_categories) ? " AND `post_cat_id` IN({$this->list_categories})" : NULL) ." AND `post_author_id` = `u_id` ORDER BY `post_date` DESC LIMIT $start_offset, $news_on_page");
				
				if($posts_query){
					while($post = $posts_query->fetch(PDO::FETCH_ASSOC)){
						$date = explode(" ", $post["post_date"]);
						
						//������� ������ �� 150 ��������
						preg_match("/<p><img.+\/>(.+)<\/p>/", $post["post_short_desc"], $short_text);
						
						if(strlen(htmlspecialchars_decode($short_text[1])) > 150){
							$chars_150 = substr(htmlspecialchars_decode($short_text[1]), 0, 150) ."...";
							$post["post_short_desc"] = preg_replace("/(<p><img.+\/>).+(<\/p>)/", "$1{$chars_150}$2", $post["post_short_desc"]);
						}
						
						//����� ��������
						echo <<<POSTS
							<article class="post">
								<header>
									<h2><a href="index.php?category={$post["post_cat_id"]}">{$post["cat_name"]}</a></h2>
									<span class="arrow">&rarr;</span>
									<h2><a href="post.php?category={$post["post_cat_id"]}&id={$post["post_id"]}">{$post["post_title"]}</a></h2>
								</header>
								<div class="content">
									{$post["post_short_desc"]}
									<a href="post.php?category={$post["post_cat_id"]}&id={$post["post_id"]}">READ MORE</a>
								</div>
								<footer>
									<time datetime="{$post["post_date"]}" pubdate>{$date[0]}</time>
									<span class="author">{$post["u_login"]}</span>
								</footer>
							</article>
POSTS;
					}
				} else {
					echo "<p class=\"error\">������� ��������� �����</p>";
				}
			} else {
				echo "<p class=\"warning\">����� � ��� ������� ����, ������ �����</p>";
			}
		}
		
		//���� ����� ������
		public function full_post($post_id){
			$is_post = $this->db->query("SELECT COUNT(*) FROM `posts` WHERE `post_id` = $post_id")->fetchColumn(0);
			//���� ������ ����, ������� ��
			if($is_post){
				$post = $this->db->query("SELECT `post_id`, `post_title`, `post_short_desc`, `post_full_desc`, `post_cat_id`, `post_date`, `post_author_id`, `u_login` FROM `posts`, `user` WHERE `post_status` = 'post' AND `post_id` = $post_id AND `post_author_id` = `u_id` LIMIT 1")->fetch(PDO::FETCH_ASSOC);
				
				if(!$post){
					throw new Exception("������� ��������� ������, ��������� �����");
				}
				$date = explode(" ", $post["post_date"]);
				
					echo <<<POSTS
						<article class="post">
							<header>
								<h1>{$post["post_title"]}</h1>
							</header>
							<div class="content">
								{$post["post_short_desc"]}
								{$post["post_full_desc"]}
							</div>
							<footer>
								<time datetime="{$post["post_date"]}" pubdate>{$date[0]}</time>
								<span class="author">{$post["u_login"]}</span>
							</footer>
						</article>
POSTS;
			} else {
				throw new Exception("���� ������ ����");
			}
		}
		
		//���� ��������
		public function print_nav($pos_pages){
			//������������ �������
			$max_pages = ceil($this->db->query("SELECT COUNT(*) FROM `posts` WHERE `post_status` = 'post'". (isset($this->list_categories) ? " AND `post_cat_id` IN({$this->list_categories})" : ""))->fetchColumn(0) / $this->news_on_page);
			$pos_pages = $pos_pages > $max_pages ? $max_pages : $pos_pages;
			$n_page    = $this->n_page;
			
			//������������ ������ �������
			if($n_page > $max_pages){
				$n_page = $max_pages;
			} elseif(!isset($n_page) || $n_page < 1){
				$n_page = 1;
			} else {
				$n_page = $n_page;
			}
			
			//���� � ���� �������
			$offset = floor($pos_pages / 2);
			
			//������������ ������
			if($n_page - $offset <= 1){
				$start = 1;
				$end = 1 + ($pos_pages - 1);
			} else {
				$start = $n_page - $offset;
			}
			
			if($n_page + $offset >= $max_pages){
				$start = $max_pages - ($pos_pages - 1);
				$end = $max_pages;
			} elseif($start != 1){
				$end = $n_page + $offset;
			}
			
			//����
			echo "<ul id=\"page_nav\">";
			
			if($start != 1){
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}" : "") ."\">1</a></li>";
				echo "<li class=\"ellips\">...</li>";
			}
			
			for($i = $start; $i <= $end; $i++){
				if($i == $n_page){
					echo "<li class=\"current_page\">$i</li>";
					continue;
				}
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}&" : "?") ."page=$i\">$i</a></li>";
			}
			
			if($end != $max_pages){
				echo "<li class=\"ellips\">...</li>";
				echo "<li><a href=\"". $_SERVER["PHP_SELF"] .(isset($this->news_category) ? "?category={$this->news_category}&" : "?") ."page=$max_pages\">$max_pages</a></li>";
			}
			
			echo "</ul>";
		}
	}
?>