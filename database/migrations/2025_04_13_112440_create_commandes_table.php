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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key linking to users
            $table->date('date_commande'); // Commande date
            $table->decimal('total', 10, 2); // Total amount
            $table->string('status')->default('Pending'); // Status of the commande (Pending, Completed, etc.)
            $table->timestamps();
    
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('commandes');
    }
    
};
