<?php

class Translate extends DB_connect {
	public $lang;

	public function __construct($lang) {
		//call parent construct for connect to DB
		parent::__construct();
		//set language
		$this->lang = $lang;
	}

	//form for add word to translate DB
	public function form_add_word() {
		echo <<<FORM
			<form action="" method="post" id="add_word">
				<fieldset>
					<label for="word">Word</label>
					<input type="text" name="word" id="word" required>
				</fieldset>
				<fieldset>
					<label for="en">English value</label>
					<input type="text" name="en" id="en" required>
				</fieldset>
				<fieldset>
					<label for="ua">Ukrainian value</label>
					<input type="text" name="ua" id="ua" required>
				</fieldset>
				<fieldset id="buttons">
					<input type="submit" name="go" value="Submit">
				</fieldset>
			</form>
FORM;
	}

	//add word to translate DB
	public function add_word() {
		$word = $this->db->quote(trim($_POST["word"]));
		$en   = $this->db->quote(trim($_POST["en"]));
		$ua   = $this->db->quote(trim($_POST["ua"]));

		if (isset($word, $en, $ua)) {
			if ($this->db->exec("INSERT INTO `translations` (`word`, `en`, `ua`) VALUE ($word, $en, $ua)")) {
				return true;
			}
			else {
				throw new Exception("Error add word");
			}
		}
		else {
			throw new Exception("All values are not filling");
		}
	}

	//get word from translate DB
	public function get_word($word) {
		$word_query = $this->db->query("SELECT `$this->lang` FROM `translations` WHERE `word` = '$word'")->fetchColumn(0);

		if ($word_query) {
			return $word_query;
		}
	}

	//get words from translate DB
	public function get_all_word() {
		$words_query = $this->db->query("SELECT * FROM `translations`");

		if ($words_query) {
			echo '<table width="600">
            	<tr>
                <th width="33%">Word</th><th width="33%">English</th><th width="33%">Ukrainian</th>
              </tr>';
			while ($word = $words_query->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>
								<td>{$word["word"]}</td><td>{$word["en"]}</td><td>{$word["ua"]}</td>
							</tr>";
			}
			echo '</table>';
		}
		else {
			throw new Exception("Words are not adding");
		}
	}
}

?>
