<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Zone;

use App\Services\UserManagementService;
use App\Services\ActionManagementService;
use App\Services\ZoneManagementService;

class UserController extends Controller
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private ZoneManagementService $zone_service,
    ) {}

    /**
     * Función para ordenar los puntos de los jugadores y llamar a la vista correspondiente
     */
    public function ranking()
    {
        $users = User::OrderByDesc('level')->orderByDesc('experience')->get();
        
        return view('users.ranking', compact('users'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user()->load('stats.stat',); 

        $zone_id = $this->action_service->getLastActionableByType('Mover');
        $zone = $this->zone_service->getZone($zone_id);
        
        return view('users.show' , compact(['user' , 'zone' ]));
    }

    /**
     * Función para llamar a la vista para asignar los puntos del jugador
     */
    public function points(string $id)
    {
        $user = auth()->user();

        if($user->_id === $id){
            return view('users.points', compact('user'));
        } else {
            return view('login');
        }
    }

    /**
     * Función para guardar los puntos asignados por el jugador
     */
    public function addStats(Request $request)
    {
        $user_id = $request->user_id;
        $user = $this->user_service->getUser($user_id);

        $stats = $request->input('stats');
        $totalAssigned = array_sum($request->input('stats', []));

        //Declaramos las reglas de validación
        foreach ($stats as $stat => $id) {
            $rules["stats.$stat"] = "required|array|exists:stats,id";
        }

        foreach($stats as $id => $value){
            $value_increment = $value;
            $valueStat = UserStat::where('user_id', $user_id)->where('stat_id', $id)->first();
            $new_value = $valueStat->value + $value;
            $valueStat->update(['value' => $new_value]);
        }

        $user->unasigned_points -= $totalAssigned;
        $user->save();

        return redirect()->route('users.show')
                         ->with('success', "$user->name has asignado todos los puntos satisfactoriamente.");
    }

    public function showAvatarSelection(User $user)
    {
        return view('users.avatar_selection', compact('user'));
    }

    public function changeAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|integer|min:1|max:6', // Validamos que sea un número del 1 al 6
        ]);

        $user->avatar = $request->avatar;
        $user->save();

        return redirect()->route('users.show', $user->_id)->with('success', 'Avatar actualizado con éxito.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
