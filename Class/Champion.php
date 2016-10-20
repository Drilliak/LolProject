<?php


 /** 
  * @author Vincent BATTU <vinbat@hotmail.fr>
  * 
  * 
  */

/**
 * 
 * @class Champion
 *  Pour ajouter une caractéristique à un champion, il faut l'ajouter dans :
 *      - les attributs de la classe Champion
 *      - la base de données (si elle a besoin d'être stockée)
 *      - le cas échéant dans le tableau $tablechampionColumns de la classe Constant.
 * 
 */

class Champion{
    
    protected  $id,
               $attackdamage,
               $attackdamageperlevel,
               $ap = 0, 
               $armor, 
               $armorperlevel,
               $spellblock,
               $spellblockperlevel, 
               $name,
               $hp,
               $hpperlevel,
               $attackspeedoffset,
               $attackspeedperlevel,
               $level=1,
               $items = array();

    // ----- Constantes de classe ----------

    const OUT_OF_BOUND_LEVEL = 1; // Utilisée dans la méthode level_up pour signaler un dépassement de niveau 18

    public function hydrate(array $data){
        foreach ($data as $key => $value) {
           $method = 'set'.ucfirst($key);

           if (method_exists($this, $method)){
                $this->$method($value);
           }
        }
    }

    /**
    * @function __construct
    Construit un champion à partir d'un tableau de ses attributs
    */

    public function __construct(array $data){
        $this->hydrate($data);
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
     * Renvoie les dégâts physiques subient par un champion 
     * @param  Champion $c Champion attaquant           
     * @return double  Dégats subits
     */
    public function damages_ad_taken(Champion $c){
        $armorReduction = self::damage_multiplier($this->armor);
        return $armorReduction*$c->ad;
    }



    /**
     * Convertit un objet Champion en tableau associatif dont les clés sont les attributs du champion
     * @return array $r tableau assaciatif des attributs
     */
    public function to_array(){
        $r = array();
        foreach($this as $key => $value){
            $r[$key] = $value;
        }

        return $r;
    }


    /**
     * Augmente le niveau d'un champion de 1
     */
    public function level_up_next_level(){
        if ($this->level <18 && $this->level >=1){
            $this->level++;
            foreach(Constant::$tablechampionColumns as $member){
                $methodGet = 'get'.ucfirst($member);
                $methodSet = 'set'.ucfirst($member);
                $memberPerLevel = $member.'perlevel';
                if (in_array($memberPerLevel, Constant::$tablechampionColumns)){
                    $methodGetPerLevel = 'get'.ucfirst($memberPerLevel);

                    $this->$methodSet($this->$methodGet() + $this->$methodGetPerLevel());

                }
            }
        }
    }


    /**
     * adapte les statistiques d'un champion jusqu'au niveau souhaité
     * @param int $level niveau à atteindre si la valeur est positive
     *                   nombre de niveau à diminuer si la valeur est négative
     * @return True si l'augementation ou la diminution de niveau a correctement été effectuée
     *         Champion::EXCEEDED_LEVEL si le niveau voulu est supérieur à 18
     *        
     */
    public function level_up($level){

        // Cas ou on veut augmenter de niveau
        if ($level>=0){
            $levelDifference = $level - $this->level;
            if($levelDifference<=17)
            {
                $this->level = ($level != 0) ? $level : 1;
                foreach(Constant::$tablechampionColumns as $member)
                {
                    $methodGet = 'get'.ucfirst($member);
                    $methodSet = 'set'.ucfirst($member);
                    $memberPerLevel = $member.'perlevel';
                    if (in_array($memberPerLevel, Constant::$tablechampionColumns)){
                        $methodGetPerLevel = 'get'.ucfirst($memberPerLevel);

                        $this->$methodSet($this->$methodGet() + $levelDifference * $this->$methodGetPerLevel());
                    }            
                }
                return True;
            }
            else{
                return self::OUT_OF_BOUND_LEVEL;
            }            
        }

        // Si l'ont veut diminuer de niveau
        else{
            $finalLevel = $this->level + $level;
            if ($finalLevel >=1)
            {
                $this->level = $finalLevel;
                foreach(Constant::$tablechampionColumns as $member)
                {
                    $methodGet = 'get'.ucfirst($member);
                    $methodSet = 'set'.ucfirst($member);
                    $memberPerLevel = $member.'perlevel';
                    if (in_array($memberPerLevel, Constant::$tablechampionColumns)){
                        $methodGetPerLevel = 'get'.ucfirst($memberPerLevel);

                        $this->$methodSet($this->$methodGet() + $level * $this->$methodGetPerLevel());
                    }            
                }
                return True;
            }
            else
            {
                return self::OUT_OF_BOUND_LEVEL;
            }
        }
    }

    public function getAttackSpeed(){
        $stapleAttackSpeed = 0.625/(1+$this->attackspeedoffset);
        return $stapleAttackSpeed + ($this->level-1)*(($stapleAttackSpeed/100)*$this->attackspeedperlevel);
    }

    // Setters

    public function setSpellblockperlevel($spellblockperlevel){
        $spellblockperlevel = (float) $spellblockperlevel;
        $this->spellblockperlevel = $spellblockperlevel;
    }

    public function setAttackdamage($attackdamage){
        $attackdamage = (float) $attackdamage;
        if ($attackdamage >= 0){
            $this->attackdamage= $attackdamage;
        }
    }

    public function setAttackdamageperlevel($attackdamageperlevel){
        $attackdamageperlevel = (float) $attackdamageperlevel;
        if ($attackdamageperlevel >=0){
            $this->attackdamageperlevel = $attackdamageperlevel;
        }
    }

    public function setAp($ap){
        $ap = (float) $ap;
        if ($ap >=0){
            $this->ap = $ap;
        }
    }

    public function setArmor($armor){
        $armor = (float) $armor;
        $this->armor = $armor;
    }

    public function setArmorperlevel($armorperlevel){
        $armorperlevel = (float) $armorperlevel;
        $this->armorperlevel = $armorperlevel;
    }

    public function setSpellblock($spellblock){
        $spellblock = (float) $spellblock;
        $this->spellblock = $spellblock;
    }

    public function setName($name){
        $name = (string) $name;
        $this->name = $name;
    }

    public function setId($id){
        $id = (int) $id;
        $this->id = $id;
    }

    public function setHp($hp){
        $hp = (float) $hp;
        $this->hp = $hp;
    }

    public function setHpperlevel($hpperlevel){
        $hpperlevel = (float) $hpperlevel;
        $this->hpperlevel = $hpperlevel;
    }

    public function setLevel($level){
        $level = (int) $level;
        if ($this->level >=1 && $this->level<=18){
            $this->level = $level;

        }
    }

    public function setAttackspeedoffset($attackspeedoffset){
        $attackspeedoffset = (float) $attackspeedoffset;
        if ($attackspeedoffset >=-0.75){
            $this->attackspeedoffset = $attackspeedoffset;
        }
    }

    public function setAttackspeedperlevel($attackspeedperlevel){
        $attackspeedperlevel = (float) $attackspeedperlevel;
        if ($attackspeedperlevel >=0 ){
            $this->attackspeedperlevel = $attackspeedperlevel;
        }
    }

    public function setItems(array $itemsArray){
        $numberOfItems = sizeof($itemsArray);
        if ($numberOfItems >=0 && $numberOfItems <=6){
            $this->items = $itemsArray;
        }
    }

    // Getters

    public function getId(){
        return $this->id;
    }

    public function getAttackdamage(){
        return $this->attackdamage;
    }

    public function getAttackdamageperlevel(){
        return $this->attackdamageperlevel;
    }

    public function getAp(){
        return $this->ap;
    }

    public function getArmor(){        
        return $this->armor;
    }

    public function getArmorperlevel(){
        return $this->armorperlevel;
    }

    public function getSpellblock(){
        return $this->spellblock;
    }

    public function getSpellblockperlevel(){
        return $this->spellblockperlevel;
    }

    public function getName(){
        return $this->name;
    }

    public function getHp(){
        return $this->hp;
    }

    public function getHpperlevel(){
        return $this->hpperlevel;
    }

    public function getLevel(){
        return $this->level;
    }

    public function getItems(){
        return $this->items;
    }

    public function getAttackspeedoffset(){
        return $this->attackspeedoffset;
    }

    public function getAttackspeedperlevel(){
        return $this->attackspeedperlevel;
    }

}