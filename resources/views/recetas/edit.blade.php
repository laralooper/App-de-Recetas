@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
@endsection

@section('botones')
    <a href="{{ route('recetas.index') }}" class="btn btn-primary mr-2 text-white">
        Volver</a>
@endsection

@section('content')
    <h2 class="text-center mb-5">Editar: {{ $receta->titulo }}</h2>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('recetas.update', ['receta' => $receta->id]) }}" method="post" novalidate
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Titulo Receta</label>
                    <input type="text" name="titulo"
                        class="form-control @error('titulo')
                        is-invalid
                    @enderror"
                        id="titulo" placeholder="Titulo Receta" value="{{ $receta->titulo }}">

                    @error('titulo')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- CATEGORIAS --}}
                <div class="form-group mt-3">
                    <label for="categoria">Categoria</label>
                    <select name="categoria" id="categoria"
                        class="form-control @error('categoria')
                    is-invalid
                @enderror">
                        <option value="">-- Seleccione --</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ $receta->categoria_id == $categoria['id'] ? 'selected' : '' }}>
                                {{ $categoria['nombre'] }}
                            </option>
                        @endforeach
                    </select>

                    @error('categoria')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- PREPARACION --}}
                <div class="form-group mt-3">
                    <label for="preparacion">Preparación</label>
                    <input id="preparacion" type="hidden" name="preparacion" value="{{ $receta->preparacion }}">
                    <trix-editor class="form-control @error('preparacion') is-invalid @enderror" input="preparacion">
                    </trix-editor>
                </div>

                @error('preparacion')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                {{-- INGREDIENTES --}}
                <div class="form-group mt-3">
                    <label for="ingredientes">Ingredientes</label>
                    <input id="ingredientes" type="hidden" name="ingredientes" value="{{ $receta->ingredientes }}">
                    <trix-editor class="form-control @error('ingredientes') is-invalid @enderror" input="ingredientes">
                    </trix-editor>
                </div>

                @error('ingredientes')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="form-group mt-3">
                    <label for="imagen">Elige la imagen</label>
                    <input type="file" name="imagen" id="imagen" class="form-control">
                </div>

                <div class="mt-4">
                    <p>Imagen actual:</p>
                    <img src="/storage/{{ $receta->imagen }}" style="width: 300px" alt="">
                </div>

                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-primary" value="Agregar la Receta">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js" defer></script>
@endsection
