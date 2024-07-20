var dominio = document.location.protocol + "//" + document.location.hostname;
var URL = dominio + "/practicas2/datosAJAX";

/**
 * Evitamos el envio automático de los datos del formulario
 */
document.getElementById("formularioAJAX").addEventListener("submit", function(event){
    event.preventDefault();

})


/**
 * Evento para el botón del formulario
 * se encarga de recoger los datos del formulario
 * los envia al controlador practicas2 accion datosAJAX
 */
document.getElementById("subeData").onclick = function (){
    
   //recogemos los datos
    var minimo = document.getElementById("nuMin").value;
    var maximo = document.getElementById("nuMax").value;
    var patron = document.getElementById("textPatron").value;


    fetch (URL, {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
        body: "minimo="+minimo+"&maximo="+maximo+"&patron="+patron
    })
    .then(function(response){
        if (response.ok){
           response.text()
           .then (function(resp){
            var arrayJSON = JSON.parse(resp); //convertimos en JSON el resultado

               //cogemos el div de resultados
               var miDiv = document.getElementById("resultados");

               //Borramos los posibles nodos anteriores
               while (miDiv.firstChild) {
                   miDiv.removeChild(miDiv.firstChild);
               }


            //Comprobamos si hay errores, si los hay no se muestra resultado
            //se muestra en un div los errores de los campos
            if (arrayJSON.errores !== undefined){
                var misErrores = arrayJSON.errores; 
                let divError = document.createElement("div");
                divError.setAttribute("class", "error");

               
                if (misErrores.numeros !== undefined){ //Si hay errores de numeros
                    var numeros = misErrores.numeros;
                    for(let numero of numeros){
                        let parrafo = document.createElement("p");
                        let texto = document.createTextNode(numero);
                        parrafo.appendChild(texto);
                        divError.appendChild(parrafo);
                    }

                }

                if (misErrores.patron !== undefined){ //Si hay errores de patrón
                    var patron = misErrores.patron;
                    for(let error of patron){
                        let parrafo = document.createElement("p");
                        let texto = document.createTextNode(error);
                        parrafo.appendChild(texto);
                        divError.appendChild(parrafo);
                    }

                }

                miDiv.appendChild(divError); //lo añadimos al div de respuestas
            }
            else{ //Si no hay errores
                var palabras = arrayJSON.palabras; //array de patron
                var numeros = arrayJSON.numeros; //array de numeros
    
    
                var parraPalabra = document.createElement("span") ;
                var creaP1 = document.createTextNode("Resultado palabras: ");
                parraPalabra.appendChild(creaP1);
                miDiv.appendChild(parraPalabra);


                for(let palabra of palabras){ //Iteramos las palabras y las añadimos en un p
                    let respuesta = document.createElement("p");
                    let cadenaRes = document.createTextNode(palabra);
                    respuesta.appendChild(cadenaRes);
                    miDiv.appendChild(respuesta); //añadimos cada p al div principal
                }
    
    
                var parraNumero = document.createElement("span");
                var creaP2 = document.createTextNode("Resultado de números: ");
                parraNumero.appendChild(creaP2);
                miDiv.appendChild(parraNumero);
    
                for(let numero of numeros){
                    let respuesta = document.createElement("p");
                    let cadenaRes = document.createTextNode(numero);
                    respuesta.appendChild(cadenaRes);
                    miDiv.appendChild(respuesta);
                }
    
            }

           })
        }
    })
    .catch(function(e) {
        console.error("Error: " + e);
    });
}

