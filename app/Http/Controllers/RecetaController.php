<?php

namespace App\Http\Controllers;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'search']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $usuario = auth()->user()->id;
        $recetas = Receta::where('user_id', $usuario)->paginate(3);
        return view('recetas.index', compact('recetas'));
        */
        $usuario = auth()->user()->id;
        return view('recetas.index', [
            'recetas' => Receta::where('user_id', $usuario)->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //obtener datos de la base de datos sin modelo
        //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre', 'id');

        //obtener datos de la base de datos con modelo
        $categorias = CategoriaReceta::all(['id', 'nombre']);
        return view('recetas.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image',
        ]);

        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public'); //almacenara la imagen dentro de la carpeta public en los archivos del proyecto, posterior a este cogido ejecutar en consola php artisan 'storage:link' para que se puede manifestar la imagen en las vistas del proyecto

        //Resize de la image
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(900, 400);
        $img->save();

        /*
        DB::table('recetas')->insert([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'user_id' => Auth::user()->id,
            'categoria_id' => $data['categoria'],
        ]);
        */

        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria'],
        ]);

        return redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        //Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = (auth()->user()) ? auth()->user()->meGusta->contains($receta->id) : false;

        //Obtener la cantidad de likes de un receta
        $likes = $receta->likes->count();

        return view('recetas.show', compact('receta', 'like', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        //restringir que usuarios no autenticados o no creadores originales de ciertas recetas editen las mismas
        $this->authorize('view', $receta);

        $categorias = CategoriaReceta::all();
        return view('recetas.edit', compact('categorias', 'receta'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        $this->authorize('update', $receta);

        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
        ]);

        $receta->titulo = $data['titulo'];
        $receta->categoria_id = $data['categoria'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];

        if (request('imagen')) {
            //obtener la ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

            //Resize de la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 500);
            $img->save();

            //Asignar al objeto
            $receta->imagen = $ruta_imagen;
        }

        $receta->save();

        return redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Policy
        $this->authorize('delete', $receta);

        $receta->delete();

        return redirect()->action([RecetaController::class, 'index']);
    }

    public function search(Request $request)
    {
        $busqueda = $request['buscar'];

        $recetas = Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(3);

        //no perder la referencia de la consulta del usuario en la URL
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show', compact('recetas', 'busqueda'));
    }
}
