<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ScategorieController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\UserController;

Route::get('/test', function () {
    return response()->json(['message' => 'API route is working!']);
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');

Route::get('/users', [UserController::class, 'index']);         // Get all users
Route::get('/users/{id}', [UserController::class, 'show']);     // Get one user
Route::post('/users', [UserController::class, 'store']);        // Create user
Route::put('/users/{id}', [UserController::class, 'update']);   // Update user
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user
Route::get('/users/{id}', [UserController::class, 'getUserById']);




Route::middleware('api')->group(function () {
    Route::resource('categories', CategorieController::class);
});
/*Route::get("/scategories",[ScategorieController::class,'index']);*/
Route::middleware('api')->group(function () {
    Route::resource('scategories', ScategorieController::class);
});
Route::get('/scat/{idcat}', [ScategorieController::class, 'showSCategorieByCAT']);
Route::middleware('api')->group(function () {
    Route::resource('articles', ArticleController::class);
});
Route::get('/listarticles/{idscat}', [ArticleController::class, 'showArticlesBySCAT']);
Route::get('/articles/art/articlespaginate', [ArticleController::class, 'articlesPaginate']);
Route::get('/articleswithcat', [ArticleController::class, 'articlesWithCategories']);
Route::get('/article-counts-by-category', [ArticleController::class, 'articleCountsByCategory']);
// Routes for commandes
Route::apiResource('commandes', CommandeController::class);
Route::patch('commandes/{id}/status', [CommandeController::class, 'updateStatus']);

// Routes for ligneCommandes
Route::apiResource('ligne-commandes', LigneCommandeController::class);


Route::get('/commandes/{id}/ligne-commandes', [CommandeController::class, 'getLigneCommandesByCommandeId']);
