<?php


class ItemManager extends Manager{

	public function delete(){
		$this->db->query("TRUNCATE TABLE " . Constant::ITEM_TABLE);
	}

	public function get($name){
		$req = 'SELECT ';
		foreach (Constant::$tableItemColumns as $value){
			$req .= $value . ', ';
		}
		$req = rtrim($req, ' ,');
		$req .= ' FROM ' . Constant::ITEM_TABLE . ' WHERE name = :name;';
		$q = $this->db->prepare($req);
		$q->execute(array(':name' =>$name));
		$res = $q->fetch(PDO::FETCH_ASSOC);
		if ($res == false){
			return false;
		}
		return new Item($res);
	}



}