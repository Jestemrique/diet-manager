  //alert("hola");
  console.log("page=diet-plans");

  var table = document.getElementById("tableDietPlan");
  var botonAddDia = document.getElementById("addDia");
  botonAddDia.addEventListener("click", addFila);
  //botonAddDia.addEventListener("click", addFila(this));



  function addFila(){
    var row = table.insertRow(-1);
    console.log(row.rowIndex);
    var celdaDia = row.insertCell(0);
    var celdaDesayuno = row.insertCell(1);
    var celdaComida = row.insertCell(2);
    var celdaMerienda = row.insertCell(3);
    var celdaCena = row.insertCell(4);
    var celdaNotas = row.insertCell(5);
    var celdaEliminar = row.insertCell(6);

    celdaDia.innerHTML = "<div> <input type=\"date\" id=\"dia\" name=\"dia\"> </div>";
    celdaDesayuno.innerHTML = "<div> <input type=\"text\" class=\"form-control\" placeholder=\"Desayuno\"></div>";
    celdaComida.innerHTML =   "<div> <input type=\"text\" class=\"form-control\" placeholder=\"Comida\"></div>";
    celdaMerienda.innerHTML = "<div> <input type=\"text\" class=\"form-control\" placeholder=\"Merienda\"></div>";
    celdaCena.innerHTML = " <div> <input type=\"text\" class=\"form-control\" placeholder=\"Cena\"></div>";
    celdaNotas.innerHTML = "<div> <input type=\"text\" class=\"form-control\" placeholder=\"Notas\"></div>";
    celdaEliminar.innerHTML = " <div> <button id=\"eliminarDia" + row.rowIndex + "\">-</button></div>";

    
    botonEliminarFila = document.getElementById("eliminarDia" + row.rowIndex);
    botonEliminarFila.addEventListener("click", () => {
      table.deleteRow(row.rowIndex);
    });

  }; //End addFila();




  
