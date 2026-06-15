<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkspaceMemberRequest;
use App\Models\User;
use App\Models\Workspace;
use App\Service\WorkspaceMemberService;
use Illuminate\Http\Request;

class WorkSpaceMemberController extends Controller
{
    protected WorkspaceMemberService $memberService;

    public function __construct(WorkspaceMemberService $memberService) {
        $this->memberService = $memberService;
    }

    public function index(Request $request) {
        $workspaceId = $request->header('X-Workspace-ID');
        $members = $this->memberService->getMembers($workspaceId);

        return response()->json([
            'success' => true,
            'data'    => $members
        ]);
    }

    public function store(StoreWorkspaceMemberRequest $request)
    {
        $workspaceId = $request->header('X-Workspace-ID');

        try {
            $user = $this->memberService->addMember($workspaceId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Anggota tim berhasil ditambahkan!',
                'data'    => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function acceptInvitation(Request $request, string $token)
    {
        try {
            $workspace = $this->memberService->acceptInvitation($token, $request->user());

            return response()->json([
                'success' => true,
                'message' => 'Berhasil bergabung dengan Workspace!',
                'data' => $workspace
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}


