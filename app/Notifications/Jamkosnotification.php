<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JamkosNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $siswa_nama,
        public string $kelas,
        public string $jurusan,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tipe'       => 'jamkos',
            'siswa_nama' => $this->siswa_nama,
            'kelas'      => $this->kelas,
            'jurusan'    => $this->jurusan,
        ];
    }
}