<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        $recetas = Receta::where('user_id', $perfil->user_id)->paginate(5);
        return view('perfiles.show', compact('perfil', 'recetas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        //Ejecutar policy
        $this->authorize('view', $perfil);

        return view('perfiles.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {

        $this->authorize('update', $perfil);

        //validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required',
        ]);

        if ($request['imagen']) {
            //Si el usuario sube una imagen
            $ruta_imagen = $request['imagen']->store('upload-perfiles', 'public'); //almacenara la imagen dentro de la carpeta public en los archivos del proyecto, posterior a este cogido ejecutar en consola php artisan 'storage:link' para que se puede manifestar la imagen en las vistas del proyecto

            //Resize de la image
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(600, 600);
            $img->save();

            //Crear arreglo imagen
            $array_imagen = ['imagen' => $ruta_imagen];
        }

        //Asignar nombre y url en la tabla users
        auth()->user()->name = $data['nombre'];
        auth()->user()->url = $data['url'];
        auth()->user()->save();

        //Eliminar url y name que pertenecen a la tabla users de $data para poder hacer la actualizacion en la tabla perfils
        unset($data['url']);
        unset($data['nombre']);

        //Adignar biografia e imagen
        auth()->user()->perfil()->update(array_merge(
            $array_imagen ?? [],
            $data
        ));

        //Guardar informacion
        //Redireccionar
        return redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
