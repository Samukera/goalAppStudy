<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $goals = auth()->user()->goals()->with('progress')->get();

        $agora = Carbon::today();
        $ativas = $goals->count();
        $concluidas = 0;

        foreach ($goals as $goal) {
            $ultima = $goal->progress->sortByDesc('completed_at')->first();

            if (!$ultima) continue;

            $data = Carbon::parse($ultima->completed_at);
            $frequencia = $goal->frequency;

            if (
                ($frequencia === 'daily' && $data->isSameDay($agora)) ||
                ($frequencia === 'weekly' && $data->isSameWeek($agora)) ||
                ($frequencia === 'monthly' && $data->isSameMonth($agora))
            ) {
                $concluidas++;
            }
        }

        $pendentes = $ativas - $concluidas;

        return view('dashboard.index', compact('ativas', 'concluidas', 'pendentes'));
    }
}
