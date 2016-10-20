<?php


/**
 * class destinée à gérer la connexion à la table champions
 */

class ChampionManager{

	private $db;

	public function __construct($db){
		$this->setDb($db);
	}

	public function setDb(PDO $db){
		return $this->db = $db;
	}

	/**
	 * Ajoute un champion à la bdd. La méthode est dynamique et s'adapte au champion, 
	 * même si la liste de ses attributs a été modifiée.
	 * @param Champion $champ Champion à ajouter à la BDD
	 */
	public function add(Champion $champ){

		// Construction de la requête
		$req = 'INSERT INTO ' . Constant::CHAMPION_TABLE . '(';
		$tab = $champ->to_array();
		$values = "(";

		foreach ($tab as $key => $value){
			# On s'assure que le champ est dans la BDD
			if (in_array($key, Constant::$tablechampionColumns)){
			$req .= $key . ', ';
			$values .= ':' . $key . ', ';
			}
		} 
		$req = rtrim($req, ' ,');
		$values = rtrim($values, ' ,');
		$req .= ') VALUES' . $values . ');';

		// Exécution

		$q = $this->db->prepare($req);
		foreach ($tab as $key => $value){
			if (in_array($key, Constant::$tablechampionColumns)){
				$q->bindValue(':'.$key, $value);
			}
			
		}
		$q->execute();
		$champ->setId($this->db->lastInsertId());
	}

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
		foreach (Constant::$tablechampionColumns as $value){
			$req .= $value . ', ';
		}
		$req = rtrim($req, ' ,');
		$req .= ' FROM ' . Constant::CHAMPION_TABLE . ' WHERE name = :name;';
		$q = $this->db->prepare($req);
		$q->execute(array(':name' =>$name));
		return new Champion($q->fetch(PDO::FETCH_ASSOC));
	}



}