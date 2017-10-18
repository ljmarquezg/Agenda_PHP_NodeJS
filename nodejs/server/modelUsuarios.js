let mongoose = require('mongoose')

let Schema = mongoose.Schema

let UserSchema = new Schema({
  user: { type: String, required: true, unique: true},
  email: { type: String, required: true },
  password: { type: String, required: true},
  })

let UsuarioModel = mongoose.model('Usuario', UserSchema)

module.exports = UsuarioModel
