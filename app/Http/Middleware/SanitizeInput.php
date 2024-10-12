<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        
        array_walk_recursive($input, function (&$input) {
            if (is_string($input)) {
                
                $input = strip_tags($input);
                
                $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                  
            }
        });
        
        $request->merge($input);
        
        return $next($request);
    }
}