<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use Illuminate\Support\Facades\Mail;

class SendLaporanReminders extends Command
{
    protected $signature = 'laporan:send-reminders';
    protected $description = 'Mengirimkan pengingat laporan ke member berdasarkan tanggal pengingat.';

    public function handle()
    {
        $today = now()->toDateString();
        $laporans = Laporan::where('reminder_date', $today)->with('user')->get();

        foreach ($laporans as $laporan) {
            Mail::raw(
                "Halo {$laporan->user->name}, ini adalah pengingat untuk membuat laporan dengan judul: {$laporan->judul}.",
                function ($message) use ($laporan) {
                    $message->to($laporan->user->email)
                            ->subject('Pengingat Pembuatan Laporan');
                }
            );
        }

        $this->info('Pengingat laporan telah dikirim.');
    }
}
