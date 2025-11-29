<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Zone;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getUser(): ?User;
    public function getUserStat(string $name): int;
    public function getUserActualZone(): ?Zone;
    public function getUserInventory(): ?Inventory;
    public function getUserInventoryWithRelations(): ?Inventory;
    public function getUserById(int $userId): User;
    public function createUser(array $userData): User;
    public function updateUser(int $userId, array $userData): bool;
    public function deleteUser(int $userId): bool;
    public function registerUser(array $userData, Zone $initialZone): User;
    public function updateUserStats(int $userId, array $stats): int;
    public function getRanking(): Collection;
    public function getAllUsers(): Collection;
    public function addExperience(int $userId, int $experience): int;
    public function checkAndUpdateLevel(int $userId): bool;
}

