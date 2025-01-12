 
 var editModal = document.getElementById('contratoModal');
 editModal.addEventListener('show.bs.modal', function(event) {
     var button = event.relatedTarget; 
     var userId = button.getAttribute('data-id');
     var matricula = button.getAttribute('data-matricula');
     var nombre_usuario = button.getAttribute('data-nombreUsuario');
     var fechaI = button.getAttribute('data-fechaInicio');
     

     document.getElementById('matriculaC').innerHTML = " " + matricula;
     document.getElementById('nombreC').innerHTML = " " + nombre_usuario;
     document.getElementById('fechaI').innerHTML = " " + fechaI;

 });

 
 $('#contratoModal').on('hidden.bs.modal', function() {
     $('#editForm')[0].reset();
 });