@extends('layouts.app')

@section('botones')
    @include('ui.navegacion')
@endsection

@section('content')
    <h2 class="text-center mb-5">Administra tus recetas</h2>
    <div class="col-md-10 mx-auto bg-white p-3">
        <table class="table text-center">
            <thead class="bg-primary text-light">
                <tr>
                    <th scole="col">Titulo</th>
                    <th scole="col">Categoria</th>
                    <th scole="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recetas as $receta)
                    <tr>
                        <td>{{ $receta->titulo }}</td>
                        <td>{{ $receta->categoria->nombre }}</td>
                        <td>
                            <eliminar-receta receta-id={{ $receta->id }}></eliminar-receta>
                            <a href="{{ route('recetas.show', ['receta' => $receta->id]) }}" {{-- la adicion de ['receta'=>$receta->id] es necesaria para la ruta: Route::get('/recetas/{receta}' --}}
                                class="d-block mb-2 btn btn-dark w-100">Ver</a>
                            <a href="{{ route('recetas.edit', ['receta' => $receta->id]) }}"
                                class="d-block btn btn-success w-100">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="col-12 mt-4 justify-content-center d-flex">
            {{ $recetas }}
        </div>

        <h2 class="text-center my-5">Recetas que te gustan</h2>
        <div class="col-md-10 mx-auto bg-white p-3">
            @if (count(Auth::user()->meGusta) > 0)
                <ul class="list-group">
                    @foreach (Auth::user()->meGusta as $iLike)
                        <li class="list-group-item d-flex justify-content-between align-items-center">{{ $iLike->titulo }}
                            <a class="btn btn-outline-success text-uppercase font-weight-bold"
                                href="{{ route('recetas.show', ['receta' => $iLike->id]) }}">Ver</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center">Aún no tienes recetas guardadas. <small>Dale me gusta ❤ a las recetas y aparecerán
                        aqui</small></p>
            @endif
        </div>
    </div>
@endsection
