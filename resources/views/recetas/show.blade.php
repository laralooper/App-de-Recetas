@extends('layouts.app')

@section('content')
    <article class="contenido-receta bg-white p-5 shadow">
        <h1 class="text-center mb-4">{{ $receta->titulo }}</h1>
        <div class="imagen-receta">
            <img src="/storage/{{ $receta->imagen }}" alt="" class="w-100">
        </div>
        <div class="receta-meta mt-3">
            <p>
                <span class="font-weight-bold text-primary">Escrito en:</span>
                <a class="text-dark" href="{{ route('categorias.show', ['categoriaReceta' => $receta->categoria->id]) }}">
                    {{ $receta->categoria->nombre }}
                </a>
            </p>

            <p>
                <span class="font-weight-bold text-primary">Autor:</span>
                <a class="text-dark" href="{{ route('perfiles.show', ['perfil' => $receta->autor->id]) }}">
                    {{ $receta->autor->name }}
                </a>
            </p>

            <p>
                <span class="font-weight-bold text-primary">Creado en:</span>
                @php
                    $fecha = $receta->created_at;
                @endphp
                <fecha-receta fecha="{{ $fecha }}"></fecha-receta>
            </p>

            <div class="ingredientes">
                <h2 class="my-3 text-primary">Ingredientes</h2>
                {!! $receta->ingredientes !!} {{-- el par de llaves con dos signos de interrogacion permiten el tipado sin los nombre de las etiquetas --}}
            </div>

            <div class="preparacion">
                <h2 class="my-3 text-primary">Preparación</h2>
                {!! $receta->preparacion !!}
            </div>

            <div class="justify-content-center row text-center">
                <like-buttom receta-id="{{ $receta->id }}" like="{{ $like }}" likes="{{ $likes }}">
                </like-buttom>
            </div>
        </div>
    </article>
@endsection
