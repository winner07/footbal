<?php

class Menu extends DB_connect{
	public $categories;
	
	//����� �������� � ���� ����� � ���������� ������
	public function __construct(){
		//������ ������������ ������������ ���������� ���� �����
		parent::__construct();
		//����� �� ���� �����
		$result = $this->db->query("SELECT * FROM `categories`");
		if(!$result){
			return NULL;
		}
		$arr_cat = array();
		$count_rows = $this->db->query("SELECT COUNT(*) FROM `categories`")->fetchColumn(0);
		
		if($count_rows) {
			//� ���� ������� �����
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				//������� �����, �� ������� � ID �� ��������� �������
				if(empty($arr_cat[$row["cat_parent"]])){
					$arr_cat[$row["cat_parent"]] = array();
				}
				$arr_cat[$row["cat_parent"]][] = $row;
			}
			//��������� �����
			$this->categories = $arr_cat;
		}
	}
	
	//���� �������� ��� �����
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
	
	//���� �������� ��� ������
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
	
	//���� �������� ��� �����������
	public function manage_categories($parent = 0){
		foreach($this->categories[$parent] as $item){
			$child = $this->categories[$item["cat_id"]];
			
			echo "<tr". ($parent != 0 ? " class=\"child\"" : NULL) .">
					<td><span class=\"cat_name\">{$item["cat_name"]}</span>
					<div class=\"edit_block\">
						<a href=\"delete_cat.php\" class=\"delete\" data-cat-id=\"{$item["cat_id"]}\">��������</a>
						<a href=\"edit_cat.php\" class=\"edit\" data-cat-id=\"{$item["cat_id"]}\">����������</a>
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
	
	//���� ����������� �������� ��� ������
	public function parent_categories(){
		echo "<select id=\"add_cat_parent\" name=\"add_cat_parent\">";
		echo "<option value=\"0\">����</option>";
		for($i = 0; $i < count($this->categories[0]); $i++){
			echo "<option value=\"{$this->categories[0][$i]["cat_id"]}\">{$this->categories[0][$i]["cat_name"]}</option>";
		}
		echo "</select>";
	}
}

?>