<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PointNotification extends Notification
{
    use Queueable;

    /**
     * @param int    $pointChange   Nilai poin yang berubah (bisa negatif)
     * @param int    $totalPoints   Total poin user setelah perubahan
     * @param string $reason        Alasan perubahan poin
     */
    public function __construct(
        public readonly int    $pointChange,
        public readonly int    $totalPoints,
        public readonly string $reason,
    ) {}

    public function via(object $notifiable): array
    {
        // Simpan ke database supaya bisa ditampilkan di notif bell
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $sign  = $this->pointChange >= 0 ? '+' : '';
        $emoji = match (true) {
            $this->pointChange > 0  => '🎉',
            $this->pointChange === 0 => 'ℹ️',
            default                 => '⚠️',
        };

        return [
            'title'        => "{$emoji} Update Poin Kehadiran",
            'body'         => "{$sign}{$this->pointChange} poin — {$this->reason}",
            'point_change' => $this->pointChange,
            'total_points' => $this->totalPoints,
            'reason'       => $this->reason,
        ];
    }
}