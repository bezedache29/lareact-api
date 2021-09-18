<?php

namespace App\Http\Controllers;

use App\Http\Validation\PictureValidation;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = Picture::all();

        return response()->json($pictures);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PictureValidation $validation)
    {
        $data = Validator::make($request->all(), $validation->rules(), $validation->messages());

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 401);
        }

        // On renomme l'image
        $imageName = time() . '-' . rand(10, 100) . '.' . $request->image->extension();
        // On la stcok dans le dossier pictures sur le disk public
        $path = $request->image->storeAs('pictures', $imageName, 'public');

        $picture = Picture::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path
        ]);

        // On récupère le user_id dans le middleware CreateImg, et on l'insere dans Picture via boot() du Model Picture
        // Cela évite d'ajouter user_id en fillable dans Picture

        return response()->json($picture);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Picture $picture)
    {
        if (!$picture) {
            return response()->json(['errors' => 'Photo introuvable']);
        }

        return response()->json($picture);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        //
    }
}
