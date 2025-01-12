// Inicializar Flatpickr para el campo de fecha y hora de inicio
flatpickr("#fecha_inicio", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      if (selectedDates[0]) {
        const inicioDate = selectedDates[0];
        // Establecer el mínimo permitido en fecha de fin
        const minDate = inicioDate.toLocaleString('sv-SE');  // Utiliza el formato local
        document.getElementById('fechaDevolucion')._flatpickr.set('minDate', minDate);
  
        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaFin = document.getElementById('fechaDevolucion')._flatpickr.selectedDates[0];
        if (fechaFin && (fechaFin - inicioDate) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
        }
      }
    }
  });
  
  // Inicializar Flatpickr para el campo de fecha y hora de fin
  flatpickr("#fechaDevolucion", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      if (selectedDates[0]) {
        const finDate = selectedDates[0];
        // Establecer el máximo permitido en fecha de inicio
        const maxDate = finDate.toISOString().slice(0, 19);
        document.getElementById('fecha_inicio')._flatpickr.set('maxDate', maxDate);
  
        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaInicio = document.getElementById('fecha_inicio')._flatpickr.selectedDates[0];
        if (fechaInicio && (finDate - fechaInicio) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
        }
      }
    }
  });
  
  // Manejar la apertura del modal devolucionModal
  var editModalRent = document.getElementById('devolucionModal');
  
  editModalRent.addEventListener('show.bs.modal', function (event) {
    // Obtén el botón que activó el modal
    var button = event.relatedTarget;
  
    // Obtén los datos del botón
    var rentaId = button.getAttribute('data-id');
    var fecha_inicio = button.getAttribute('data-fechaInicio');
  
    // Asigna los valores a los campos correspondientes
    document.getElementById('alquilerId').value = rentaId;
    document.getElementById('fecha_inicio').value = fecha_inicio;
  
    // Configurar el campo de fecha de inicio en Flatpickr
    document.getElementById('fecha_inicio')._flatpickr.setDate(fecha_inicio, true);
  
    // Asegurarse de que el campo fechaDevolucion tenga restricciones actualizadas
    const inicioDate = new Date(fecha_inicio);
    document.getElementById('fechaDevolucion')._flatpickr.set('minDate', inicioDate.toLocaleString('sv-SE'));
  });
  


  $('#devolucionModal').on('hidden.bs.modal', function () {
    $('#formDevolucion')[0].reset(); 
    $('#alerta2').addClass('d-none'); // Ocultar alerta si existía
});

$('#formDevolucion').submit(function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: './home_section/scripts/addDev.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            var result = JSON.parse(response);

            if (result.success) {
                alert(result.message);
                location.reload(); // Recargar la página para reflejar los cambios
            } else {
                $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text(result.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error de conexión:', status, error);
            $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión con el servidor.');
        }
    });
});

