<!-- Edit Modal -->

<div id="Edit-{{$parent->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/menu/management/update', $parent->id) }}">
      {{ csrf_field() }}
    
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit menu {{$parent->display}} </h4>
      </div>
            
      <div class="modal-body">
        <p>
        <div class="box-body">

          <div class="form-group row add">            
            <div class="col-md-12">
                <input type="hidden" class="form-control" name="id"
                    value="{{ $parent->id }}" required>                
            </div>            
          </div>

          <div class="form-group row add">
            <div class="col-md-3">
               <label for="namaakun" class="control-label">Nama :</label>
            </div>
            <div class="input-group col-md-8">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" name="name" value="{{ $parent->display }}">
                <p class="error text-center alert alert-danger hidden"></p>
            </div>            
          </div>           
          
        </div>
        </p>
      </div>

      <div class="modal-footer">
         <button class="btn btn-primary" type="submit" id="edit">
            <span class="glyphicon glyphicon-plus"></span> Simpan
          </button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    
    </div>
    </form>
    
  </div>
</div>

