<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Exception;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Categorie::all(); // Missing semicolon
            return response()->json($categories);
        } catch (\Exception $e) { // Missing closing parenthesis
            return response()->json(["message" => "categories non dispo"]); // Use an array for the response message
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $categorie = new Categorie([
                'nomcategorie' => $request->input('nomcategorie'),
                'imagecategorie' => $request->input('imagecategorie')
            ]);
            $categorie->save();
            return response()->json($categorie);
        } catch (\Exception) {
            return response()->json('Catégorie créée !');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            return response()->json($categorie);
        } catch (\Exception $e) {
            return response()->json(["message" => "problème de récupération"]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->update($request->all());
        } catch (\Exception $e) {
            return response()->json("probleme de modification{$e->getMessage()},{$e->getCode()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();
            return response()->json('Catégorie supprimée!');
        } catch (\Exception $e) {
            return response()->json(["message" => "problème de delete"]);
        }
    }
}
