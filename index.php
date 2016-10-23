<?php

 
require 'Class/Autoloader.php';
Autoloader::register();

// -----------------------------------------------

// Connexion à la BDD
try{
	$db = new PDO('mysql:host=' . Constant::PATH_MYSQL_HOST . ';dbname=' . Constant::DATABASE_NAME, Constant::USER_DATABASE, Constant::PASSWORD_DATABASE);
}
catch(Exception $e){
	$e->getMessage();
}

// -----------------------------------------------
;


$manager = new Manager($db);

$annie= $manager->get('annie', 'champions');
$riven = $manager->get('riven', 'champions');

var_dump($riven);
var_dump($annie);


$championFighter = new ChampionFighter($riven, $annie);
var_dump($championFighter->auto_attack_damage("name"));

$championFighter->kill_duration();



?>
<!DOCTYPE html>
</html>
	<head>
		<meta charset = "utf-8" >
		<title></title>
	</head>
		<form method="post" action ="#">

		</form>

	<body>
		
	</body>
</html>
