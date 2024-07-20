<?php


class CCaja extends CWidget {

    private $_titulo = "";
    private $_contenido = "";
    private $_atributosHTML = array ();

    public function __construct(string $titulo, string $contenido="", array $atributosHTML=array())
    {
        $this->_titulo = $titulo;

        $this->_contenido = $contenido;

        $this->_atributosHTML=$atributosHTML;
        if (!isset($this->_atributosHTML["class"])){
          $this->_atributosHTML["class"]="caja";          
        }

		

    }


    /**
     * Función que devuelve el script de js
     * para la caja
     * esta funcion se llama cuando se activa el evento
     * onclick del boton mostrar contenido
     * 
     * Si esta en display:block se pone a display:none
     * y viceversa
     *
     * @return String  cadena de la función de js
     */
    public static function requisitos (): String {

        $codigo=<<<EOF
        function muestraContenido()
        {
            
            let caja = document.getElementById('cajaForm');
            
            if (caja.style.display === 'block' || caja.style.display === ''){
                caja.style.display = 'none';
                document.getElementById('ocultar').innerText = 'Mostrar contenido'
            }
            else{
                caja.style.display = 'block'
                document.getElementById('ocultar').innerText = 'Ocultar contenido'

            }
            

        }
        EOF;
        return CHTML::script($codigo);
    }


    /**
     * Función que devuelve la 
     * cadena que devuelve dibuja apartura y dibuja fin
     *
     * @return String devuelve caja completa
     */
    public function dibujate():string
    {
        return $this->dibujaApertura().$this->dibujaFin();
    }


    /**
     * Dibuja la parte superior de la caja
     *
     * @return String devuelve cadena de con etiquetas HTML de la caja
     */
    public function dibujaApertura():string
    {
        ob_start();

        
        echo CHTML::dibujaEtiqueta("div",$this->_atributosHTML, "", false).PHP_EOL;

        echo CHTML::dibujaEtiqueta("div", ["class"=> "titulo", "id" => "cajaTitulo"],
        CHTML::dibujaEtiqueta("span",  [], $this->_titulo . "". CHTML::botonHtml("Ocultar contenido", ["style" => "margin-left: 5%",
                                    "class" => "boton", "onclick" =>"muestraContenido();" , "id"=> "ocultar"]), true), true ).PHP_EOL;;
      

        //creamos caja contenedora del contenido, pero no la cerramos
        echo CHTML::dibujaEtiqueta("div", ["class" => "cuerpo", "id" => "cajaForm"], null, false).PHP_EOL;;
        
        
        if ($this->_contenido !== ""){
            echo $this->_contenido;
        }

        //</div>

        $escrito = ob_get_contents();
        ob_end_clean();

        return $escrito;
    }


    /**
     * Se encarga de cerrar la caja, cierra
     * las etiquetas divs
     *
     * @return String devuelve cadena HTML
     */
    public function dibujaFin():string
    {
        return   CHTML::dibujaEtiquetaCierre("div") ." ". CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
    }
}














?>