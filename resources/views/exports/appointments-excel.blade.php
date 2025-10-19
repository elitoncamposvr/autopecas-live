<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Agendamentos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #1E3A8A; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1E3A8A; color: white; font-size: 12px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 15px; text-align: right; font-size: 11px; color: #555; }
    </style>
</head>
<body>
<h2>Relatório de Agendamentos</h2>

<table>
    <thead style="background-color:#1E3A8A;color:#fff;">
    <tr>
        <th>Cliente</th>
        <th>Serviço</th>
        <th>Mecânico</th>
        <th>Celular</th>
        <th>Data</th>
        <th>Hora</th>
        <th>Status</th>
        <th>Observações</th>
    </tr>
    </thead>
    <tbody>
    @foreach($appointments as $a)
        <tr>
            <td>{{ $a->client }}</td>
            <td>{{ $a->service }}</td>
            <td>{{ $a->mechanic ?? '-' }}</td>
            <td>{{ $a->cellphone }}</td>
            <td>{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
            <td>{{ $a->time }}</td>
            <td>{{ ucfirst($a->status) }}</td>
            <td>{{ $a->notes ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p style="font-size:11px;text-align:right;">Gerado em {{ now()->format('d/m/Y H:i') }}</p>


<div class="footer">
    Gerado em {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>
