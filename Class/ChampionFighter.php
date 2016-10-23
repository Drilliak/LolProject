<?php

/**
 * Classe destinée à gérer un combat entre deux champions
 * 
 */
class ChampionFighter{

	protected $champion1, $champion2;

	// ------- Constantes de classe --------

		/**@var int constante utilisée dans la méthode auto_attack_damage **/
		const UNTREATED_INPUT_VALUE = 1;
	// ------------------------------------


	public function __construct(Champion $champion1, Champion $champion2){
		$this->champion1 = $champion1;
		$this->champion2 = $champion2;

	}

	  /**
     * Multiplicateur de dégâts
     * @param float $reduction armure ou résistance magique
     * @return float multiplicateur de dégâts
     */
    private static function damage_multiplier($reduction){
        return 100/(100+$reduction);
    }


    /**
     * Retourne un tableau indiquant les dégâts infligés par une auto attaque par le champion 1 sur le champion 2.
     * Les clés su tableau sont de base des id, mais peuvent être les noms des champions si le paramètres de la fonction est "name"
     * Par exemple, dans un combats riven olaf, le tableau retourné sera le suivant :
     * 					"riven" => 50 : dégâts infligés par Riven sur Olaf
     * 					"olaf" =>55 : dégâts infligés par Olaf sur Riven
     * @param type d'indexage du tableau (id ou name)
     * @return array()
     */
    public function auto_attack_damage($indexType = 'id'){

    	$res = array(); // Tableau de retour

    	if ($indexType == 'id'){

    		$res[$this->champion1->getId()] = $this->champion1->getAttackDamage() * $this->damage_multiplier($this->champion2->getArmor());
    		$res[$this->champion2->getId()] = $this->champion2->getAttackDamage() * $this->damage_multiplier($this->champion1->getArmor());


    	}elseif ($indexType = 'name'){

    		$res[$this->champion1->getName()] = $this->champion1->getAttackDamage() * $this->damage_multiplier($this->champion2->getArmor());
    		$res[$this->champion2->getName()] = $this->champion2->getAttackDamage() * $this->damage_multiplier($this->champion1->getArmor());

    	}else{
    		return self::UNTREATED_INPUT_VALUE;
    	}

    	return $res;

    }

    public function kill_duration(){


    	$attackSpeedChampion1 = $this->champion1->getAttackSpeed();
    	$attackSpeedChampion2 = $this->champion2->getAttackSpeed();

    	$hpPoolChampion1 = $this->champion1->getHp();
    	$hpPoolChampion2 = $this->champion2->getHp();
    	$dealtDamageArray = $this->auto_attack_damage();
    	$dealtDamageChampion1 = $dealtDamageArray[$this->champion1->getId()];
    	$dealtDamageChampion2 = $dealtDamageArray[$this->champion2->getId()];

    	$numberAAChampion2 = 0;
    	while($hpPoolChampion1 >0){
    		$hpPoolChampion1 -= $dealtDamageChampion2;
    		$numberAAChampion2 +=1;
    	}

    	$numberAAChampion1 = 0;
    	while($hpPoolChampion2 >0){
    		$hpPoolChampion2 -= $dealtDamageChampion1;
    		$numberAAChampion1 +=1;
    	}

    	echo $this->champion2->getName() . " " . $numberAAChampion2 . '<br>';
    	echo $this->champion1->getName() . " " . $numberAAChampion1;





    }







}