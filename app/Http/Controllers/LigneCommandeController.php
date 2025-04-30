<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LigneCommande;

class LigneCommandeController extends Controller
{
    // Get all ligneCommandes
    public function index()
    {
        return LigneCommande::with('article', 'commande')->get();
    }

    // Get a single ligneCommande
    public function show($id)
    {
        $ligneCommande = LigneCommande::with('article', 'commande')->find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'LigneCommande not found'], 404);
        }

        return response()->json($ligneCommande);
    }

    // Create a new ligneCommande
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $ligneCommande = LigneCommande::create($validatedData);

        return response()->json($ligneCommande, 201);
    }

    // Update an existing ligneCommande
    public function update(Request $request, $id)
    {
        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'LigneCommande not found'], 404);
        }

        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $ligneCommande->update($validatedData);

        return response()->json($ligneCommande);
    }

    // Delete a ligneCommande
    public function destroy($id)
    {
        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'LigneCommande not found'], 404);
        }

        $ligneCommande->delete();

        return response()->json(['message' => 'LigneCommande deleted successfully']);
    }
}
