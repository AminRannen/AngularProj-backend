<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    // Les champs assignables en masse
    protected $fillable = [
        'commande_id',
        'article_id',
        'quantity',
        'price',
    ];

    // Relation avec le modèle Commande (chaque ligne de commande appartient à une commande)
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relation avec le modèle Article (chaque ligne de commande correspond à un article)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    protected static function boot()
{
    parent::boot();

    // Recalculate total when a ligne_commande is created
    static::created(function ($ligneCommande) {
        $ligneCommande->commande->updateTotal();
    });

    // Recalculate total when a ligne_commande is updated
    static::updated(function ($ligneCommande) {
        $ligneCommande->commande->updateTotal();
    });

    // Recalculate total when a ligne_commande is deleted
    static::deleted(function ($ligneCommande) {
        $ligneCommande->commande->updateTotal();
    });
}

}
