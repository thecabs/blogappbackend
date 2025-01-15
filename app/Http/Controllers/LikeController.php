<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    // like or unlike
    public function likerOrUnlike($id)
    {
        // Trouver le post
        $post = Post::find($id);

        // Si le post n'existe pas, renvoyer une erreur 404
        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 404);  // Changer le code de statut à 404 ici
        }

        // Vérifier si l'utilisateur a déjà liké ce post
        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        // Si ce n'est pas encore liké, alors on l'aime
        if (!$like) {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);
            return response([
                'message' => 'Liked'
            ], 200);
        }

        // Sinon, on retire le like
        $like->delete();

        return response([
            'message' => 'Disliked'
        ], 200);
    }
}
