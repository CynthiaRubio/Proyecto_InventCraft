<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\Action;
use App\Models\ActionType;

class UserController extends Controller
{
    use HasApiTokens;

    public function getSanctumIdentifier(){
        return $this->getKey();
    }

    public function getSanctumCustomClaims(){
        return [];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200); //return response()->json(['data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user()->load('stats.stat');
        $zone_id = $this->action_service->lastActionableByType('Mover');
        $zone = $this->action_service->getZoneById($zone_id);

        return response()->json([
            'user' => $user,
            'zone' => $zone
        ]);
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

    /**
     * 
     */
    public function ranking()
    {
        $users = User::orderByDesc('level')->orderByDesc('experience')->get();
        return response()->json(['data' => $users]);
    }

    /**
     * 
     */
    public function points(string $id)
    {
        $user = auth()->user()->load(['stats.stat']);

        if ($user->_id === $id) {
            return response()->json(['data' => $user]);
        } else {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    }

}
