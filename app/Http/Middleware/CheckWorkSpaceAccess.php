<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkSpaceAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspaceId = $request->header('X-Workspace-Id');

        if(!$workspaceId) {
            return response()->json([
                'success' => false,
                'message' => 'X-Workspace-Id is missing'
            ], 400);
        }

        $user =$request->user();

        if(!$user || !$user->workspaces()->where('workspace_id', $workspaceId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You dont have access to this workspace'
            ], 403);
        }

        $request->attributes->set('workspaceId', $workspaceId);

        return $next($request);
    }
}
