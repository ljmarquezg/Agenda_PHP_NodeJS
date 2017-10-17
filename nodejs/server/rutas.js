const Router = require('express').Router();
const Usuarios = require('./modelUsuarios.js')
const Eventos = require('./modelEventos.js')
const Operaciones = require('./crud.js')
//Obtener todos los usuarios

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


/*Router.get('/login', function(req, res) {
  let email = req.query.email
  let password = req.query.password
    Usuarios.find({email: email , password: password}).exec(function(err, docs) {
        if (err) {
            res.status(500)
            res.json(err)
        }
        if(res.json(docs).length > 0){
          console.log('Recorriendo registros')
            res.json(docs)
        }else{
          console.log("No hay registros en la base de datos")
        }

    })
})*/

// Obtener un usuario por su id
Router.all('*', function(req, res) {
  res.send('No se encontro el recurso solicitado')
  res.end()
})

// Agregar a un usuario
Router.post('/new', function(req, res) {
    let user = new Usuarios({
        nombre: 'demo',
        passwo: req.body.nombres,
        apellidos: req.body.apellidos,
        edad: req.body.edad,
        sexo: req.body.sexo,
        estado: "Activo"
    })
    user.save(function(error) {
        if (error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro guardado")
    })
})

// Eliminar un usuario por su id
Router.get('/delete/:id', function(req, res) {
    let uid = req.params.id
    Usuarios.remove({userId: uid}, function(error) {
        if(error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro eliminado")
    })
})

// Inactivar un usuario por su id
Router.post('/inactive/:id', function(req, res) {

})

// Activar un usuario por su id
Router.post('/active/:id', function(req, res) {

})

module.exports = Router
