<?php

/**
 * Classe destinée à regrouper les constantes utiles au fonctionnement de l'application
 */
class Constant{

	const USER_DATABASE = 'root';
	const PASSWORD_DATABASE = 'root';
	const PATH_MYSQL_HOST = 'localhost';
	const DATABASE_NAME = 'league_of_legends';
	const CHAMPION_TABLE = 'champions';
	public static $tablechampionColumns = array(
						'id',
						'name',
					    'attackdamage',
					    'spellblock', 
					    'armor', 
						'spellblockperlevel',
						'armorperlevel',
						'attackdamageperlevel',
						'hp',
						'hpperlevel',
						'attackspeedoffset',
               			'attackspeedperlevel',
	  );

}