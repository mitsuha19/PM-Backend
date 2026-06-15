<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckWorkspaceRole
{

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $workspaceId = $request->header('X-Workspace-ID');
        $user = $request->user();

        if (!$workspaceId || !$user) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $workspaceRelation = $user->workspaces()->where('workspace_id', $workspaceId)->first();

        if (!$workspaceRelation) {
            return response()->json(['success' => false, 'message' => 'Anda bukan anggota workspace ini.'], 403);
        }

        $userRole = $workspaceRelation->pivot->role;

        if (!in_array($userRole, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin (role) yang cukup.'
            ], 403);
        }

        $request->attributes->set('workspace_role', $userRole);

        return $next($request);
    }
}
