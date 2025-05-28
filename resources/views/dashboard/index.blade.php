@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 shadow rounded text-center">
            <p class="text-gray-500 text-sm">Metas Ativas</p>
            <p class="text-3xl font-bold text-blue-600">{{ $ativas }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded text-center">
            <p class="text-gray-500 text-sm">Concluídas (período atual)</p>
            <p class="text-3xl font-bold text-green-600">{{ $concluidas }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded text-center">
            <p class="text-gray-500 text-sm">Pendentes</p>
            <p class="text-3xl font-bold text-red-500">{{ $pendentes }}</p>
        </div>
    </div>
</div>
@endsection
