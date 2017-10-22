let mongoose = require('mongoose'), //Requerir el módulo mongoose
    Schema = mongoose.Schema //Definir la variable Schema

let UserSchema = new Schema({ //Cerar el esquema de los usuarios
  user: { type: String, required: true, unique: true}, //variable usuario - Obligatoria
  email: { type: String, required: true }, //variable email - Obligatoria
  password: { type: String, required: true}, //variable contraseña - Obligatoria
  })

let UsuarioModel = mongoose.model('Usuario', UserSchema) //Definir el modelo del usuario

module.exports = UsuarioModel //Exportar el modelo del usuario
