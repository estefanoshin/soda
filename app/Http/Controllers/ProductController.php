<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product_list = DB::table('productos')->select('*')->get();
        return view('/welcome', ['productos' => $product_list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/formProduct');
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

        $articulo = $request->input('articulo');
        $descripcion = $request->input('descripcion');
        $tipo_tela = $request->input('tipo_tela');
        $talles = $request->input('talles');
        $colores = $request->input('colores');
        $precio = $request->input('precio');

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $filename = md5($articulo.$tipo_tela.$image->getClientOriginalName());
            Storage::disk('local')->put($filename, File::get($image));
        }

        $data = array( 'articulo'=>$articulo, 'descripcion'=>$descripcion, 'tipo_tela'=>$tipo_tela, 'talles'=>$talles, 'colores'=>$colores, 'img'=>$filename, 'precio'=>$precio);

        DB::table('productos')->insert($data);

        return \Redirect::to('/');
    }

    public function retrieve_file($filename)
    {
        $image = Storage::disk('local')->get($filename);
        return new Response($image, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        //
        $data =  DB::table('productos')->select('*')->where([[ 'id_art', '=', $product]])->get();
        return view( 'updateProduct', ['datos' => $data] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $articulo = $request->input('articulo');
        $descripcion = $request->input('descripcion');
        $tipo_tela = $request->input('tipo_tela');
        $talles = $request->input('talles');
        $colores = $request->input('colores');
        $precio = $request->input('precio');
        $id_art = $request->input('id_art');
        $prev_img = $request->input('prev_img');

        if ($request->hasFile('img')) {
            Storage::delete($prev_img);

            $image = $request->file('img');
            $filename = md5($articulo.$tipo_tela.$image->getClientOriginalName());
            Storage::disk('local')->put($filename, File::get($image));
        } else {
            $filename = $prev_img;
        }

        $data = array( 'articulo'=>$articulo, 'descripcion'=>$descripcion, 'tipo_tela'=>$tipo_tela, 'talles'=>$talles, 'colores'=>$colores, 'img'=>$filename, 'precio'=>$precio);

        DB::table('productos')->where([[ 'id_art', '=', $id_art ]])->update($data);

        return \Redirect::to('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        //
        $producto =  DB::table('productos')->select('*')->where([[ 'id_art', '=', $product]])->first();        
        
        if (Storage::disk('local')->has($producto->img)){
            Storage::delete($producto->img);
        }

        DB::table('productos')->where([[ 'id_art', '=', $product]])->delete();

        return \Redirect::to('/');
    }
}
