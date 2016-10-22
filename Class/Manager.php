<?php

/**
 * Classe destinée à gérer l'accès à la base de données league_of_legends
 */

class Manager{

	protected $db;

// ------------- Constantes de classes -------------
	/**@var int valeur retournée si la table appelée n'existe pas **/
	const NONEXISTENT_TABLE = 1; 
	/**@var int valeur retournée si l'objet rentrée en paramètre n'existe pas **/
	const UNTRAEATED_OBJECT = 2;
	/**@var int valeur retournée si le type d'objet rentré en paramètre de get n'est pas traité **/
	const UNTRAEATED_DATA_INPUT_TYPE = 3;

// ------------------------------------------------

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
				break;
			default:
				return self::UNTRAEATED_OBJECT;


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

	/**
	 * Permet de rechercher un objet (item ou champion) dans la base de données appropriée
	 * à partir de son nom ou de son id
	 * @param type $data nom de l'objet à retourner
	 * @param type $table table dans lequel il faut exécuter la recherche
	 * @return type Object l'objet recherché s'il a été trouvé
	 * 				bool false si la recherche à échouer
	 * 				int self::NONEXISTENT_TABLE si la table rentrée en paramètre n'existe pas
	 * 				int self::UNTRAEATED_DATA_INPUT_TYPE si le type de $data n'est pas un entier ou une chaine de caractère
	 */
	public function get($data, $table){

		// Cas d'une requête où l'on effectue la recherche à l'aide de l'id de l'objet
		if (is_int($data)){
			$endRequest = ' FROM ' . $table . ' WHERE id = :id;';
		}
		elseif (is_string($data)) // Cas où l'on effectue la recherche à l'aide du nom du champion
		{
			$endRequest = ' FROM ' . $table . ' WHERE name = :name;';
		}
		else
		{
			return self::UNTRAEATED_DATA_INPUT_TYPE;
		}

		switch ($table = strtolower($table)){
			case Constant::ITEM_TABLE:
				$table = Constant::ITEM_TABLE;
				$tableColumns = Constant::$tableItemColumns;
				$type = "Item";
				break;
			case Constant::CHAMPION_TABLE:
				$table = Constant::CHAMPION_TABLE;
				$tableColumns = Constant::$tablechampionColumns;
				$type = "Champion";
				break;
			default:
				return self::NONEXISTENT_TABLE;

		}

		$req = 'SELECT ';
		foreach ($tableColumns as $value){
			$req .= $value . ', ';
		}
		$req = rtrim($req, ' ,');
		$req .= $endRequest;
		$q = $this->db->prepare($req);
		if (is_int($data)){
			$q->execute(array(':id' =>$data));
		}
		elseif (is_string($data)) 
		{
			$q->execute(array(':name' =>$data));
		}
		$res = $q->fetch(PDO::FETCH_ASSOC);
		if ($res == false){
			return false;
		}
		return new $type($res);
	}


	public function delete($table){
		$table = strtolower($table);
		if ($table ==Constant::CHAMPION_TABLE)
			$this->db->query("TRUNCATE TABLE " . Constant::CHAMPION_TABLE);
		elseif ($table== Constant::ITEM_TABLE)
			$this->db->query("TRUNCATE TABLE " . Constant::ITEM_TABLE);
		else
			return self::NONEXISTENT_TABLE;
	}



}