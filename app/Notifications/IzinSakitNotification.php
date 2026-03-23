<?php
// app/Notifications/IzinSakitNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class IzinSakitNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User   $siswa,
        public string $tipe,    // 'izin' atau 'sakit'
        public string $alasan,
        public ?string $suratUrl = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // ── In-app (database) ──
    public function toDatabase(object $notifiable): array
    {
        return [
            'siswa_id'   => $this->siswa->id,
            'siswa_nama' => $this->siswa->name,
            'kelas'      => $this->siswa->kelas,
            'tipe'       => $this->tipe,
            'alasan'     => $this->alasan,
            'surat_url'  => $this->suratUrl,
        ];
    }

    // ── Email ──
    public function toMail(object $notifiable): MailMessage
    {
        $tipeCap  = ucfirst($this->tipe);
        $mail = (new MailMessage)
            ->subject("Pengajuan {$tipeCap} - {$this->siswa->name} ({$this->siswa->kelas})")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Siswa berikut mengajukan **{$tipeCap}**:")
            ->line("**Nama:** {$this->siswa->name}")
            ->line("**Kelas:** {$this->siswa->kelas}")
            ->line("**Alasan:** {$this->alasan}");

        if ($this->suratUrl) {
            $mail->action('Lihat Surat / Dokumen', $this->suratUrl);
        }

        return $mail->line('Silakan periksa dan setujui/tolak pengajuan ini di dashboard admin.');
    }
}