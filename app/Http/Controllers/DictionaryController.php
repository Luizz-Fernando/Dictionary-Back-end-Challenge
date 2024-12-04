<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DictionaryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $limit  = $request->query('limit', 10);
        $page   = $request->query('page', 1);

        $response = Http::get("https://api.dictionaryapi.dev/api/v2/entries/en/{$search}");

        if ($response->failed()) {
            return response()->json(['error' => 'Word not found'], 404);
        }

        $words = $response->json();

        $allWords = [];
        foreach ($words as $entry) {
            if (isset($entry['word'])) {
                $allWords[] = $entry['word'];
            }
        }

        $totalDocs = count($allWords);

        // Calcular os resultados da página atual
        $offset = ($page - 1) * $limit;
        $results = array_slice($allWords, $offset, $limit);


        return response()->json([
            'results'    => $results,
            'totalDocs'  => $totalDocs,
            'page'       => $page,
            'totalPages' => ceil($totalDocs / $limit),
            'hasNext'    => $page * $limit < $totalDocs,
            'hasPrev'    => $page > 1,
        ]);
    }

    public function show($word)
    {
        $response = Http::get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");

        if ($response->failed()) {
            return response()->json(['error' => 'Word not found'], 404);
        }

        $history = [
            'word'        => $word,
            'accessed_at' => now()->toDateTimeString()
        ];

        Storage::append('access_history.log', json_encode($history));

        return response()->json($response->json());
    }

    public function favorite($word)
    {
        $favorite = [
            'word' => $word,
            'added' => now()->toISOString()
        ];

        if (Storage::exists('favorites.json')) {
            $favorites = json_decode(Storage::get('favorites.json'), true);
        } else {
            $favorites = [];
        }

        foreach ($favorites as $fav) {
            if ($fav['word'] === $word) {
                return response()->json(['message' => 'Word already in favorites.'], 200);
            }
        }

        $favorites[] = $favorite;

        Storage::put('favorites.json', json_encode($favorites, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Word added to favorites.'], 201);
    }

    public function unfavorite($word)
    {
        if (Storage::exists('favorites.json')) {
            $favorites = json_decode(Storage::get('favorites.json'), true);
        } else {
            return response()->json(['message' => 'No favorites found.'], 404);
        }

        $filteredFavorites = array_filter($favorites, function ($favorite) use ($word) {
            return $favorite['word'] !== $word;
        });

        if (count($favorites) === count($filteredFavorites)) {
            return response()->json(['message' => 'Word not found in favorites.'], 404);
        }

        Storage::put('favorites.json', json_encode(array_values($filteredFavorites), JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Word removed from favorites.'], 200);
    }

}
