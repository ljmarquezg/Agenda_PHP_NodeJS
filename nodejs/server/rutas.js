const Router = require('express').Router();
const Usuarios = require('./modelUsuarios.js')
const Operaciones = require('./crud.js')
//Verificar si existe el usuario DEMO
Router.get('/demo', function(req, res) {
  Usuarios.find({user: req.query.user}).count({}, function(err, count) { //Verificar si exste el usuario DEMO
    if(count>0){ //Si el registro es mayor a 0
        res.send("Utilice los siguientes datos: </br>email: demo@mail.com | password:123456")
    }else{
      Operaciones.crearUsuarioDemo((error, result) => { //Si no , llamar la función crearUsuarioDemo en el modelo modelUsuarios.js
        if(error){
          res.send(error)
        }else{
          res.send(result)
        }
      })
    }
  })
})

//Validar formulario de inicio de sesion
Router.post('/login', function(req, res) {
    let user = req.body.user
    let password = req.body.pass
    Usuarios.find({user: user}).count({}, function(err, count) { //Verificar que el usuario está registrado
        if (err) {
            res.status(500)
            res.json(err)
        }else{

          if(count == 1){ //Si el usuario existe
            Usuarios.find({user: user, password: password }).count({}, function(err, count) { //Verificar su contraseña
                if (err) {
                    res.status(500)
                    res.json(err)
                }else{
                  if(count == 1){ //Si ambos campos coinciden con el registro de la base de datos, enviar mensaje Validado
                    res.send("Validado")
                  }else{ //Si la contraseña no coincide, enviar mensaje de error de contraseña
                    res.send("Contraseña incorrecta")
                  }
                }
            })
          }else{
            res.send("Usuario no registrado") //Mostrar mensaje Usuario o registrado
          }
        }

    })
})

// Obtener un usuario por su id
Router.all('*', function(req, res) {
  res.send('No se encontro el recurso solicitado')
  res.end()
})

module.exports = Router
