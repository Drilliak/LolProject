<?php


class StaticDataApi extends RiotApiManager{

	const VERSION = 'v1.2/';
	const API_REFERENCE =  '/api/lol/static-data/';
	
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
	 * Rempli table champion en fonction de l'api renvoyé par riot
	 * Vide la table avant de la remplir
	 * Activer l'extension  php_openssl
	 * @return array() tableau contenant 
	 * 					"totalTime" => temps d'exécution total de la méthode
	 * 					"requestTime" => temps d'exécution de la requête (appel API)
	 */
	public static function fill_champion_table(){

		$start = microtime(true);
		$res = array(); // Tableau contenant les valeurs renvoyées par la méthode
		// Connexion à la BDD
		try{
			$db = new PDO('mysql:host=' . Constant::PATH_MYSQL_HOST . ';dbname=' . Constant::DATABASE_NAME, Constant::USER_DATABASE, Constant::PASSWORD_DATABASE);
		}
		catch(Exception $e){
			$e->get();
		};


		// Extraction des données du fichier Json

		$startRequest = microtime(true);
		$json = file_get_contents(self::build_url_champions());
		$stopRequest = microtime(true);
		$res['requestTime'] = $stopRequest - $startRequest;


		$startExecution = microtime(true);
		$parsed_json = json_decode($json);


		$parsed_json = $parsed_json->{'data'};
		var_dump($parsed_json);
		$manager = new ChampionManager($db);
		$manager->delete();


		foreach($parsed_json as $value)
		{
			$championStatsArray = array();
			$championStatsArray['name'] = $value->{'name'};
			$championStatsArray['id']= $value->{'id'};
			$value = $value->{'stats'};
			foreach($value as $statName => $stat)
			{
				if (in_array($statName, constant::$tablechampionColumns)){
					$championStatsArray[$statName] = $stat;
				}
			}
			// Insertion des champions à la BDD
			$manager->add(new Champion($championStatsArray));


		
		}
		$stop = microtime(true);
		$res['totalTime'] = $stop-$start;
		$res['executionTime'] = $stop-$startExecution;
		return $res;
	}

	public static function fill_items_table(){

		$json = file_get_contents(self::build_url_items());
		$parsed_json = json_decode($json);
		$parsed_json = $parsed_json->{'data'};
	}


}