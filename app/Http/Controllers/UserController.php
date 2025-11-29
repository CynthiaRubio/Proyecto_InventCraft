<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\UserServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Http\Requests\UpdateUserStatsRequest;
use App\Http\Requests\ChangeAvatarRequest;
use App\Models\User;
use App\ViewModels\UserShowViewModel;

class UserController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     */
    public function __construct(
        private UserServiceInterface $userService,
        private ActionServiceInterface $actionService,
        private ZoneServiceInterface $zoneService,
    ) {
    }

    /**
     * Muestra el ranking de usuarios ordenado por nivel y experiencia.
     * 
     * @return \Illuminate\View\View Vista con el ranking de usuarios
     */
    public function ranking()
    {
        $users = $this->userService->getRanking();
        
        return view('users.ranking', compact('users'));
    }

    /**
     * Muestra una lista de todos los usuarios.
     * 
     * @return \Illuminate\View\View Vista con la lista de usuarios
     */
    public function index()
    {
        return view('users.index', ['users' => $this->userService->getAllUsers()]);
    }

    /**
     * Muestra el perfil del usuario autenticado.
     * 
     * @return \Illuminate\View\View Vista con el perfil del usuario
     */
    public function show()
    {
        $user = auth()->user()->load('userStats.stat'); 

        $zone_id = $this->actionService->getLastActionableByType('Mover');
        $zone = $this->zoneService->getZone($zone_id);

        $viewModel = new UserShowViewModel(
            user: $user,
            zone: $zone,
        );
        
        return view('users.show', compact('viewModel', 'user', 'zone'));
    }

    /**
     * Muestra la vista para asignar puntos de estadísticas al usuario.
     * 
     * @param string $id ID del usuario
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de asignación de puntos o redirección si no tiene permisos
     */
    public function points(string $id)
    {
        $user = auth()->user();

        // Verificar que el usuario solo pueda acceder a sus propios puntos
        if ((string) $user->id !== $id) {
            return redirect()->route('users.show')
                ->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Cargar las estadísticas del usuario con la relación stat
        $user->load('userStats.stat');

        return view('users.points', compact('user'));
    }

    /**
     * Guarda los puntos de estadísticas asignados por el usuario.
     * 
     * @param UpdateUserStatsRequest $request Solicitud validada con los puntos asignados
     * @return \Illuminate\Http\RedirectResponse Redirección al perfil con mensaje de éxito o error
     */
    public function addStats(UpdateUserStatsRequest $request)
    {
        $user = auth()->user();
        $userId = (int) $request->user_id;

        // Validar que el usuario solo pueda asignar puntos a su propio perfil
        if ($userId !== $user->id) {
            return redirect()->route('users.show')
                ->with('error', 'No tienes permiso para asignar puntos a otro usuario.');
        }

        $user = $this->userService->getUserById($userId);

        // Actualizar las stats del usuario
        $this->userService->updateUserStats($userId, $request->input('stats'));

        return redirect()->route('users.show')
                         ->with('success', "$user->name has asignado todos los puntos satisfactoriamente.");
    }

    /**
     * Muestra la vista de selección de avatar para el usuario.
     * 
     * @param User $user Usuario para el que se selecciona el avatar
     * @return \Illuminate\View\View Vista de selección de avatar
     */
    public function showAvatarSelection(User $user)
    {
        return view('users.avatar_selection', compact('user'));
    }

    /**
     * Actualiza el avatar del usuario.
     * 
     * @param ChangeAvatarRequest $request Solicitud validada con el avatar seleccionado
     * @param User $user Usuario cuyo avatar se actualiza
     * @return \Illuminate\Http\RedirectResponse Redirección al perfil con mensaje de éxito
     */
    public function changeAvatar(ChangeAvatarRequest $request, User $user)
    {
        $user->avatar = $request->avatar;
        $user->save();

        return redirect()->route('users.show')->with('success', 'Avatar actualizado con éxito.');
    }


}
