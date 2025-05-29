<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Dompdf\Options;
use Dompdf\Dompdf;

class GoalController extends Controller
{
    // Lista todas as metas do usuário autenticado
    public function index(Request $request)
    {
        $goals = auth()->user()->goals()->with('progress')->get();

        // Determina o mês a ser exibido
        $mesSelecionado = $request->query('mes', now()->format('Y-m'));

        return view('goals.index', compact('goals', 'mesSelecionado'));
    }

    // Exibe o formulário de criação
    public function create()
    {
        return view('goals.create');
    }

    // Salva nova meta
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
        ]);

        auth()->user()->goals()->create($request->only(['title', 'description', 'frequency']));

        return redirect()->route('goals.index')->with('success', 'Meta criada com sucesso!');
    }

    // Marca a meta como concluída no dia atual
    public function markComplete(Goal $goal)
    {
        $alreadyComplet = $goal->progress()
            ->where('completed_at', '<=', now())
            ->exists();

        if (!$alreadyComplet) {
            $goal->progress()->create([
                'completed_at' => now(),
            ]);
        }

        return back()->with('success', 'Meta marcada como concluída!');
    }

    public function exportPdf(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $goals = Goal::with('progress')
            ->where('user_id', auth()->id())
            ->whereHas('progress', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('completed_at', [$startDate, $endDate]);
            })
            ->get();

        $html = view('goals.export_pdf', compact('goals', 'startDate', 'endDate'))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return Response::make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="metas_exportadas.pdf"'
        ]);
    }

    public function exportCsv(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $goals = Goal::with('progress')
            ->where('user_id', auth()->id())
            ->whereHas('progress', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('completed_at', [$startDate, $endDate]);
            })
            ->get();

        $csvContent = "Meta,Descrição,Frequência,Concluída Em\n";

        foreach ($goals as $goal) {
            foreach ($goal->progress as $progress) {
                if ($progress->completed_at >= $startDate && $progress->completed_at <= $endDate) {
                    $csvContent .= "{$goal->title},{$goal->description},{$goal->frequency},{$progress->completed_at}\n";
                }
            }
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="metas_exportadas.csv"');
    }
}
