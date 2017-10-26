const RouterEventos = require('express').Router();
const Usuario = require('./modelUsuarios.js')
const Evento = require('./modelEventos.js') //Definir el modelo Eventos en una variable
const Operaciones = require('./crud.js') //Requerir el archivo crud.js
let ObjectId = require('mongoose').Types.ObjectId;

// Obtener todos los eventos del usuario logueado
RouterEventos.get('/all', function(req, res) {
  req.session.reload(function(err) { //Recargar la información de la sesión guardada
    if(req.session.user){ //Verificar que haya un usuario con sesión iniciada
      if(err){
        res.send('logout'); //Devolver mensaje "logout"
        res.end()
      }else{
        Usuario.findOne({user:req.session.user}).exec({}, function(error, doc){
          if(error){
            res.send('logout'); //Devolver mensaje "logout"
          }else{
            Evento.find({user: doc._id}).exec(function(err, doc){ //Ejecutar sentencia para buvcar todos los registros en la base de datos correspondientes al usuario logueado
              if (err) {
                res.status(500)//Devolver error
                res.json(err) //Devolver error
              }
              res.json(doc) //devolver el array de información
            })
          }
        })
      }
    }else{ //Si no existe sesión iniciada
      res.send('logout'); //Devolver mensaje "logout"
      res.end()
    }
  })
})

RouterEventos.all('/', function(req, res) {
  res.send('Error al mostrar el recurso solicitado. Por favor verifique la dirección url a la cual desea ingresar' )
  res.end()
})

// Crear eventos
RouterEventos.post('/new', function(req, res) {
  req.session.reload(function(err) { //Recargar la información de la sesión guardada
    if(err){
      console.log(err); //Mostrar error en cosola
      res.json("logout"); //Devolver mensaje "logout"
    }else{
      Usuario.findOne({user:req.session.user}).exec({}, function(error, doc){
        Evento.nextCount(function(err, count) { //La función nextCount del modulo autoincrement obtiene el valor del último registro guardado en el modelo Evento
          newID = count //Asignar el valor del identificador a la variable newID
        });

        let title = req.body.title, //Asignar el valor del título del evento redibido desde el formulario
        start = req.body.start, //Asignar el valor del inicio del evento redibido desde el formulario
        end   = req.body.end, //Asignar el valor del fin del evento redibido desde el formulario
        userId  = doc._id //Asignar el valor del identificador del usuario actual con el de sesión iniciada al registro actual

        let evento = new Evento({ //Crear un nuevo objeto evento con los valores correspondientes
          title: title,
          start: start,
          end: end,
          user: userId
        })
        evento.save(function(error) { //Guardar el registro en la base de datos
          if (error) {
            console.log(error) //Mostrar error en cosola
            res.json(error) //Devolver mensaje de error
          }
          res.json(newID) //Devolver el valor del ultimo id como callback para ser utilizado como parámetro _id en el renderizado del último evento creado
        })
      })
    }
  })
})

// Eliminar un evento que coincida con su id
RouterEventos.post('/delete/:_id', function(req, res) {
  let id = req.params._id //Obtener el identificador del evento
  req.session.reload(function(err) {
    if(err){
      console.log(err) //Mostrar error en cosola
      res.send("logout") //Devolver mensaje logout
    }else{
      Evento.remove({_id: id}, function(error) { //Ejecutar la función remover evento pasándo como parámetro el id del evento
        if(error) {
          console.log(error) //Mostrar error en cosola
          res.status(500)
          res.json(error)
        }
        res.send("Registro eliminado") //Devolver mensaje de registro eliminado
      })
    }
  })
})

//Actualizar evento
RouterEventos.post('/update/:_id&:start&:end', function(req, res) { //Obtener el identificador el evento, fecha de inicio y finalización desde el formulario
  req.session.reload(function(err) {
    if(err){
      console.log(err) //Mostrar error en cosola
      res.send("logout") //Devolver mensaje logout
    }else{
      Evento.findOne({_id:req.params._id}).exec((error, result) => { //Encontrar el evento por su identificador
        let id    = req.params._id, //asignar a la variable id el valor obtenido del formulario
        start = req.params.start, //asignar a la variable start el valor obtenido del formulario
        end   = req.params.end //asignar a la variable end el valor obtenido del formulario
        if (error){ //En caso de error
          res.send(error) //Enviar error
        }else{
          Evento.update({_id: id}, {start:start, end:end}, (error, result) => { //Ejecutar la función actualizar enviando como parámetros de búsqueda el id del evento y como datos a actualizar la fecha inicial y final
            if (error){ //En caso de error
              res.send(error )//Enviar error
            }else{
              res.send("Evento ha sido actualizado") //Enviar mensaje exitoso
            }
          })
        }
      })
    }
  })
})

module.exports = RouterEventos //Exportar rutas de los eventos
