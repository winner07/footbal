<?php

class Menu extends DB_connect{
	public $categories;
	
	//Витяг категорій з бази даних і формування масиву
	public function __construct(){
		//Виклик батьківського конструктора підключення бази даних
		parent::__construct();
		//Запит до бази даних
		$result = $this->db->query("SELECT * FROM `categories`");
		if(!$result){
			return NULL;
		}
		$arr_cat = array();
		$count_rows = $this->db->query("SELECT COUNT(*) FROM `categories`")->fetchColumn(0);
		
		if($count_rows) {
			//У циклі формуємо масив
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				//Формуємо масив, де ключами є ID на батьківські категорії
				if(empty($arr_cat[$row["cat_parent"]])){
					$arr_cat[$row["cat_parent"]] = array();
				}
				$arr_cat[$row["cat_parent"]][] = $row;
			}
			//Повертаємо масив
			$this->categories = $arr_cat;
		}
	}
	
	//Вивід категорій для сайту
	public function print_categories($parent = 0){
		echo "<ul>";
		
		foreach($this->categories[$parent] as $item){
			$child = $this->categories[$item["cat_id"]];
			
			echo "<li><a href=\"index.php?category={$item["cat_id"]}\"". (!empty($item["cat_class"]) ? " class=\"{$item["cat_class"]}\"" : NULL) .">{$item["cat_name"]}</a>";
			if(isset($child)){
				$this->print_categories($item["cat_id"]);
			}
			echo "</li>";
		}
		
		echo "</ul>";
	}
	
	//Вивід категорій для вибору
	public function select_categorie($parent = 0, $default_cat){
		echo "<ul>";
		
		foreach($this->categories[$parent] as $item){
			$child = $this->categories[$item["cat_id"]];
			
			echo "<li><input type=\"radio\" value=\"{$item["cat_id"]}\" id=\"{$item["cat_id"]}\" name=\"post_cat\" form=\"form_post\" ". ($default_cat == $item["cat_id"] ? "checked" : NULL) ."><label for=\"{$item["cat_id"]}\">{$item["cat_name"]}</label>";
			if(isset($child)){
				$this->select_categorie($item["cat_id"], (isset($default_cat) ? $default_cat : NULL));
			}
			echo "</li>";
		}
		
		echo "</ul>";
	}
	
	//Вивід категорій для редагування
	public function manage_categories($parent = 0){
		foreach($this->categories[$parent] as $item){
			$child = $this->categories[$item["cat_id"]];
			
			echo "<tr". ($parent != 0 ? " class=\"child\"" : NULL) .">
					<td><span class=\"cat_name\">{$item["cat_name"]}</span>
					<div class=\"edit_block\">
						<a href=\"delete_cat.php\" class=\"delete\" data-cat-id=\"{$item["cat_id"]}\">Видалити</a>
						<a href=\"edit_cat.php\" class=\"edit\" data-cat-id=\"{$item["cat_id"]}\">Редагувати</a>
					</div>
					</td>
					<td>{$item["cat_class"]}</td>
				</tr>";
			if(isset($child)){
				$this->manage_categories($item["cat_id"]);
			}
			echo "</li>";
		}
	}
	
	//Вивід батьківських категорій для вибору
	public function parent_categories(){
		echo "<select id=\"add_cat_parent\" name=\"add_cat_parent\">";
		echo "<option value=\"0\">Нема</option>";
		for($i = 0; $i < count($this->categories[0]); $i++){
			echo "<option value=\"{$this->categories[0][$i]["cat_id"]}\">{$this->categories[0][$i]["cat_name"]}</option>";
		}
		echo "</select>";
	}
}

?>