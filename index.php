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

$manager = new ChampionManager($db);

var_dump(StaticDataApi::fill_champion_table());
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
