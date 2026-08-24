<?php

namespace App\Console\Commands;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Tryout;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScheduleWeeklyTournament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournament:schedule-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autopilot scheduler for national weekly tryout tournament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Weekly Tournament Autopilot Engine...');

        // 1. Finalize finished tournaments: compute final rankings
        $finishedTournaments = Tournament::where('end_at', '<=', now())
            ->where('is_active', true)
            ->get();

        foreach ($finishedTournaments as $t) {
            $participants = TournamentParticipant::where('tournament_id', $t->id)
                ->orderByDesc('score')
                ->orderBy('duration_seconds', 'asc')
                ->get();

            $rank = 1;
            foreach ($participants as $p) {
                $p->update(['rank_position' => $rank++]);
            }

            $t->update(['is_active' => false]);
            $this->info("Finalized tournament #{$t->id} with {$participants->count()} participants.");
        }

        // 2. Check if an ongoing or upcoming tournament exists for this week
        $now = now();
        $thisSaturday = $now->copy()->isWeekend() ? $now->copy()->startOfWeek()->addDays(5)->startOfDay() : $now->copy()->next(Carbon::SATURDAY)->startOfDay();
        $thisSunday = $thisSaturday->copy()->addDay()->endOfDay();

        $existing = Tournament::where('start_at', '<=', $thisSunday)
            ->where('end_at', '>=', $thisSaturday)
            ->first();

        if ($existing) {
            $this->info("Active tournament for this weekend already exists: #{$existing->id} - {$existing->title}");
            return 0;
        }

        // 3. Find an active Tryout for the tournament
        $tryout = Tryout::where('is_active', true)->inRandomOrder()->first();

        if (!$tryout) {
            $this->warn('No active tryouts found in database. Skipping tournament creation.');
            return 0;
        }

        $latestEdition = Tournament::max('edition_number') ?? 0;
        $nextEdition = $latestEdition + 1;
        $weekNumber = now()->weekOfYear;

        $newTournament = Tournament::create([
            'title' => "Liga Tryout Nasional SKD - Edisi Minggu ke-{$weekNumber}",
            'edition_number' => $nextEdition,
            'tryout_id' => $tryout->id,
            'start_at' => $thisSaturday,
            'end_at' => $thisSunday,
            'is_active' => true,
            'prizes_description' => "🥇 Juara 1: Saldo E-Wallet Rp 100.000 + E-Sertifikat\n🥈 Juara 2: Saldo E-Wallet Rp 50.000 + E-Sertifikat\n🥉 Juara 3: Akses VIP 1 Bulan + E-Sertifikat\n🎖 Seluruh Peserta Lolos: E-Sertifikat Prestasi Digital",
        ]);

        $this->info("Created new tournament edition #{$nextEdition}: {$newTournament->title}");

        return 0;
    }
}