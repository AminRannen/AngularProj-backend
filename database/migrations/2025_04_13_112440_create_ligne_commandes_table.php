<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id'); // Foreign key linking to commandes
            $table->unsignedBigInteger('article_id'); // Foreign key linking to articles
            $table->integer('quantity'); // Quantity of the article
            $table->decimal('price', 10, 2); // Price of the article
            $table->timestamps();
    
            // Foreign key constraints
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('ligne_commandes');
    }
    

};
