<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function show(Request $request)
    {

        $user = $request->user();

        return response()->json([
            'user' => $user
        ]);
    }

    public function getFavorites(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page  = $request->query('page', 1);

        if (Storage::exists('favorites.json')) {
            $favorites = json_decode(Storage::get('favorites.json'), true);
        } else {
            $favorites = [];
        }

        // Total de favoritos
        $totalDocs = count($favorites);

        // Paginar os resultados
        $offset  = ($page - 1) * $limit;
        $results = array_slice($favorites, $offset, $limit);

        return response()->json([
            'results'    => $results,
            'totalDocs'  => $totalDocs,
            'page'       => $page,
            'totalPages' => ceil($totalDocs / $limit),
            'hasNext'    => $page * $limit < $totalDocs,
            'hasPrev'    => $page > 1,
        ]);
    }

    public function history(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page  = $request->query('page', 1);

        if (Storage::exists('access_history.log')) {
            $history = array_map('json_decode', file(storage_path('app/access_history.log')));
        } else {
            $history = [];
        }

        // Ordenar do mais recente para o mais antigo
        usort($history, function ($a, $b) {
            return strtotime($b->accessed_at) - strtotime($a->accessed_at);
        });


        $totalDocs = count($history);
        $offset    = ($page - 1) * $limit;
        $results   = array_slice($history, $offset, $limit);

        return response()->json([
            'results'    => $results,
            'totalDocs'  => $totalDocs,
            'page'       => $page,
            'totalPages' => ceil($totalDocs / $limit),
            'hasNext'    => $page * $limit < $totalDocs,
            'hasPrev'    => $page > 1,
        ]);
    }
}
