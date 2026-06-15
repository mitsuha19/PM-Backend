<x-mail::message>

    # Halo!

    Anda telah diundang untuk bergabung dengan Workspace **{!! $workspace->name !!}** sebagai **{{ ucfirst($role) }}**.

    Silakan klik tombol di bawah ini untuk menerima undangan dan mulai berkolaborasi bersama tim.

    <x-mail::button :url="$inviteUrl" color="primary">
        Terima Undangan
    </x-mail::button>

    Jika Anda tidak merasa mengenal tim ini, Anda dapat mengabaikan email ini dengan aman.

    Terima kasih,
    <br>
    {{ config('app.name') }}

</x-mail::message>
