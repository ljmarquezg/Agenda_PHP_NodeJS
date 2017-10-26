function showMessage(message){ //Mensajes de error
  $('#message').html('<p>'+message+'</p>').fadeIn('slow').delay(5000) //Mostrar y ocultar mensaje despues de 5 segundos
  $('.overlay').fadeOut('slow')
}

function verificarConexion(){
  $.ajax({
    url:'../server/check_database.php',
    type:'post',
    data:{},
    dataType: 'json',
    success:function(data){
      if(data.phpmyadmin != "OK"){
        $('.row.align-center').fadeOut('fast')
        showMessage(data.msg) //Mostrar Mensaje de error
      }else{
        crearDB() //Funcion para crear base de datos
      }
      },
    error:function(data){
      showMessage(data) //Mostrar Mensaje de error
    }
  })
}

function crearDB(){
  self = $('.loader-container h3');
  self.text('Verificando conexion a base de datos'); //Mostrar mensaje en el titulo contenido dentro del div con clase loader-container
  $.ajax({
    url:'../server/crearDB.php', //url a consultar
    type:'post', //Método de envío de información
    data:{}, //Data a enviar
    dataType: 'json', //Formato de la información
    success: function(data){
      if(data.database == "OK"){ //Verificar respuesta recibida en la consulta
        $('.loader-container h3').html(data.msg) //Mostrar el mensaje
        crearTabla() //Ejecutar función de Crear tablas
      }else{
        $('.row.align-center').fadeOut('fast') //Ocultar campos de inicio de sesión
        showMessage(data.msg); //Mostrar mensaje de error
      }
    },
    error: function(data){
      $('.row.align-center').fadeOut('fast')
      showMessage(data.msg) //Mostrar mensaje de console.error();
    }
  })
}

function crearTabla(){
  self = $('.loader-container h3')
  self.text('Verificando Tablas');
  $.ajax({
    url:'../server/check_tablas.php',
    type:'post',
    data:{},
    dataType:'json',
    success: function(data){
      $('.overlay').delay(750).fadeOut('slow')
      if(data.msg =="OK"){
        var l = new Login();
        l.generarUsuarios() //Agregar usuarios a la base de datos
        self.html('<p>Sistema inicializado</p>') //Mostrar mensaje
      }else{
        $('.row.align-center').fadeOut('fast') //Ocultar campos de inicio de sesión
        showMessage(data.msg).fadeIn() //Mostrar mensaje de console.error();
      }
    },
    error:function(data){
      $('#message').text((JSON.stringify(data.responseText))); //Mostrar respuesta del servidor si ocurrión un error al enviar la consulta
    }
  })
}

$(function(){
  verificarConexion() //Ejecutar la función verificar conexión al cargar la página
  var l = new Login(); //llamar el objeto Login
  $('#generarUsuarios').on('click', function(e){ //Agergar un listener al div con id generarUsuarios
    crearTabla(); //Iniciar la funcion de verificar las tablas en la base de datos
  })
  validarSession(); //Ejecutar la función validar la sesión para verificar que no hayan sesiones iniciadas
})

/*Verificar que no exista una sesión iniciada*/
function validarSession(){
  $.ajax({
    url: '../server/session.php',
    type:'post',
    data:{},
    dataType:"json",
    success: function(data){
      if(data.msg !=""){ //Si la respuesta del servidor no es vacía
        alert("Ya existe una sesión iniciada. Redireccionando") //Mostrar mensaje de sesión iniciada
        window.location.href = './main.html' //Redireccionar a la página main.html
      }
    },
    error: function(data){
      $('.row.align-center').fadeOut('fast') //En caso de ocurrir un error
      alert("Error inesperado. "+data )//Mostrar mensaje con la respuesta de la consulta
    }
  })
}

class Login {
  constructor() {
    this.submitEvent()
  }

  submitEvent(){
    $('form').submit((event)=>{
      event.preventDefault()
      this.sendForm()
    })
  }

  sendForm(){
    let form_data = new FormData();
    form_data.append('username', $('#user').val())
    form_data.append('password', $('#password').val())
    $.ajax({
      url: '../server/check_login.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: function(php_response){
        if (php_response.conexion == "OK") {
        if (php_response.acceso == "Usuario Autorizado") { //Si la respuesta de la consulta conicide
            $('#message').css({ background: "rgba(3, 147, 3, 0.6)" }) //Cambiar el color del fondo del mensaje
              window.location.href = "./main.html"; //Redireccionar a la página main.html
          }
          if (php_response.acceso == "Acceso rechazado") {//En caso de rechazar el inicio de sesión
            $('#message').css({ background: "rgba(164, 11, 11, 0.7)"}) //Cambiar el color de fondo del mensaje
          }

          if(php_response.acceso == "No existen usuarios registrados"){
          $('#message').css({ background: "rgba(164, 11, 11, 0.7)"}) //Cambiar el color de fondo del mensaje
            $('#generarUsuarios').fadeIn('slow') //Mostrar mensaje
          }
            showMessage(php_response.acceso + ". " + php_response.msg); //Mostrar el mensaje recibido
        }
      },
    })
  }

  generarUsuarios(){
    $.ajax({
      url: '../server/create_user.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(php_response){
        if (php_response.conexion == "OK") {
          $('.row.align-center').fadeIn('fast')
          if (php_response.resultado == "1") {
            $('#message').css({ background: "#03930399" })//cambiar color de findo del mensaje
          }else{
            $('#message').css({ background: "#a40b0bb3" })//cambiar color de findo del mensaje
          }
          showMessage(php_response.msg);
        }
      },
      error: function(){
        $('#message').css({ background: "#a40b0bb3" })
        showMessage("Ha ocurrido un error al generar los usuarios"); //Mensaje de error
      }
    })
  }
}
