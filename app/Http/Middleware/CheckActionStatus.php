<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Action;

class CheckActionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = auth()->id();

        /* Actualizamos las acciones que ya han terminado */
        Action::where('user_id', $user_id)
            ->where('finished', false)
            ->where('time', '<=', now())
            ->update(['finished' => true, 'notification' => true]);

        /* Determinamos si hay alguna acción que no haya terminado */
        $action = Action::where('user_id', $user_id)
            ->where('finished', false)
            ->where('time', '>', now())
            ->first();

        /* Si existe acción en marcha sacamos en la vista el tiempo restante para terminar */
        if ($action) {
            $time_left = $action->time->timestamp - now()->timestamp;
            view()->share([
                'time_left' => $time_left,
                'hasAction' => true, 
            ]);
        }

        return $next($request);
    }
}
