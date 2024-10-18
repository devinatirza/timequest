<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserImageController extends Controller
{
    public function serveImage($userId, $filename)
    {
        $user = Auth::user();
        if (!$user || ($user->id != $userId && !$user->isAdmin())) {
            Log::warning('Unauthorized image access attempt', ['userId' => $userId, 'requestedBy' => $user ? $user->id : 'guest']);
            abort(403);
        }
        
        $path = 'user_profiles/' . $filename;
        $fullPath = Storage::disk('private')->path($path);

        if (!Storage::disk('private')->exists($path)) {
            Log::warning('User image file not found', ['path' => $fullPath, 'exists' => file_exists($fullPath)]);
            return $this->serveDefaultImage();
        }
        
        return Storage::disk('private')->response($path, $filename, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function serveDefaultImage()
    {
        $defaultPath = public_path('images/default-profile.jpg');
        if (!file_exists($defaultPath)) {
            Log::error('Default image not found', ['path' => $defaultPath]);
            abort(404);
        }
        return response()->file($defaultPath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}