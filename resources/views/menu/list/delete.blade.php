<!-- Hapus Modal-->
@foreach ($parent as $slider)
<div id="Hapus-{{$slider->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal" role="form" method="GET" action="{{ url('/admin/settings/menu/destroy', $slider->id) }}">
      {{ csrf_field() }}
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Data {{$slider->slug}}</h4>
      </div>
      <div class="modal-body">
        <p>Name  : <b>{{$slider->display}}</b>, are you sure to delete ?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit" id="hapus">
            <span class="glyphicon glyphicon-trash"></span> Delete
          </button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </form>
  </div>
</div> 
@endforeach