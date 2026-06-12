<?php

namespace App\Service;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService {
    private function validateProjectWorkspace(int $workspaceId, int $projectId): Project
    {
        return Project::where('workspace_id', $workspaceId)->findOrFail($projectId);
    }

    public function getTasksByProject(int $workspaceId, int $projectId): Collection
    {
        $project = $this->validateProjectWorkspace($workspaceId, $projectId);

        return $project->tasks()->with('assignee')->get();
    }

    public function createTask(int $workspaceId, int $projectId, array $data): Task
    {
        $project = $this->validateProjectWorkspace($workspaceId, $projectId);

        return $project->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'todo',
            'due_date' => $data['due_date'] ?? null,
            'assignee_id' => $data['assignee_id'] ?? null,
        ]);
    }
    public function getTaskById(int $workspaceId, int $projectId, int $taskId): Task
    {
        $this->validateProjectWorkspace($workspaceId, $projectId);

        return Task::where('project_id', $projectId)->with('assignee')->findOrFail($taskId);
    }

    public function updateTask(int $workspaceId, int $projectId, int $taskId, array $data): Task
    {
        $task = $this->getTaskById($workspaceId, $projectId, $taskId);

        $task->update($data);

        return $task;
    }

    public function deleteTask(int $workspaceId, int $projectId, int $taskId): void
    {
        $task = $this->getTaskById($workspaceId, $projectId, $taskId);

        $task->delete();
    }



}
