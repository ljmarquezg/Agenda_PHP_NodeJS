var express = require('express')
var path = require('path')
var fs = require('fs')
var app = express()
var Router = express.Router()

var viewsPath = path.join(__dirname, '../') + 'public/'


// Declare variables

Router.get('/data.json', function(req, res, next){
  var obj;
  fs.readFile(viewsPath + 'data.json', handleFile);
  function handleFile(err, data) {
      if (err) throw err;
      obj = JSON.stringfy(data);
      res.json(obj)
  }
})

Router.get('/index.html', function(req, res) {
  res.sendFile(viewsPath + 'index.html');
})

Router.all('*', function(req, res) {
  res.send('No se encontro el recurso solicitado')
  res.end()
})

module.exports = Router
