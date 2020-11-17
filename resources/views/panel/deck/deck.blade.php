@extends('panel/index')
@section('titulocontenido')
Deck: {{ str_replace('_',' ',$id) }}
@endsection

@section('subtitulo')
Número total de seguidores: {{ $contador }}
@endsection


@section('contenido')
@if(session('total'))
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        RT: {{ session('total') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">

        @foreach($errors->all() as $error)
            {{ $error }}
        @endforeach

    </div>
@endif


@if(session('mensaje'))
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{ session('mensaje') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{ session('error') }}
    </div>
@endif
<div class="text-center">
    <button type="button" class="btn btn-primary btn-rounded mb-3 " data-toggle="modal" data-target="#apis"><i
            class="fas fa-bolt"></i> Apis</button>



    <button type="button" class="btn btn-success btn-rounded mb-3 " data-toggle="modal" data-target="#darrt"><i
            class="fas fa-retweet"></i> Dar RT</button>
            @if($cred->whatsapp != null)
            <a class="btn btn-danger btn-rounded mb-3 text-white" target ="_blank" href="{{$cred->whatsapp}}"><i
            class="fas fa-mobile-alt" ></i> Grupo de Whatsapp</a>
            @endif
    @role('Owner|admin-'.$id)
        <button type="button" class="btn btn-warning btn-rounded mb-3 " data-toggle="modal"
            data-target="#primary-header-modal"><i class="fas fa-user-plus"></i> Agregar usuario</button>

        <button type="button" class="btn btn-primary btn-rounded mb-3 " data-toggle="modal" data-target="#nuevoadmin"><i
                class="fas fa-user-plus"></i> Modificar administradores</button>

        <button type="button" class="btn btn-secondary btn-rounded mb-3 " data-toggle="modal"
            data-target="#editarmodal"><i class="fas fa-pencil-alt"></i> Editar Deck</button>

        <button type="button" class="btn btn-danger btn-rounded mb-3 " data-toggle="modal" data-target="#eliminar"><i
                class="fas fa-times"></i> Eliminar Deck</button>
    @endrole




</div>

<script language="JavaScript" type="text/javascript">
    alert("ESTA PROHIBIDO DAR RT A: FOTOS PERSONALES, PORN0 , ENLACES Y SPAM . BANEO AUTOMATICO");
</script>


<div id="apis" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">¿Estas seguro?
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <center>
                    <h3>Debes autorizar las dos apis para poder hacer uso del deck</h3>

                    <form class="form-group" method="POST" target="_blank"
                        action="{{ route('generar') }}">
                        @csrf


                        <input type="text" class="form-control" name="deckname" id="deckname" value="{{ $id }}"
                            style="display:none">

                        <button type="submit" class="btn btn-primary">Api1</button>
                    </form>

                    <form class="form-group" target="_blank" method="POST"
                        action="{{ route('generar1') }}">
                        @csrf


                        <input type="text" class="form-control" name="deckname" value="{{ $id }}" style="display:none">


                        <button type="submit" class="btn btn-primary">Api2</button>
                    </form>
                    @if($cred->api3key != null)

                        <form class="form-group" target="_blank" method="POST"
                            action="{{ route('generar3') }}">
                            @csrf


                            <input type="text" class="form-control" name="deckname" value="{{ $id }}"
                                style="display:none">


                            <button type="submit" class="btn btn-primary">Api3</button>
                        </form>
                    @endif


                    <strong>Luego, da clic en aplicar cambios.</strong>

                    <hr>


                    <h3 style="margin-top:15px">¿Pasó algo?</h3>
                    <a class="btn btn-danger btn-rounded mb-3 " href="{{ route('reautorizar') }}"
                        target="_blank"><i class="fas fa-bolt"></i> Reautorizar apis</a>
                    @if($cred->api3key != null)
                    <p>Si deseas reautorizar, da clic al botón, se abrirá una ventana y se cerrará automáticamente.
                        Luego de esto, puedes volver a autorizar las apis dando clic a los botones "Api1", "Api2" y "Api3"</p>
                    @else
                    <p>Si deseas reautorizar, da clic al botón, se abrirá una ventana y se cerrará automáticamente.
                        Luego de esto, puedes volver a autorizar las apis dando clic a los botones "Api1" y "Api2"</p>
                    @endif
                </center>



            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-warning" onclick="window.location.reload();">Aplicar
                    Cambios</button>

                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="eliminar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">¿Estas seguro?
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST"
                    action="{{ route('decks.destroy',['deck'=>$id]) }}">
                    @csrf
                    @method('DELETE')




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Eliminar Deck</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<div id="primary-header-modal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="primary-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Añadir un nuevo usuario al Deck
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST"
                    action="{{ route('nuevouser',['id'=>$id]) }}">
                    @csrf

                    <label class="form-control-label" for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Agregar usuario</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="nuevoadmin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Añadir o borrar un admin del Deck
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST"
                    action="{{ route('nuevoadmin',['id'=>$id]) }}">
                    @csrf

                    <label class="form-control-label" for="username">Username</label>
                    <input type="text" class="form-control mb-3" name="username" id="username" required>
                    <label class="form-control-label" for="accion">¿Qué deseas?</label>

                    <select class="form-control" id="accion" name="accion">
                        <option>Añadir</option>
                        <option>Eliminar</option>

                    </select>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Modificar usuario</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<div id="darrt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Dar RT
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST" action="{{ route('rt') }}">
                    @csrf

                    <label class="form-control-label" for="rtid">ID Tweet</label>
                    <input type="text" class="form-control" name="rtid" id="rtid" required>
                    <input type="text" class="form-control" name="deckname" id="deckname" value="{{ $id }}"
                        style="display:none">





            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Retweetear</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="editarmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Configuracion del deck
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-group" method="POST"
                    action="{{ route('decks.update',['deck'=>$id]) }}">
                    @csrf
                    @method('PUT')

                    <label class="form-control-label" for="rth">RT/H del deck</label>
                    <input type="text" class="form-control" name="rth" id="rth" value="1" readonly>

                    <label class="form-control-label" for="key1">Key</label>
                    <input type="text" class="form-control" name="key1" id="key1" value="{{ $cred->crearkey }}">

                    <label class="form-control-label" for="secret1">Secret</label>
                    <input type="text" class="form-control" name="secret1" id="secret1"
                        value="{{ $cred->crearsecret }}">

                    <label class="form-control-label" for="key2">Key 2</label>
                    <input type="text" class="form-control" name="key2" id="key2" value="{{ $cred->borrarkey }}">


                    <label class="form-control-label" for="secret2">Secret 2</label>
                    <input type="text" class="form-control" name="secret2" id="secret2"
                        value="{{ $cred->borrarsecret }}">

                    <label class="form-control-label" for="key3">Key 3</label>
                    <input type="text" class="form-control" name="key3" id="key3" value="{{ $cred->api3key }}">


                    <label class="form-control-label" for="secret3">Secret 3</label>
                    <input type="text" class="form-control" name="secret3" id="secret3"
                        value="{{ $cred->api3secret }}">

                    
                        <label class="form-control-label" for="whatsapp">Link grupo Whatsapp</label>
                        <input type="text" class="form-control" name="whatsapp" id="whatsapp"
                            value="{{ $cred->whatsapp }}">



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar cambios</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col"> </th>

                <th scope="col">@Twitter</th>

                <th scope="col">Seguidores</th>
                @role('admin-'.$id.'|Owner')
                    <th scope="col">Acciones</th>
                @endrole
            </tr>
        </thead>
        <tbody>
            @foreach($decks as $deck)
                <tr>
                    
                        
                        @if (in_array($deck->username,$admins))
                        <td>{{ $deck->username }} <strong>Admin</strong></td> 
                        @else
                        <td>{{ $deck->username }}</td>
                        @endif
                    
                    
                    <td>
                        <img src="{{ $deck->img }}" class="rounded-circle" width="30"></img>

                    </td>
                    <td>
                        <a href="https://twitter.com/{{ $deck->twitter }}">
                            {{ $deck->twitter }}
                        </a>

                    </td>

                    <td>{{ $deck->followers }}</td>
                    @role('admin-'.$id.'|Owner')

                        <td>
                            <form action="{{ route('eliminar-user') }}" method="post">
                                @csrf
                                <input type="text" name="user-id" value="{{ $deck->twitter }}" style="display:none">
                                <input type="text" name="deck-name" value="{{ $id }}" style="display:none">
                                <input type="text" name="username" value="{{ $deck->username }}" style="display:none">


                                <button type="submit" class="btn btn-danger btn-circle"><i
                                        class="fa fa-meh"></i></button>
                            </form>
                        </td>
                    @endrole



                </tr>
            @endforeach

        </tbody>
    </table>
    <center>
        <a href="{{ route('historial',['id'=>$id]) }}"> Ver historial
            del Deck</a>
    </center>
</div>
@endsection