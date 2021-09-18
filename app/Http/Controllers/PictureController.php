<?php

namespace App\Http\Controllers;

use App\Http\Validation\PictureValidation;
use App\Http\Validation\SearchValidation;
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

    public function search(Request $request, SearchValidation $validation)
    {
        $data = Validator::make($request->all(), $validation->rules(), $validation->messages());

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 401);
        }

        $pictures = Picture::where('title', 'like', '%' . $request->search . '%')
                            ->orWhere('description', 'like', '%' . $request->search . '%')
                            ->get();

        return response()->json($pictures);
    }

    public function checkLike(Picture $picture)
    {
        // On check si le user est connecté
        if (auth()->user()) {
            // On récupères les users ayant liké l'article
            $likes = $picture->with('usersLiked')->get();

            // On check si le user connecté a deja liké l'article
            foreach ($likes as $like) {
                foreach ($like->usersLiked as $userLiked) {
                    if ($userLiked->id == auth()->user()->id) {
                        return response()->json(true, 200);
                    } else {
                        return response()->json(false, 200);
                    }
                }
            }
        }

        return response()->json('euh');

        
    }
}
