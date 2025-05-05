<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;

class CommandeController extends Controller
{
    // Get all commandes with related data
    public function index()
    {
        return Commande::with(['ligneCommandes', 'user:id,name'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($commande) {
                return [
                    'id' => $commande->id,
                    'user_id' => $commande->user_id,
                    'client_name' => $commande->user->name,
                    'date_commande' => $commande->date_commande,
                    'total' => $commande->total,
                    'status' => $commande->status,
                    'created_at' => $commande->created_at,
                    'updated_at' => $commande->updated_at,
                    'ligne_commandes' => $commande->ligneCommandes,
                    'status_formatted' => strtolower($commande->status),
                ];
            });
    }

    // Get a single commande with full details
    public function show($id)
    {
        $commande = Commande::with(['ligneCommandes.article', 'user:id,name'])->find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        return response()->json([
            'id' => $commande->id,
            'user_id' => $commande->user_id,
            'client_name' => $commande->user->name,
            'date_commande' => $commande->date_commande,
            'total' => $commande->total,
            'status' => $commande->status,
            'created_at' => $commande->created_at,
            'updated_at' => $commande->updated_at,
            'ligne_commandes' => $commande->ligneCommandes,
            'status_formatted' => strtolower($commande->status),
        ]);
    }

    // Create a new commande
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_commande' => 'required|date',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:Pending,Processing,Completed,Cancelled',
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
            'user_id' => 'sometimes|exists:users,id',
            'date_commande' => 'sometimes|date',
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:Pending,Processing,Completed,Cancelled',
        ]);

        $commande->update($validatedData);

        return response()->json($commande);
    }

    // Update only the status of a commande
    public function updateStatus(Request $request, $id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Completed,Cancelled',
        ]);

        $commande->update(['status' => $validatedData['status']]);

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

    // Get ligne_commandes for a specific commande
    public function getLigneCommandesByCommandeId($id)
    {
        $commande = Commande::with('ligneCommandes.article')->find($id);

        if (!$commande) {
            return response()->json(['error' => 'Commande not found'], 404);
        }

        return response()->json($commande->ligneCommandes);
    }
}