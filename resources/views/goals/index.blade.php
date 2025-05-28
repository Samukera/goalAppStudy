@extends('layouts.app')
@php use Carbon\Carbon; @endphp

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Minhas Metas</h1>

    @php
        $dataMesAtual = Carbon::parse($mesSelecionado . '-01');
        $mesAnterior = $dataMesAtual->copy()->subMonth()->format('Y-m');
        $mesSeguinte = $dataMesAtual->copy()->addMonth()->format('Y-m');
    @endphp
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('goals.index', ['mes' => $mesAnterior]) }}" class="text-blue-600 hover:underline">
            ← {{ Carbon::parse($mesAnterior . '-01')->translatedFormat('F Y') }}
        </a>

        <span class="font-semibold text-gray-700">
            {{ $dataMesAtual->translatedFormat('F Y') }}
        </span>

        <a href="{{ route('goals.index', ['mes' => $mesSeguinte]) }}" class="text-blue-600 hover:underline">
            {{ Carbon::parse($mesSeguinte . '-01')->translatedFormat('F Y') }} →
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('goals.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
        + Nova Meta
    </a>

    @foreach($goals as $goal)
        <div class="border p-4 mb-3 rounded shadow">
            <h2 class="text-xl font-semibold">{{ $goal->title }}</h2>
            <p class="text-sm text-gray-500">Total concluída: {{ $goal->progress->count() }}x</p>
            <p class="text-sm text-gray-600 mb-2">{{ $goal->description }}</p>
            <p class="text-sm text-gray-500">Frequência: {{ ucfirst($goal->frequency) }}</p>

            @php
                $hoje = Carbon::today();
                $ultimaConclusao = $goal->progress->sortByDesc('completed_at')->first();
                $marcado = false;

                if ($ultimaConclusao) {
                    $data = Carbon::parse($ultimaConclusao->completed_at);
                    switch ($goal->frequency) {
                        case 'daily':
                            $marcado = $data->isSameDay($hoje);
                            break;
                        case 'weekly':
                            $marcado = $data->isSameWeek($hoje);
                            break;
                        case 'monthly':
                            $marcado = $data->isSameMonth($hoje);
                            break;
                    }
                }
            @endphp

            @if (!$marcado)
                <form method="POST" action="{{ route('goals.complete', $goal) }}" class="mt-2">
                    @csrf
                    <button class="bg-green-500 text-white px-3 py-1 rounded">Marcar como feito</button>
                </form>
            @else
                <p class="text-green-600 mt-2">
                    ✅ Meta concluída
                    {{ $goal->frequency === 'daily' ? 'hoje' : ($goal->frequency === 'weekly' ? 'esta semana' : 'este mês') }}
                </p>
            @endif

            <div class="mt-4">
                <h3 class="font-semibold text-sm text-gray-700">Histórico:</h3>
                <ul class="list-disc pl-5 text-sm text-gray-600">
                    @forelse($goal->progress->sortByDesc('completed_at') as $progress)
                        <li>{{ Carbon::parse($progress->completed_at)->format('d/m/Y') }}</li>
                    @empty
                        <li>Sem registros ainda.</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-sm text-gray-700">Calendário do mês:</h3>

                @php
                    $inicioMes = $dataMesAtual->copy()->startOfMonth();
                    $fimMes = $dataMesAtual->copy()->endOfMonth();
                    $diasNoMes = $inicioMes->diffInDays($fimMes) + 1;
                    $datasConcluidas = $goal->progress->pluck('completed_at')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();
                @endphp

                <div class="grid grid-cols-7 gap-2 mt-2 text-center text-sm">
                    @foreach(range(0, $diasNoMes - 1) as $i)
                        @php
                            $data = $inicioMes->copy()->addDays($i);
                            $estaConcluido = in_array($data->format('Y-m-d'), $datasConcluidas);
                        @endphp
                        <div class="p-2 border rounded {{ $estaConcluido ? 'bg-green-200 text-green-800' : 'bg-red-100 text-red-500' }}">
                            {{ $data->format('d') }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div> <!-- Fim do card da meta -->
    @endforeach
</div>
@endsection
