<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ScategorieController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\LigneCommandeController;


Route::get('/test', function () {
    return response()->json(['message' => 'API route is working!']);
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');





Route::middleware('api')->group(function () {
    Route::resource('categories', CategorieController::class);
    });
/*Route::get("/scategories",[ScategorieController::class,'index']);*/
Route::middleware('api')->group(function () {
    Route::resource('scategories', ScategorieController::class);
    });
    Route::get('/scat/{idcat}', [ScategorieController::class,'showSCategorieByCAT']);
Route::middleware('api')->group(function () {
        Route::resource('articles', ArticleController::class);
        });
Route::get('/listarticles/{idscat}', [ArticleController::class,'showArticlesBySCAT']);
Route::get('/articles/art/articlespaginate', [ArticleController::class, 'articlesPaginate']);


// Routes for commandes
Route::apiResource('commandes', CommandeController::class);

// Routes for ligneCommandes
Route::apiResource('ligne-commandes', LigneCommandeController::class);


Route::get('/commandes/{id}/ligne-commandes', [CommandeController::class, 'getLigneCommandesByCommandeId']);
