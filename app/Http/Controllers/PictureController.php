<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Picture;
use Illuminate\Http\Request;
use App\Http\Validation\SearchValidation;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\PictureValidation;

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

    // Permet de check si l'article a été like par le user
    public function checkLike(Picture $picture)
    {
        // On check si le user est connecté
        if (auth()->user()) {

            // On récupère le like si le user et le picture match
            $like = Like::where('user_id', auth()->user()->id)->where('picture_id', $picture->id)->first();

            // Si le like existe on return true, sinon on return false
            return $like ? response()->json(true, 200) : response()->json(false, 200);

        }
    }

    public function handleLike(Picture $picture)
    {
        $like = Like::where('user_id', auth()->user()->id)->where('picture_id', $picture->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['success' => 'Article unliké !']);
        }

        Like::create([
            'picture_id' => $picture->id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json(['success' => 'Article liké !']);
    }

    public function likedArticles()
    {
        $pictures = Picture::whereRelation('usersLiked', 'user_id', auth()->user()->id)->get();

        // $pictures = Picture::whereHas('usersLiked', function ($query) {
        //     $query->where('user_id', 6);
        // })->get();

        return response()->json($pictures);
    }

    public function searchLikedArticles(Request $request, SearchValidation $validation)
    {
        $data = Validator::make($request->all(), $validation->rules(), $validation->messages());

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 401);
        }

        $pictures = Picture::where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%')
                                ->whereRelation('usersLiked', 'user_id', auth()->user()->id)
                                ->get();

        return response()->json($pictures);
    }

    public function usersLikedArticles(Picture $picture)
    {
        $users = User::whereRelation('liked', 'picture_id', $picture->id)->get();

        return response()->json($users);
    }
}
