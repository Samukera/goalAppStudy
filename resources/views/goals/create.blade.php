@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Nova Meta</h1>

    <form method="POST" action="{{ route('goals.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Título</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Descrição</label>
            <textarea name="description" class="w-full border p-2 rounded"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Frequência</label>
            <select name="frequency" class="w-full border p-2 rounded" required>
                <option value="daily">Diária</option>
                <option value="weekly">Semanal</option>
                <option value="monthly">Mensal</option>
            </select>
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
    </form>
</div>
@endsection
