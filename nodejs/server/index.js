//db.agenda.usuarios.insert({email:'demo@mail.com', password:'123456', nombre:'Usuario Demo', fecha_nacimiento:'1988-09-08'})

const http = require('http'),
      path = require('path'),
      Routing = require('./rutas.js'),
      express = require('express'),
      bodyParser = require('body-parser'),
      MongoClient = require('mongodb').MongoClient
      mongoose = require('mongoose');

const PORT = 3000
const app = express()

const Server = http.createServer(app)

mongoose.connect('mongodb://localhost/agenda_db')

app.use(express.static('../client'))
app.use(bodyParser.json())
app.use(bodyParser.urlencoded({ extended: true}))
app.use('/usuarios', Routing)

Server.listen(PORT, function() {
  console.log('Server is listeng on port: ' + PORT)
})
