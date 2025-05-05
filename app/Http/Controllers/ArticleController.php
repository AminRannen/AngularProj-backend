<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::with('scategorie')->get(); // Inclut la sous catégorie liée;
            return response()->json($articles, 200);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $article = new Article([
                "designation" => $request->input('designation'),
                "marque" => $request->input('marque'),
                "reference" => $request->input('reference'),
                "qtestock" => $request->input('qtestock'),
                "prix" => $request->input('prix'),
                "imageart" => $request->input('imageart'),
                "scategorieID" => $request->input('scategorieID'),
            ]);
            $article->save();
            return response()->json($article);
        } catch (\Exception $e) {
            return response()->json("insertion impossible {$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $article = Article::findOrFail($id);
            return response()->json($article);
        } catch (\Exception $e) {
            return response()->json("probleme de récupération des données {$e->getMessage()}");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $article = Article::findorFail($id);
            $article->update($request->all());
            return response()->json($article);
        } catch (\Exception $e) {
            return response()->json("probleme de modification {$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->delete();
            return response()->json("catégorie supprimée avec succes");
        } catch (\Exception $e) {
            return response()->json("probleme de suppression de catégorie {$e->getMessage()}");
        }
    }
    public function showArticlesBySCAT($idscat)
    {
        try {
            $articles = Article::where('scategorieID', $idscat)->with('scategorie')->get();
            return response()->json($articles);
        } catch (\Exception $e) {
            return response()->json("Selection impossible {$e->getMessage()}");
        }
    }
    public function articlesPaginate()
    {
        try {
            // Set the perPage dynamically or fallback to 2 as the default
            $perPage = request()->input('pageSize', 10);

            // Paginate the articles
            $articles = Article::with('scategorie')->paginate($perPage);

            // Return the paginated results
            return response()->json([
                'products' => $articles->items(),    // Paginated items
                'totalPages' => $articles->lastPage(), // Total pages
                'currentPage' => $articles->currentPage(), // Current page
            ]);
        } catch (\Exception $e) {
            return response()->json("Selection impossible {$e->getMessage()}");
        }
    }
  /**
 * Get articles with full category hierarchy
 */
public function articlesWithCategories()
{
    try {
        $articles = Article::with(['scategorie.categorie'])->get();
        
        $result = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'designation' => $article->designation,
                'subcategory_id' => $article->scategorieID,
                'subcategory_name' => $article->scategorie->nomscategorie ?? null,
                'category_id' => $article->scategorie->categorieID ?? null,
                'category_name' => $article->scategorie->categorie->nomcategorie ?? null
            ];
        });
        
        return response()->json($result, 200);
    } catch (\Exception $e) {
        return response()->json("Error retrieving articles with categories: {$e->getMessage()}", 500);
    }
}
public function articleCountsByCategory()
{
    $counts = Article::with('scategorie.categorie')
        ->get()
        ->groupBy('scategorie.categorie.nomcategorie')
        ->map->count();
    
    return response()->json($counts);
}
}
