
@extends('layouts.main')

@section('content')
   <h1>Lista de tareas</h1>
   <div class="row">
   
        <div class="col-12">
            <label>Crear Task</label>
        </div>
   </div>
   <div class="row">
   
    <div class="col-12">
            <div class="form-group">
                <input type="text"  name="description" id="description"  placeholder="">
                <br>
                <button type="button" class="btn" onclick="createTask()">Crear</button>
 
            </div>
        </div> 
    </div>
    <div class="row">
       <div class="col-12">
           <table class="table" id = "table">
               <thead>
                   <tr>
                       <th>#</th>
                       <th>Descripcion</th>
                       <th>Pendiente</th>
                       <th>Terminado</th>

                   </tr>
               </thead>
               <tbody>
                   @foreach ($tasks as $task)
                       <tr id = "{{$task->id}}">
                           <td>{{ $task->id}}</td>
                           <td>{{ $task->description}}</td>
                           
                           <td id = "{{$task->id}}_done"> {{$task->is_done ? 'No' : 'Si'}}</td>
                           <td>
                         <button type="button" class="btn " onclick = "updateTask({{$task->id}})">Terminado</button>
                           </td> 
                           <td>
                          <button type="button" class="btn " onclick = "deleteTask({{$task->id}})">Borrar</button>
                               
                               
                           </td>
                       </tr>
                   @endforeach
                   
                   
               </tbody>
           </table>
       </div>
   </div>
   

@endsection

@push('layout_end_body')
<script>
    function createTask() {
       let theDescription = $('#description').val();
       $.ajax({
           url: '{{ route('tasks.store') }}',
           method: 'POST',
           headers:{
               'Accept': 'application/json',
               'X-CSRF-Token': $('meta[name="csrf-token"').attr('content')
           },
           data: {
               description: theDescription
           }
       })
       .done(function(response) {
           
           $('.table tbody').append('<tr id= '+ response.id +'><td>' + response.id + '</td><td>' + response.description + '</td><td id = "'+response.id+'_done">Si</td><td><button type="button" class="btn " onClick = "updateTask('+response.id+')">Terminado</button></td><td><button type="button" class="btn" onclick = "deleteTask('+ response.id +')">Borrar</button></td></tr>')
           $('#description').val('');
       })
       .fail(function(jqXHR, response) {
           console.log('Fallido', response);
       });
   }

   function deleteTask(task){
    let durl = '{{ route('tasks.destroy', 0) }}'+task+''
        
        $.ajax({
            url: durl,
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done((response) => {
            var row = document.getElementById(task);
            row.parentNode.removeChild(row);
            
        }).fail((jqXHR, response)=> {
            console.log('Fallido', response);
        })
    }


   function updateTask(task){
       let durl = '{{ route('tasks.update', 0) }}'+task+''
       $.ajax({
           url: durl,
           method: 'PUT',
           headers:{
               'Accept': 'application/json',
               'X-CSRF-Token': $('meta[name="csrf-token"').attr('content')
           },
           data:{
               task:task  
           }
       }).done(function(response) {
           let idTask = ''+task+'_done'
           document.getElementById(idTask).innerHTML = "No"
       })
       .fail(function(jqXHR, response) {
           console.log('Fallido', response);
       });
   }
</script>    

@endpush