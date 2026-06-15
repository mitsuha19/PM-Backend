<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkspaceInvitation extends Model
{
    protected $fillable = [
        'workspace_id',
        'email',
        'role',
        'token',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
