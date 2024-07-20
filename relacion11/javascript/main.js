var dominio = document.location.protocol + "//" + document.location.hostname;
var URL = dominio + "/aplicacion/catastro/dameMunicipios.php"; //ruta a donde enviaremos por post la provincia



/**
 * Evento que al cambiar el comboBox de provincias, se rellena
 * son sus respectivas municipios
 */
document.getElementById("provincias").onchange = function(){
    
    //guardamos objeto de select provincias
    var selectProvincias = document.getElementById("provincias"); 

    //sacamos provincia
    var provinciaSeleccionada = selectProvincias.options[selectProvincias.selectedIndex].value;



    fetch (URL, {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
        body: "provincia="+provinciaSeleccionada  //peticion normal
        
    })
    .then(function (response) {
        if (response.ok) {
            response.json() // response.json(), si se devuelve json
            .then(function(resp) {

                //variables
                var arrayMunicipios = resp
                var miSelect = document.getElementById("municipios");


                if (provinciaSeleccionada !== "defecto"){ //comprobamos que no se haya pulsado por defecto

                    if (miSelect.getElementsByTagName("option").length > 1){ //Se comprueba si tiene elementos
                        //Si hay elementos previos, se van eliminando

                        let options = Array.from(miSelect.getElementsByTagName("option")); //lo pasamos a array para iterar
                       
                        for (const option of options){
                      
                            if (option.value !== "defecto"){
                                miSelect.removeChild(option);
                            }
                        }
                    }

                    //Se añade options de petición actual
                    for (municipio of arrayMunicipios){

                        let option = document.createElement("option");
                        option.setAttribute("value", municipio);
                        let texto = document.createTextNode(municipio);
                        option.appendChild(texto)
                        miSelect.appendChild(option)
                    }

                }
                else{ //si se pulsa por defecto, es decir, no devuelve nada
                    //se vacia el combo de municipios, en caso de que este relleno de municipios
                    
                    if (miSelect.getElementsByTagName("option").length > 1){ //Se comprueba si tiene elementos
                        //Si hay elementos previos, se van eliminando

                        let options = Array.from(miSelect.getElementsByTagName("option")); //lo pasamos a array para iterar
                       
                        for (const option of options){
                      
                            if (option.value !== "defecto"){ //la opción por defecto no la borramos
                                miSelect.removeChild(option); //vamos borrando municipios
                            }
                        }
                    }
                }

            })
            .catch(function(e) {
                console.error("Error: " + e);
            });
        }
    })

}

