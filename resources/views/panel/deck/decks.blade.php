@extends('panel/index')
@section('titulocontenido')
Decks
@endsection

@section('subtitulo')
Decks registrados hasta el momento
@endsection

@section('contenido')

@role('Owner')
<div class="text-right">
    <button type="button" class="btn btn-success btn-rounded mb-3 " data-toggle="modal"
        data-target="#primary-header-modal"><i class="fas fa-check"></i>Crear Deck</button>


</div>



<div id="primary-header-modal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="primary-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Crear nuevo Deck
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST">
                @csrf

                    <label class="form-control-label" for="nombre">Nombre del deck</label>
                    <input type="text" class="form-control" name="nombre" id="nombre">
                    <label class="form-control-label" for="descipcion">Descipcion del deck</label>
                    <input type="text" class="form-control" name="descipcion" id="descipcion">
                    <label class="form-control-label" for="admin">Administrador</label>
                    <input type="text" class="form-control" name="admin" id="admin">

                    <label class="form-control-label" for="rt">Cantidad RT/H</label>
                    <input type="text" class="form-control" name="rt" id="rt" value="1" readonly>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endrole


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>

                <th scope="col">Deck</th>
                <th scope="col">Admin</th>
                <th scope="col">Descripción</th>
                <th scope="col">Seguidores</th>
            </tr>
        </thead>
        <tbody>
        @foreach($decks as $deck)
            <tr>
                <td><a href="http://www.feed-deck.com/decks/{{str_replace(' ','_',$deck['nombre'])}}">{{$deck['nombre']}}</a></td>
                <td>{{$deck['admin']}}</td>
                <td>{{$deck['descripcion']}}</td>
                <td>{{$deck['numero']}}</td>

            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
@endsection
