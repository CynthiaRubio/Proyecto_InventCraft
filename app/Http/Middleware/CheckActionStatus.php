<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\ActionType;
use App\Models\ActionZone;
use App\Models\Invention;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use App\Models\Resource;

use App\Services\ResourceManagementService;


class CheckActionStatus
{
    public function __construct(
        private ResourceManagementService $resource_service,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        /* Buscamos las acciones que ya han terminado */
        $last_action = Action::where('user_id', $user->_id)
                        ->where('finished', false)
                        ->where('time', '<=', now())
                        ->first();

        if ($last_action != null) {

            /* Asignamos los puntos de experiencia */
            if($user->level === 0){
                $user_level = 1;
            } else {
                $user_level = $user->level;
            }
            $time = $last_action->time->diffInSeconds($last_action->created_at);
            $user_experience = $user->experience + (5 * $user_level * ($time / 30));
            $user->update(['experience' => $user_experience]);

            /* Recuperamos el tipo de acción que es */
            $action_type = ActionType::find($last_action->action_type_id);

            switch ($action_type->name) {

                case 'Mover':
                    session()->flash('success', "Has llegado a tu destino");
                    break;

                case 'Construir':
                    $last_action_building = ActionBuilding::where('action_id' , $last_action->_id)
                                    ->update(['available' => true]);
                    
                    session()->flash('success', "Terminaste la construcción de tu edificio");
                    break;

                case 'Crear':
                    $inventory_id = Inventory::where('user_id', $user->_id)->first()->id;
                    
                    $invention = Invention::where('inventory_id', $inventory_id)
                            ->where('available', false)
                            ->update(['available' => true]);

                    session()->flash('data_invention', $invention);
                    break;

                case 'Recolectar':
                    $results = $this->resource_service->updateResources($last_action);
                    /* TODO Falta sacar por pantalla la información sobre el evento ocurrido */
                    session()->flash('data_resource', $results);
                    break;
            }

            /* Se actualiza el estado de la acción terminada */
            $last_action->update([
                'finished' => true, 
                'notification' => true , 
                'updated' => true,
            ]);
        }


        /* Determinamos si hay alguna acción que no haya terminado */
        $action = Action::where('user_id', $user->_id)
            ->where('finished', false)
            ->where('time', '>', now())
            ->first();

        /* Si existe acción en marcha */
        if ($action) {
            $time_left = $action->time->timestamp - now()->timestamp;
            /* Se limita hacer otra acción */
            if ($request->is('moveZone') || $request->is('buildings/create/*') || $request->is('inventions/create/*') || $request->is('farm')) {
                return redirect()->back()->with('error', '⚠️ No puedes hacer otra acción hasta que termine la actual.');
            }
            /* Se envia el tiempo restante para terminar a la vista */
            view()->share([
                'time_left' => $time_left,
            ]);

        }

        return $next($request);
    }
}
