<?php

namespace App\Service;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Str;

class WorkspaceService {

    public function createWorkspace(User $user, array $data): Workspace
    {
        $workspace = Workspace::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'] . '-' . uniqid()),
        ]);

        $user->workspaces()->attach($workspace->id, ['role' => 'owner']);

        return $workspace;
    }

    public function updateWorkspace(Workspace $workspace, array $data): Workspace
    {
        $workspace->update([
            'name' => $data['name'],
        ]);

        return $workspace;
    }

    public function deleteWorkspace(Workspace $workspace): void
    {
        $workspace->delete();
    }

}
