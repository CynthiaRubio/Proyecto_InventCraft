<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Invention;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use App\Models\ActionZone;
use App\Models\Resource;


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

        /* Determinamos si hay alguna acción que no haya terminado */
        $action = Action::where('user_id', $user_id)
            ->where('finished', false)
            ->where('time', '>', now())
            ->first();

        /* Si existe acción en marcha */
        if ($action) {
            $time_left = $action->time->timestamp - now()->timestamp;
            /* Se limita hacer otra acción */
            if ($request->is('moveZone') || $request->is('actionBuildings/create/*') || $request->is('inventions/create/*') || $request->is('farm')) {
                return redirect()->back()->with('error', '⚠️ No puedes hacer otra acción hasta que termine la actual.');
            }
            /* Se envia el tiempo restante para terminar a la vista */
            view()->share([
                'time_left' => $time_left,
            ]);

            /* Si no existe acción en marcha */
        } else {

            /* Buscamos las acciones que ya han terminado */
            $last_action = Action::where('user_id', $user_id)
                        ->where('finished', false)
                        ->where('notification', false)
                        ->where('updated', false)
                        ->where('time', '<=', now())
                        ->first();

            if ($last_action != null) {
                $action_type = ActionType::where('id', $last_action->action_type_id)->first();
                switch ($action_type->name) {
                    case 'Mover':
                        /* TO DO Revisar porque está mal planteado */
                        ActionZone::create([
                            'action_id' => $last_action->_id,
                            'zone_id' => $last_action->actionable_id,
                        ]);
                        break;

                    case 'Construir':
                        // $action_building_id = ActionBuilding::where('action_id' , $last_action->id)
                        //     ->where('building_id' , $last_action->actionable_id)
                        //     ->first()->id;
                        // Invention::where('action_building_id' , $action_building_id)
                        //     ->delete();
                        break;

                    case 'Crear':
                        $inventory_id = Inventory::where('user_id', $user_id)->first()->id;
                        Invention::where('inventory_id', $inventory_id)
                                ->where('available', false)
                                ->update(['available' => true]);
                        break;

                    case 'Recolectar':
                        $inventory_id = Inventory::where('user_id', $user_id)->first()->id;
                        $resources = Resource::where('action_zone_id', $last_action->actionable_id)
                            ->where('available' , false)
                            ->with(['materials:id,name' ,'inventions.inventions.inventionType:id,name'])
                            ->get();
                            dd($resources);
                        $materials = InventoryMaterial::where('inventory_id', $inventory_id)
                                    ->where('quantity_na', '>', 0)
                                    ->get();
                        foreach ($materials as $material) {
                            $new_quantity = $material->quantity + $material->quantity_na;
                            $material->update(['quantity' => $new_quantity , 'quantity_na' => 0]);
                        }
                        Invention::where('inventory_id', $inventory_id)
                                ->where('available', false)
                                ->update(['available' => true]);
                        
                        session()->flash('success' , "Has recolectado $resources");
                        break;
                }

                /* Se actualiza el estado de la acción a actualizada */
                $last_action->update(['finished' => true, 'notification' => true , 'updated' => true ]);
            }


        }

        return $next($request);
    }
}
