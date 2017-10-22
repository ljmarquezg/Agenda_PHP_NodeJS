var Usuario = require('./modelUsuarios.js'), //Asignarle a la variable USUARIO el modelo del usuario
    Eventos = require('./modelEventos.js') //Asignarle a la variable USUARIO el modelo del usuario

module.exports.crearUsuarioDemo = function(callback){ //Función para crear el usuario DEMO
  let Demo = new Usuario({ email: 'demo@mail.com', user: "demo", password: "123456"}) //Inicializa la base de datos con un usuario DEMO
  Demo.save((error) => { //Ejecutar la sentencia de guardado en la base de datos
      if (error){ //Acciones si existe un error
        if (error.code == 11000){ //Verificar si el nombre de usuario (PrimaryKey) del existe
          callback("Utilice los siguientes datos: </br>usuario: demo | password:123456") //Mostrar mensaje
        }else{
          callback(error.message) //Mostrar mensaje de error
        }
      }else{
        callback(null, "El usuario DEMO se ha registrado correctamente. </br>usuario: demo | password:123456") //Mostrar mensaje del usuario guardado con exito
      }
  })
}
