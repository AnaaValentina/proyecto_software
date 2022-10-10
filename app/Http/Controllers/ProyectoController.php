<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Desarrollador;
use Illuminate\Http\Request;
use Gate;

class ProyectoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request)
        {
            $query = $request->buscar;
            $proyectos = Proyecto::where('nombre','like', '%'. $query . '%')
                                    ->orderBy('nombre','asc')
                                    ->paginate(5);
            return view('proyectos.index', compact('proyectos','query'));
        }
        // Obtener todos los registros 
        $proyectos = Proyecto::orderBy('nombre','asc')->paginate(6);

        // enviar a la vista
        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Solo lo puede hacer el admin del sistema 
        if(Gate::denies('administrador'))
        {
            // abort(403);
            return redirect()->route('proyectos.index');
        }
        return view('proyectos.insert');
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo $request;
        $nombre = $request->nombre;
        $duracion = $request->duracion;

        Proyecto::create($request->all());

        return redirect()->route('proyectos.index')->with('exito', '¡El registro se ha creado satisfactoriamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $desarrolladores = Desarrollador::where('proyecto_id',$id)
                                            ->orderBy('nombre','asc')
                                            ->get();
        // dd($desarrolladores);
        return view('proyectos.show', compact('proyecto','desarrolladores'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('administrador'))
        {
            // abort(403);
            return redirect()->route('proyectos.index');
        }
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.edit', compact('proyecto'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        //Metodo 1
        // $proyecto->nombre = $request->nombre;
        // $proyecto->duracion = $request->duracion;
        // $proyecto->save();

        //metodo 2
        $proyecto->update($request->all());
        return redirect()->route('proyectos.index')->with('exito', '¡El registro se ha actualizado satisfactoriamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies('administrador'))
        {
            // abort(403);
            return redirect()->route('proyectos.index');
        }
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();
        return redirect()->route('proyectos.index');
    }
}
