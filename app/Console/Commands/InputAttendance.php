<?php

// app/Console/Commands/InputAttendance.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class InputAttendance extends Command
{
    protected $signature = 'attendance:input';
    protected $description = 'Automatic input attendance for employees who did not fill attendance';

    public function handle()
    {
        // Mendapatkan semua pengguna dengan peran "employee"
        $employees = User::where('role', 'employee')->get();

        // Mendapatkan tanggal dan waktu saat ini
        $now = Carbon::now();

        foreach ($employees as $employee) {
            // Periksa apakah pengguna telah mengisi kehadiran untuk hari ini
            $attendance = Attendance::where('user_id', $employee->id)
                                    ->whereDate('date', $now->toDateString())
                                    ->first();

            // Jika pengguna belum mengisi kehadiran, buat catatan kehadiran otomatis
            if (!$attendance) {
                Attendance::create([
                    'user_id' => $employee->id,
                    'name' => $employee->name,
                    'date' => $now, // Tambahkan tanggal dan waktu saat ini
                    'status' => 'bolos',
                    'reasons' => "Saya bolos pada tanggal " . $now->toDateString()
                ]);
            }
        }

        $this->info('Automatic attendance input process completed.');
    }
}
