@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
@endsection

@section('botones')
    <a href="{{ route('recetas.index') }}" class="btn btn-primary mr-2 text-white">
      Volver</a>
@endsection

@section('content')
    <h1 class="text-center">Editar mi Perfil</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 bg-white p-3">
            <form action="{{ route('perfiles.update',['perfil'=>$perfil->id]) }}" method="post" novalidate enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="">Nombre</label>
                    <input type="text" name="nombre"
                        class="form-control @error('nombre')
                        is-invalid
                    @enderror"
                        id="nombre" placeholder="Tu nombre" value="{{ $perfil->usuario->name }}">

                    @error('nombre')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url">Sitio Web</label>
                    <input type="text" name="url"
                        class="form-control @error('url')
                        is-invalid
                    @enderror"
                        id="url" placeholder="" value="{{ $perfil->usuario->url }}">

                    @error('url')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="biografia">Biografia</label>
                    <input id="biografia" type="hidden" name="biografia" value="{{ $perfil->biografia }}">
                    <trix-editor class="form-control @error('biografia') is-invalid @enderror" input="biografia">
                    </trix-editor>
                </div>

                @error('biografia')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="form-group mt-3">
                    <label for="imagen">Elige tu imagen de perfil</label>
                    <input type="file" name="imagen" id="imagen" class="form-control">
                </div>

                @if ($perfil->imagen)
                    <div class="mt-4">
                        <p>Imagen actual:</p>
                        <img src="/storage/{{ $perfil->imagen }}" style="width: 300px" alt="">
                    </div>
                @endif

                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-primary text-white" value="Guardar cambios">
                </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js" defer></script>
@endsection
