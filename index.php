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

//$json = json_decode(file_get_contents("https://global.api.pvp.net/api/lol/static-data/euw/v1.2/item?itemListData=all&api_key=308fdabd-0365-4103-b5ec-e8e965db5515"));


$warmog = new Item(array(
					'id' => 1,
					'name' => "Warmog",
					'FlatArmorMod' => 10,
					'FlatMagicDamageMod' =>12, 
					'FlatHPPoolMod' => 13

					));


var_dump($warmog);

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
