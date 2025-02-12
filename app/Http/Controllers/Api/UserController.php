<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\Action;
use App\Models\ActionType;



class UserController extends Controller
{
    /**
     * Devuelve todos los usuarios en formato JSON.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    /**
     * Muestra el perfil de usuario autenticado.
     */
    public function show()
    {
        
        $user = Auth::user()->load('stats.stat');
    
        // Obtener el tipo de acción "Mover"
        $actionType = ActionType::where('name', 'Mover')->first();
    
        // Buscar la última acción de tipo "Mover" del usuario autenticado
        $zone_id = null;
        $zone = null;
    
        if ($actionType) {
            $zone_id = Action::where('user_id', $user->_id)
                ->where('action_type_id', $actionType->_id)
                ->latest()
                ->value('actionable_id'); // Obtener solo el ID de la zona
    
            // Si hay una zona, obtener los datos
            if ($zone_id) {
                $zone = Zone::find($zone_id);
            }
        }
    
        return response()->json([
            'user' => $user,
            'current_zone' => $zone,
        ], 200);
    }

    /**
     * Muestra el ranking de usuarios ordenado por nivel y experiencia.
     */
    public function ranking()
    {
        $users = User::orderByDesc('level')->orderByDesc('experience')->get();
        return response()->json(['ranking' => $users], 200);
    }

    /**
     * Devuelve los puntos sin asignar del usuario autenticado.
     */
    public function points()
    {
        $user = Auth::user();
        return response()->json(['unasigned_points' => $user->unasigned_points], 200);
    }

    /**
     * Permite asignar puntos a las estadísticas del usuario autenticado.
     */
    public function addStats(Request $request)
    {
        $user = Auth::user();

        $stats = $request->input('stats');
        $totalAssigned = array_sum($request->input('stats', []));

        foreach ($stats as $id => $value) {
            $valueStat = UserStat::where('user_id', $user->_id)->where('stat_id', $id)->first();
            if ($valueStat) {
                $new_value = $valueStat->value + $value;
                $valueStat->update(['value' => $new_value]);
            }
        }

        $user->unasigned_points -= $totalAssigned;
        $user->save();

        return response()->json(['message' => 'Puntos asignados correctamente', 'user' => $user], 200);
    }

    /**
     * Actualiza el avatar del usuario autenticado.
     */
    public function changeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|integer|min:1|max:6',
        ]);

        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return response()->json(['message' => 'Avatar actualizado correctamente', 'avatar' => $user->avatar], 200);
    }
}
