/********************************************************************************/
/*****************************   Antes de iniciar   *****************************/
/********** Leer el archivo index.html en la carpeta raiz del proyecto **********/
/********************************************************************************/
/********************************************************************************/

const http = require('http'); //requerir el módulo http
      path = require('path'), //requiere el modulo path para trabajar con directorios
      express = require('express'), //permite el enrutamiento de peticiones.
      session = require('express-session'), // requerir el modulo express-session para manejar las sesiones de los usuarios.
      bodyParser = require('body-parser'); //Módulo que permite al servidor interpretar datos en formato JSON.
      MongoClient = require('mongodb').MongoClient, //Reqierir el módulo modngodb
      mongoose = require('mongoose'), //Reqieror el módulo modngoose para crear esquemas y modelos de base de datos.
      //Definir la ruta y nombre de la base de datos utilizando mongodb.
      connection = mongoose.connect('mongodb://localhost/agenda_db', {useMongoClient: true,}); //definir la base de datos a utilizar

const RoutingUsers = require('./rutasUsuarios.js'), //Incluir el archivo de rutas de interacción de usuarios
      RoutingEvents = require('./rutasEventos.js') //Incluir el archivo de rutas de interacción de eventos

const PORT = 3000 //Definir el puerto de conexión
const app = express() //Definir la variable express

const Server = http.createServer(app) //Crar el servidor a través del módulo http

app.use(express.static('../client')) //Definir el directorio cliente como directorio raiz
app.use(bodyParser.json()) //Iniciar el módulo body-parser para interpretar datos en formato JSON.
app.use(bodyParser.urlencoded({ extended: true}))
app.use(session({ //Iniciar modulo de manejo de sesiones
    secret: 'secret-pass', //Cadena de caracteres secreta para firmar el Identificador de la sesión cookie
    cookie: { maxAge: 3600000 }, //Mantener las cookies de la sesión iniciada por una hora
    resave: false,
    saveUninitialized: true,
  }));

app.use('/usuarios', RoutingUsers) //Incluir el módulo usuarios y definir su directorio raíz como /usuarios
app.use('/events', RoutingEvents) //Incluir el módulo eventos bajo y definir su directorio raíz como /events

Server.listen(PORT, function() { //Iniciar el servidor
  console.log('Server is listeng on port: ' + PORT) //Mostrar mensaje de inicialización del servidor en la cónsola.
})
