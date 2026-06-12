<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Service\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    private function getWorkspaceId(Request $request): int
    {
        return (int) $request->attributes->get('workspace_id');
    }

    public function index(Request $request, string $projectId): JsonResponse
    {
        $tasks = $this->taskService->getTasksByProject($this->getWorkspaceId($request), (int) $projectId);

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function store(StoreTaskRequest $request, string $projectId): JsonResponse
    {
        $task = $this->taskService->createTask(
            $this->getWorkspaceId($request),
            (int) $projectId,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    public function show(Request $request, string $projectId, string $id): JsonResponse
    {
        $task = $this->taskService->getTaskById(
            $this->getWorkspaceId($request),
            (int) $projectId,
            (int) $id
        );

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    public function update(UpdateTaskRequest $request, string $projectId, string $id): JsonResponse
    {
        $task = $this->taskService->updateTask(
            $this->getWorkspaceId($request),
            (int) $projectId,
            (int) $id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    public function destroy(Request $request, string $projectId, string $id): JsonResponse
    {
        $this->taskService->deleteTask(
            $this->getWorkspaceId($request),
            (int) $projectId,
            (int) $id
        );

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

}
