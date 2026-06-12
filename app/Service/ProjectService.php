<?php

namespace App\Service;

use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectService {
    public function getAllProjects(int $workspaceId): Collection
    {
        return Project::where('workspace_id', $workspaceId);
    }
    public function createProject(int $workspaceId, array $data): Project
    {
        return Project::create([
            'workspace_id' => $workspaceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }
    public function getProjecyById(int $ProjectId, int $workspaceId): Project
    {
        return Project::where('workspace_id', $workspaceId)->findOrFail($ProjectId);
    }

    public function updateProject(int $workspaceId, int $projectId, array $data): Project
    {
        $project = $this->getProjecyById($projectId, $workspaceId);
        $project->update($data);
        return $project;
    }

    public function deleteProject(int $workspaceId, int $projectId): void
    {
        $project = $this->getProjecyById($projectId, $workspaceId);
        $project->delete();
    }
}
