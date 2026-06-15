<?php

namespace App\Service;

use App\Models\Workspace;
use App\Models\User;
use Exception;

class WorkspaceMemberService
{
    /**
     * Ambil daftar anggota dari sebuah workspace
     */
    public function getMembers(int $workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);

        return $workspace->users;
    }

    /**
     * Tambahkan anggota baru ke workspace
     */
    public function addMember(int $workspaceId, array $validatedData)
    {
        $workspace = Workspace::findOrFail($workspaceId);
        $user = User::where('email', $validatedData['email'])->first();

        if ($workspace->users()->where('user_id', $user->id)->exists()) {
            throw new Exception('Pengguna ini sudah menjadi anggota di Workspace Anda.');
        }

        $workspace->users()->attach($user->id, ['role' => $validatedData['role']]);

        return $user;
    }
}
