<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    // Les champs assignables en masse
    protected $fillable = [
        'user_id',
        'date_commande',
        'total',
        'status',
    ];

    // Relation avec le modèle User (chaque commande appartient à un utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec LigneCommande (une commande peut avoir plusieurs lignes de commande)
    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
    public function updateTotal()
{
    // Sum of quantity * price for all associated ligne_commandes
    $total = $this->ligneCommandes->sum(function ($ligneCommande) {
        return $ligneCommande->quantity * $ligneCommande->price;
    });

    // Update the total in the database
    $this->update(['total' => $total]);
}

}
