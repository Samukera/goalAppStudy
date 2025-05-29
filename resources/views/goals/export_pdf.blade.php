<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exportação de Metas</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Metas exportadas entre {{ $startDate->format('d/m/Y') }} e {{ $endDate->format('d/m/Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Frequência</th>
                <th>Data Concluída</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goals as $goal)
                @foreach($goal->progress as $progress)
                    @if($progress->completed_at >= $startDate && $progress->completed_at <= $endDate)
                        <tr>
                            <td>{{ $goal->title }}</td>
                            <td>{{ $goal->description }}</td>
                            <td>{{ $goal->frequency }}</td>
                            <td>{{ \Carbon\Carbon::parse($progress->completed_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
