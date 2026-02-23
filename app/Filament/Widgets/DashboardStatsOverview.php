<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Question;
use App\Models\Module;
use App\Models\TryoutAnswer;

class DashboardStatsOverview extends BaseWidget
{
    // Setting up polling for real-time vibe
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeSubscribers = User::whereNotNull('subscription_expires_at')
                                 ->where('subscription_expires_at', '>', now())
                                 ->count();
        $inactiveSubscribers = $totalUsers - $activeSubscribers;

        $pendingPayments = Transaction::where('status', 'pending')->count();
        $failedPayments = Transaction::where('status', 'failed')->count();
        $successPayments = Transaction::where('status', 'success')->count();

        $totalQuestions = Question::count();
        $totalModules = Module::count();

        // Calculate correct vs wrong answers
        $totalAnswers = TryoutAnswer::count();
        $correctAnswers = TryoutAnswer::join('questions', 'tryout_answers.question_id', '=', 'questions.id')
            ->whereNotNull('tryout_answers.selected_answer')
            ->whereColumn('tryout_answers.selected_answer', 'questions.correct_answer')
            ->count();
        $wrongAnswers = $totalAnswers - $correctAnswers;

        return [
            Stat::make('Total Pengguna', $totalUsers)
                ->description('Total akun terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Pengguna Aktif', $activeSubscribers)
                ->description('Premium aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Pengguna Tidak Aktif', $inactiveSubscribers)
                ->description('Gratis / Expired')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
                
            Stat::make('Pembayaran Berhasil', $successPayments)
                ->description('Transaksi sukses (' . $successPayments . ')')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Status Belum Dibayar', $pendingPayments)
                ->description('Transaksi menunggu (' . $pendingPayments . ')')
                ->color('warning'),
            Stat::make('Pembayaran Gagal', $failedPayments)
                ->description('Dibatalkan / Expired (' . $failedPayments . ')')
                ->color('danger'),

            Stat::make('Total Bank Soal', $totalQuestions)
                ->description('Total butir soal')
                ->color('info'),
            Stat::make('Total Modul', $totalModules)
                ->description('Modul materi')
                ->color('info'),
            Stat::make('Akurasi Peserta', $correctAnswers . ' Benar')
                ->description($wrongAnswers . ' Salah dari (' . $totalAnswers . ' Total Jawaban)')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color($correctAnswers > $wrongAnswers ? 'success' : 'warning'),
        ];
    }
}
