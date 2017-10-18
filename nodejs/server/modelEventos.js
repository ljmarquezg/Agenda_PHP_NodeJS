let mongoose = require('mongoose')

let Schema = mongoose.Schema

let EventSchema = new Schema({
  title:{ type: String, required: true },
  start: { type: String, required: true },
  end: { type: String, required: false },
  })

let EventoModel = mongoose.model('Evento', EventSchema)

module.exports = EventoModel
