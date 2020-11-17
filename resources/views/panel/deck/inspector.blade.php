@extends('panel/index')
@section('titulocontenido')
    Deck: {{$id}}
@endsection

@section('subtitulo')
Analizando el tweet con ID: {{$h}}
@endsection

@section('contenido')


<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>

                <th scope="col">Twitter</th>
                <th scope="col">Código</th>
                <th scope="col">Significado</th>
                
                
            </tr>
        </thead>
        <tbody>
        @foreach($o as $err)
            <tr>

                <td>{{$err->twitter}}</td>
                <td>{{$err->codigo}}</td>
                <td>{{$err->mensaje}}</td>
                




            </tr>
            @endforeach
            
        </tbody>
    </table>
    <center>
    <a href="{{route('historial',['id'=>$id])}}">Volver al historial</a>
    </center>
</div>
@endsection
