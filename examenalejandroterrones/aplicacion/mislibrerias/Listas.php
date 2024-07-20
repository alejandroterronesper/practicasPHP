<?php

/**
 * clasde listas 
 * se carga automaticamente
 */
class Listas {



    /**
     * Esta clase contendrá métodos estáticos que nos darán listas de elementos. 
     * Crear en esta clase el método estático listaTiposBarajas con los parámetros $completo (booleano, por 
     * defecto false) y $cod_baraja (entero, por defecto null). Internamente tendremos un array con todos los 
     * tipos de barajas: [5 => [“nombre” => ”española normal”, “min_juga” => 2, “max_juga” => 4], 6 => 
     * [“nombre”=>”pocker”, “min_juga” => 4, “max_juga” => 4], 7=> [“nombre” => ”figuras” , “min_juga”
     *  => 4, “max_juga” => 8]] Si $completo vale true, se devolverá para cada tipo todos los datos 
     * (nombre/min_juga/max_juga)
     *
     * @param boolean $completo
     * @param integer|null $cod_baraja
     */
    public static function listaTipoBarajas(bool $completo = false, ?int $cod_baraja = null)
    {


        $arrayBarajas =  [
            5 => ["nombre" => "española normal", "min_juga" => 2, "max_juga" => 4],
            6 => ["nombre" => "pocker", "min_juga" => 4, "max_juga" => 4],
            7 => ["nombre" => "figuras", "min_juga" => 4, "max_juga" => 8],
            10 =>  ["nombre" => "cartas magicas", "min_juga" => 1, "max_juga" => 2] //BARAJA INVENTADA
        ];



        if ($cod_baraja === null) {

            if ($completo === true) {
                return $arrayBarajas;
            }

            if ($completo === false) {


                $arrayNombres = [];


                foreach ($arrayBarajas as $clave => $valor) {

                    $arrayNombres[$clave] = $valor["nombre"];

                    //array_push($arrayNombres, [$clave => $valor["nombre"]]);
                }


                return $arrayNombres;
            }
        }


        if (is_int($cod_baraja)) {


            if (array_key_exists($cod_baraja, $arrayBarajas) === true) {

                if ($completo === true) {

                    return $arrayBarajas[$cod_baraja];
                } else {
                    return $arrayBarajas[$cod_baraja]["nombre"];
                }
            } else {

                return false;
            }
        }
    }
}


?>