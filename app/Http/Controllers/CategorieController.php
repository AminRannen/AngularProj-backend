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
     */public function update(Request $request, $id)
{
    try {
        $categorie = Categorie::findOrFail($id);
        $updated = $categorie->update($request->all());
        
        return response()->json([
            'success' => $updated,
            'data' => $categorie,
            'message' => $updated ? 'Category updated successfully' : 'Failed to update category'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating category',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            
            // Check if category has subcategories
            if ($categorie->scategories()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing subcategories'
                ], 422);
            }
            
            $categorie->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
