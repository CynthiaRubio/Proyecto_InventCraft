<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;

class CheckExperienceStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        /* Comprobamos la experiencia del jugador */
        $user = auth()->user();

        $experience_by_level = ($user->level + 1) * 100;
        /* Si es superior a 100, restamos 100 puntos, aumentamos en 1 el nivel y le asignamos 15 puntos */
        if($user->experience >= $experience_by_level){
            $new_experience = $user->experience - $experience_by_level;
            $new_level = $user->level + 1;
            $new_unasigned_points = $user->unasigned_points + 15;
            $user->update([
                'experience' => $new_experience , 
                'level' => $new_level,
                'unasigned_points' => $new_unasigned_points,
            ]);
        }

        return $next($request);
    }
}
