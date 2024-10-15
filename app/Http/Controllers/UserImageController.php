<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    public function serveImage($userId, $filename)
    {
        $user = Auth::user();
        if (!$user || ($user->id != $userId && !$user->isAdmin())) {
            abort(403);
        }

        $path = storage_path('app/private/user_profiles/' . $filename);

        if (!Storage::disk('private')->exists('user_profiles/' . $filename)) {
            abort(404);
        }

        return response()->file($path);
    }
}