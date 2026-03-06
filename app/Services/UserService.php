<?php

namespace App\Services;

use App\Jobs\SendResetPasswordJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function listUsers($request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortDir = $request->sort_dir ?? 'desc';

        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->per_page ?? 10;

        return $query->paginate($perPage);
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        SendWelcomeEmailJob::dispatch($user);

        return $user;
    }

    public function updateUser($data, User $user)
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    public function deleteUser(User $user)
    {
        if ($user->id == Auth::id()) {
            throw new \Exception("You cannot delete your own account.");
        }
        $user->delete();
        return true;
    }
}