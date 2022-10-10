@extends('layouts.main')

@section('titulo', 'Detalle de proyecto')

@section('content')
<div class="my-3">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                </tr>
            </thead>
            <tbody>
                <td>{{ $proyecto->nombre}}</td>
                <td>{{ $proyecto->duracion}} meses</td>
                
            </tbody>
        </table>
        @can(['administrador'])
        <h4 class="my-3">Desarrolladores</h4>
        <ul class="list-group list-group-flush mb-3">
            @foreach($desarrolladores as $item)
                <li class="list-group-item">{{$item->nombre}} {{$item->apellido}}</li>
            @endforeach
        </ul>
        @endcan
        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">Volver</a>
    </div>

@endsection