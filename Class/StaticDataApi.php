<?php


class StaticDataApi extends RiotApiManager{

	// --------------- Constantes de classe --------------------------

	/**@var int valeur retournée lorsque la table appelée n'existe pas **/
	const NONEXISTENT_TABLE = 1; 
	const VERSION = 'v1.2/';
	const API_REFERENCE =  '/api/lol/static-data/';

	// ------------------------------------------------------------------
	
	/**
	 * Contruit l'url correspondant à l'api Riot renvoyant le json des stats sur les champions
	 * @return string url api champion
	 */
	public static function build_url_champions(){

		return self::API_URL . self::API_REFERENCE . self::REGION . self::VERSION . 'champion?champData=all&api_key=' . self::KEY;
	}

	/**
	 * Construit l'url correspondant à l'api Riot renvoyant le json sur les stats des objets
	 * @return string url api objet
	 */
	public static function build_url_items(){
		return self::API_URL . self::API_REFERENCE . self::REGION . self::VERSION . 'item?itemListData=all&api_key=' . self::KEY;
	}

	/**
	 * Rempli la base de données donnée en paramètre à partir des données renvoyées par l'api de riot.
	 * Commence par vider la table en place.
	 * @param String $table table à repmplir
	 * @return array tableau associatif contenant les données suivantes
	 * 				requestTime : temps d'exécution de la requête (appel api uniquement)
	 * 				executionTime : temps d'exécution de la méthode hors appel api
	 * 				totalTime : temps total d'exécution du programme
	 */
	public static function fill_table($table)
	{
		$start = microtime(true);

		$table = strtolower($table);
		switch($table){
			case Constant::CHAMPION_TABLE:
				$buildUrlMethod = "build_url_champions";
				$table = Constant::CHAMPION_TABLE;
				$tableColumns = Constant::$tablechampionColumns;
				$object = 'Champion';
				break;
			case Constant::ITEM_TABLE:
				$buildUrlMethod = "build_url_items";
				$table = Constant::ITEM_TABLE;
				$tableColumns = Constant::$tableItemColumns;
				$object = 'Item';
				break;
			default:
				return self::NONEXISTENT_TABLE;

		}

		$res = array(); // Tableau contenant les valeurs renvoyées par la méthode
		// Connexion à la BDD
		try{
			$db = new PDO('mysql:host=' . Constant::PATH_MYSQL_HOST . ';dbname=' . Constant::DATABASE_NAME, Constant::USER_DATABASE, Constant::PASSWORD_DATABASE);
		}
		catch(Exception $e){
			return $e->getMessage();
		};

		$startRequest = microtime(true);
		$json = file_get_contents(self::$buildUrlMethod());
		$stopRequest = microtime(true);
		$res['requestTime'] = $stopRequest - $startRequest;


		$startExecution = microtime(true);
		$parsed_json = json_decode($json);
		$parsed_json = $parsed_json->{'data'};

		$manager = new Manager($db, $table);
		$manager->delete($table);
		foreach($parsed_json as $value)
		{
			$statsArray = array();
			$statsArray['name'] = isset($value->{'name'}) ? $value->{'name'} : "NULL";
			$statsArray['id']= $value->{'id'};
			$value = $value->{'stats'};
			foreach($value as $statName => $stat)
			{
				if (in_array($statName, $tableColumns)){
					$statsArray[$statName] = $stat;
				}
			}
			// Insertion des champions à la BDD
			$manager->add(new $object($statsArray));


		
		}
		$stop = microtime(true);
		$res['totalTime'] = $stop-$start;
		$res['executionTime'] = $stop-$startExecution;
		return $res;

		
	}


}