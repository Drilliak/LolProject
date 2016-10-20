<?php

class Object{


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




}