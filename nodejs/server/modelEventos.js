let mongoose = require('mongoose'),
    Schema = mongoose.Schema,
    autoIncrement = require('mongoose-auto-increment'),

  EventSchema = new Schema({
  title:{ type: String, required: true },
  start: { type: String, required: true },
  end: { type: String, required: false },
  })

  autoIncrement.initialize(connection)
  EventSchema.plugin(autoIncrement.plugin, 'Evento');

let EventoModel = mongoose.model('Evento', EventSchema)

module.exports = EventoModel
