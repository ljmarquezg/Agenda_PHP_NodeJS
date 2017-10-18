

class EventManager {
    constructor() {
        this.urlBase = "/events"
        this.obtenerDataInicial()
        this.inicializarFormulario()
        this.guardarEvento()
    }

    obtenerDataInicial() {
        let url = this.urlBase + "/all"
        $.get(url, (response) => {
            this.inicializarCalendario(response)
        })
    }

    eliminarEvento(evento) {
        let eventId = evento._id
        $.post('/events/delete/'+eventId, {id: eventId}, (response) => {
            alert(response)
        })
    }

    guardarEvento() {
        $('.addButton').on('click', (ev) => {
            ev.preventDefault()
            let nombre = $('#titulo').val(),
            start = $('#start_date').val(),
            title = $('#titulo').val(),
            end = '',
            start_hour = '',
            end_hour = '';

            if (!$('#allDay').is(':checked')) {
                end = $('#end_date').val()
                start_hour = $('#start_hour').val()
                end_hour = $('#end_hour').val()
                start = start + 'T' + start_hour
                end = end + 'T' + end_hour
            }
            let url = this.urlBase + "/new"
            if (title != "" && start != "") {
                let ev = {
                    title: title,
                    start: start,
                    end: end
                }
                $.post(url, ev, (response) => {
                    alert(response)
                })
                $('.calendario').fullCalendar('renderEvent', ev)
            } else {
                alert("Complete los campos obligatorios para el evento")
            }
        })
    }

    inicializarFormulario() {
        $('#start_date, #titulo, #end_date').val('');
        $('#start_date, #end_date').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30,
            minTime: '5',
            maxTime: '23:59:59',
            defaultTime: '',
            startTime: '5:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        $('#allDay').on('change', function(){
            if (this.checked) {
                $('.timepicker, #end_date').attr("disabled", "disabled")
            }else {
                $('.timepicker, #end_date').removeAttr("disabled")
            }
        })
    }

    inicializarCalendario(eventos) {
      var d = new Date();
        $('.calendario').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,basicDay'
            },
            defaultDate: d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate(),
            navLinks: true,
            editable: true,
            eventLimit: true,
            droppable: true,
            dragRevertDuration: 0,
            timeFormat: 'H:mm',
            eventDrop: (event) => {
                this.actualizarEvento(event)
            },
            events: /*[{
            title  : 'event3',
            start  : '2017-10-09T12:30:00',
            allDay : false // will make the time show
          }], //*/eventos,
            eventDragStart: (event,jsEvent) => {
                $('.delete').find('img').attr('src', "../img/trash-open.png");
                $('.delete').css('background-color', '#a70f19')
            },
            eventDragStop: (event,jsEvent) =>{
              var trashEl = $('.delete');
              var ofs = trashEl.offset();
              var x1 = ofs.left;
              var x2 = ofs.left + trashEl.outerWidth(true);
              var y1 = ofs.top;
              var y2 = ofs.top + trashEl.outerHeight(true);
              if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
                  jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                    $('.calendario').fullCalendar('removeEvents', event._id);
                    this.eliminarEvento(event, jsEvent)
              }
              $('.delete').find('img').attr('src', "../img/trash.png");

            }
            })
        }

        actualizarEvento(evento) {

          if(evento.end === null){ //Verificar si el evento es de dia completo
            var start = moment(evento.start).format('YYYY-MM-DD'),
                url = '/events/update/'+evento._id+'&'+start+'&'+start
          }else{
            var start = moment(evento.start).format('YYYY-MM-DD HH:mm:ss'),
                end = moment(evento.end).format('YYYY-MM-DD HH:mm:ss'),
                url = '/events/update/'+evento._id+'&'+start+'&'+end
          }

            var  data = {
                  id: evento._id,
                  start: start,
                  end: end
              }
              $.get(url, data, (response) => {
                  alert(response)
              })
        }


    }

    const Manager = new EventManager()
