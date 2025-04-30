<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;

class CommandeController extends Controller
{
    // Get all commandes
    public function index()
    {
        return Commande::with('ligneCommandes')->get();
    }

    // Get a single commande
    public function show($id)
    {
        $commande = Commande::with('ligneCommandes')->find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        return response()->json($commande);
    }

    // Create a new commande
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_commande' => 'required|date',
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $commande = Commande::create($validatedData);

        return response()->json($commande, 201);
    }

    // Update an existing commande
    public function update(Request $request, $id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_commande' => 'required|date',
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $commande->update($validatedData);

        return response()->json($commande);
    }

    // Delete a commande
    public function destroy($id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        $commande->delete();

        return response()->json(['message' => 'Commande deleted successfully']);
    }
    public function getLigneCommandesByCommandeId($id)
{
    // Find the commande by ID
    $commande = Commande::with('ligneCommandes.article')->find($id);

    if (!$commande) {
        return response()->json(['error' => 'Commande not found'], 404);
    }

    // Return the ligne_commandes linked to the commande
    return response()->json($commande->ligneCommandes);
}

}
