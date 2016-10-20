<?php


/**
 * class destinée à gérer la connexion à la table champions
 */

class ChampionManager extends Manager{

	/**
	 * Vide la base de données
	 */
	public function delete(){
		$this->db->query("TRUNCATE TABLE " . Constant::CHAMPION_TABLE);
	}

	/**
	 * Description
	 * @param string $name nom du champion à extraire
	 * @return Champion champion dont les statistiques ont été exraites de la BDD
	 */
	public function get($name){
		$req = 'SELECT ';
		foreach (Constant::$tableItemColumns as $value){
			$req .= $value . ', ';
		}
		$req = rtrim($req, ' ,');
		$req .= ' FROM ' . Constant::CHAMPION_TABLE . ' WHERE name = :name;';
		$q = $this->db->prepare($req);
		$q->execute(array(':name' =>$name));
		if ($res == false){
			return false;
		}
		return new Champion($res);
	}



}