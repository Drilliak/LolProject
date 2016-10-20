<?php


class Manager{

	protected $db;

	public function __construct($db){
		$this->setDb($db);
	}

	public function setDb(PDO $db){
		return $this->db = $db;
	}

	/**
	 * /**
	  * Permet d'ajouter un objet (Item ou Champion) dans la base de données appropriée. La méthode est dynamique et s'adapte
	  *  à l'objet même si la liste de ses attributs a été modifiée.
	  * @param Object $object objet à ajouter (Item ou Champion)
	  * @return bool True si l'ajout s'est correctement réalisé
	  * 			 False sinon
	  */
	public function add(Object $object){

		switch (get_class($object)){
			case "Item":
				$table = Constant::ITEM_TABLE;
				$tableColumns = Constant::$tableItemColumns;
				break;
			case 'Champion':
				$table = Constant::CHAMPION_TABLE;
				$tableColumns = Constant::$tablechampionColumns;

		}

		// Construction de la requête
		$req = 'INSERT INTO ' . $table . '(';
		$tab = $object->to_array();
		$values = "(";

		foreach ($tab as $key => $value){
			# On s'assure que le champ est dans la BDD
			if (in_array($key,$tableColumns)){
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
			if (in_array($key, $tableColumns)){
				$q->bindValue(':'.$key, $value);
			}
			
		}
		return $q->execute();


	}



}