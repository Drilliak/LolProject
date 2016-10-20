<?php



class Item{

	protected $id,
			  $name,
			  $FlatArmorMod = 0,
			  $FlatMagicDamageMod = 0,
			  $FlatHPPoolMod = 0;
	
	public function hydrate(array $data){
        foreach ($data as $key => $value) {
           $method = 'set'.ucfirst($key);

           if (method_exists($this, $method)){
                $this->$method($value);
           }
        }
    }


    public function __construct(array $data){
        $this->hydrate($data);
    }

    // Setters

    public function setId($id){
    	if (is_int($id) && $id >=0){
    		$this->id = $id;
    	}

    }

    public function setName($name){
    	$name = (string) $name;
    	$this->name = $name;
    }

    public function setFlatArmorMod($flatArmorMod){
    	$flatArmorMod = (float) $flatArmorMod;
    	if ($flatArmorMod >=0){
    		$this->FlatArmorMod = $flatArmorMod;
    	}
    }

    public function setFlatMagicDamageMod($flatMagicDamageMod){
    	$flatMagicDamageMod = (float) $flatMagicDamageMod;
    	if ($flatMagicDamageMod >=0){
    		$this->FlatMagicDamageMod = $flatMagicDamageMod;
      	}
    }

    public function setFlatHPPoolMod($flatHpPoolMod){
    	$flatHpPoolMod = (float) $flatHpPoolMod;
    	if ($flatHpPoolMod >=0){
    		$this->FlatHPPoolMod = $flatHpPoolMod;
    	}
    }

}