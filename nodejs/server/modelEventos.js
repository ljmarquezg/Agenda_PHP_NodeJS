const mongoose = require('mongoose')

const Schema = mongoose.Schema

let EventSchema = new Schema({
  id: { type: Number, required: true, index: {unique: true}},
  start_date: { type: String, required: true },
  start_hour: { type: String, required: false},
  end_date: { type: String, required: false },
  end_hour: {type: String, required: false},
  allday: {type: Boolean, required:false},
  fk_usuarios: { type: Schema.ObjectId, ref: 'Usuarios' }
  })

let EventoModel = mongoose.model('Evento', EventSchema)

module.exports = EventoModel
