var Usuario = require('./modelUsuarios.js'),
    Eventos = require('./modelEventos.js')

module.exports.crearUsuarioDemo = function(callback){ //Función para crear el usuario DEMO
  let Demo = new Usuario({ email: 'demo@mail.com', user: "demo", password: "123456"})
  Demo.save((error) => {
      if (error){
        if (error.code == 11000){ //Verificar si el email (PrimaryKey) del usuario existe
          callback("Utilice los siguientes datos: </br>usuario: demo | password:123456")
        }else{
          callback(error.message) //Mostrar mensaje de error
        }
      }else{ //Mostrar mensaje del usuario guardado con exito
        callback(null, "El usuario DEMO se ha registrado correctamente. </br>usuario: demo | password:123456")
      }
  })
}

module.exports.consultarLogin = function(callback) {
    Usuario.find({email: email, password: password}).exec((error, result) => {
        console.log("Email: "+ email +" Password:"+ password)
        if (error) callback(error)
        console.log(result)
    })
}

module.exports.insertarEvento = function(callback) {
    let evento = new Evento({ id: "Mateo", edad: 28, peso: 90 })
    evento.save((error) => {
        if (error) callback(error)
        callback(null, "Registro guardado")
    })
}

module.exports.eliminarRegistro = function(callback) {
    Evento.remove({ email:'demomail@mail.com' }, (error) => {
        if (error) callback(error)
        callback(null, "Se elimino el registro correctamente");
    })
}


module.exports.consultarYActualizar = function(callback) {
    Usuario.find({}).exec((error, result) => {
        if (error) callback(error)
        console.log(result)
        Usuario.update({nombre: "Mateo"}, {peso: 40}, (error, result) => {
            if (error) callback(error)
            callback(null, result)
        })
    })
}

module.exports.ordenarConsulta = function(callback) {
    Usuario.find({}).sort({edad: -1}).exec((error, result) => {
        if (error) callback(error)
        callback(null, result)
    })
}
