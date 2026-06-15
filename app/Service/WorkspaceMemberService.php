<?php

namespace App\Service;

use App\Mail\WorkspaceInviteMail;
use App\Models\Workspace;
use App\Models\User;
use App\Models\WorkspaceInvitation;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $email = $validatedData['email'];
        $role = $validatedData['role'];

        $userExistsInWorkspace = $workspace->users()->where('email', $email)->exists();
        if ($userExistsInWorkspace) {
            throw new Exception('Pengguna dengan email ini sudah menjadi anggota aktif di Workspace Anda.');
        }

        $token = Str::random(64);

        $invitation = WorkspaceInvitation::updateOrCreate(
            ['workspace_id' => $workspaceId, 'email' => $email],
            ['role' => $role, 'token' => $token]
        );

        Mail::to($email)->send(new WorkspaceInviteMail($workspace, $role, $token));

        return $invitation;
    }

    public function acceptInvitation(string $token, \App\Models\User $user)
    {
        $invitation = WorkspaceInvitation::where('token', $token)->first();

        if (!$invitation) {
            throw new Exception('Tautan undangan tidak valid atau sudah kedaluwarsa.');
        }

        if (strtolower($invitation->email) !== strtolower($user->email)) {
            throw new Exception('Email akun Anda tidak cocok dengan email tujuan undangan ini.');
        }

        $workspace = Workspace::findOrFail($invitation->workspace_id);

        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            $workspace->users()->attach($user->id, ['role' => $invitation->role]);
        }
        $invitation->delete();

        return $workspace;
    }
}
