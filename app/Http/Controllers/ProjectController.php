<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Service\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class   ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService){
        $this->projectService = $projectService;
    }

    private function getWorkspaceId(Request $request): int
    {
        return (int) $request->attributes->get('workspace_id');
    }

    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->getAllProjects($this->getWorkspaceId($request));

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject(
            $this->getWorkspaceId($request),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $project = $this->projectService->getProjectById($this->getWorkspaceId($request), (int) $id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $project = $this->projectService->updateProject(
            $this->getWorkspaceId($request),
            (int) $id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $this->projectService->deleteProject($this->getWorkspaceId($request), (int) $id);

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}
