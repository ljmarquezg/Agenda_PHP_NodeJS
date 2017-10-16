
    function showMessage(message){
        $('#message').html('<p>'+message+'</p>').show('blind').delay(5000)

    }

$(function(){
  var l = new Login();
  $('#generarUsuarios').on('click', function(e){
    l.generarUsuarios();
  })
  if(checkUser() != ""){
    window.location.href = "./main.html"
  }
})


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
          $('#message').css({ background: "#a40b0bb3" })
          showMessage(JSON.stringify(php_response.acceso + ". " + php_response.msg));
          if (php_response.acceso == "Usuario Autorizado") {
              $('#message').css({ background: "#03930399" })
              window.location.href = "./main.html";
          }
        }
      },
      error: function(){
          $('#message').css({ background: "#a40b0bb3" })
          showMessage("Complete todos los campos");//alert("Complete todos los campos")
      }
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
          showMessage(JSON.stringify(php_response.msg));
                    if (php_response.resultado == "1") {
                        $('#message').css({ background: "#03930399" })
                    }else{
                        $('#message').css({ background: "#a40b0bb3" })
                    }
      }
    },
      error: function(){
          $('#message').css({ background: "#a40b0bb3" })
          showMessage("Complete todos los campos");//alert("Complete todos los campos")
      }
    })
  }
}

function checkUser(){
  $.ajax({
    url: '../server/check_user.php',
    dataType:"json",
    success: function(data){
      if(data.msg ==""){
        window.location.href = './index.html'
      }else{
        $("#logout").html('Cerrar Sesión '+data.msg)
      }
    },
    error: function(data){
      //window.location.href = './index.html'
    }
  })
}