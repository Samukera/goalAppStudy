<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

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
        $goal->progress()->firstOrCreate([
            'completed_at' => now()->toDateString(),
            'goal_id' => $goal->id,
        ]);

        return back()->with('success', 'Meta marcada como concluída!');
    }
}
