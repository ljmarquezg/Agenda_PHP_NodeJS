let mongoose = require('mongoose'), //Requerir el módulo mongoose
    Schema = mongoose.Schema, //Definir la variable Schema
    Usuarios = require('./modelUsuarios'),
    autoIncrement = require('mongoose-auto-increment'), //Requerir módulo para autoincrementar valor del Id.

    EventSchema = new Schema({ //Cerar el esquema de los Eventos
      title:{ type: String, required: true }, //Definir titulo del evento - Obligatorio
      start: { type: String, required: true }, //Inicio del evento - Obligatorio
      end: { type: String, required: false }, //Finalizacion del evento - No obligatorio
      user: { type: Schema.ObjectId, ref: "Usuario" }
    });

autoIncrement.initialize(connection) //Inicializar el módulo de autoincrementar en la variable conexión
EventSchema.plugin(autoIncrement.plugin, {model: 'Evento', startAt: 1} ); //Asignar el plugin de autoincrementar al esquema Evento

let EventoModel = mongoose.model('Evento', EventSchema) //Definir el modelo de los eventos

module.exports = EventoModel //Exportar el modelo evento
