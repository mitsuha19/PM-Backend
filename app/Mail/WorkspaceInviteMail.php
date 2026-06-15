<?php

namespace App\Mail;

use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkspaceInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $workspace;
    public $role;
    public $token;
    public $inviteUrl;

    public function __construct(Workspace $workspace, string $role, string $token)
    {
        $this->workspace = $workspace;
        $this->role = $role;
        $this->token = $token;
        $this->inviteUrl = "http://localhost:3000/invitations/" . $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Anda diundang untuk bergabung ke Workspace: ' . $this->workspace->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.workspace.invite',
        );
    }
}
