var dominio = document.location.protocol + "//" + document.location.hostname;
var URL = dominio + "/prueba/ajaxProveedor";



document.getElementById("ele").addEventListener("click", (event)=>{
    
    var texto=document.getElementById("texto").value;
    var datos = new Object({
        name: texto,
        tlf: 123456789
    })
    
    

    fetch (URL, {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
        body: "name="+texto+"&segundo=12"  //peticion normal
         //body: JSON.stringify(datos) //peticion como json
    })
    .then(function (response) {
        if (response.ok) {
            response.text() // response.json(), si se devuelve json
            .then(function(resp) {
                var dat=JSON.parse(resp)
                document.getElementById("pDatos").innerHTML = dat["texto1"]+dat["texto2"];
            })
            .catch(function(e) {
                console.error("Error: " + e);
            });
        }
    })
});