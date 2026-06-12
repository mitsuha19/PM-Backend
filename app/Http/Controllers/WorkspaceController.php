<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWorkspaceRequest;
use App\Service\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    protected WorkSpaceService $workspaceService;

    public function __construct(WorkspaceService $workspaceService){
        $this->workspaceService = $workspaceService;
    }

    public function index(Request $request): JsonResponse
    {
        $workspace = $request->user()->workspaces;

        return response()->json([
           'success' => true,
           'data' => $workspace,
        ]);
    }
    public function store(UpdateWorkspaceRequest $request): JsonResponse
    {
        $workspace =$this->workspaceService->createWorkspace(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Workspace created successfully',
            'data'       => $workspace
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $workspace =$request->user()->workspace()->findOrfail($id);

        return response()->json([
            'success' => true,
            'data' => $workspace,
        ]);
    }

    public function update(UpdateWorkspaceRequest $request, string $id): JsonResponse
    {
        $workspace = $request->user()->workspaces()->wherePivot('role', 'owner')->findOrFail($id);

        $updatedWorkspace = $this->workspaceService->updateWorkspace($workspace, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Workspace updated successfully',
            'data' => $updatedWorkspace
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $workspace = $request->user()->workspaces()->wherePivot('role', 'owner')->findOrFail($id);

        $this->workspaceService->deleteWorkspace($workspace);

        return response()->json([
            'success' => true,
            'message' => 'Workspace deleted successfully'
        ]);
    }
}
