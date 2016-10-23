<?php

abstract class Object{


	protected $id, 
			  $name;

			  
	 /**
     * Convertit un objet Champion en tableau associatif dont les clés sont les attributs du champion
     * @return array $r tableau assaciatif des attributs
     */
    public function to_array(){

        $r = array();
        foreach($this as $key => $value){
        	if (!is_array($value)){
           		 $r[$key] = $value;
        	}
        }

        return $r;
    }

    public function getName(){
    	return $this->name;
    }




}