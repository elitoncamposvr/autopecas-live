<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Agendamentos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #1e40af;
            color: #fff;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px;
            text-align: left;
        }
        td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .status {
            text-transform: capitalize;
            font-weight: bold;
        }
        .status.pendente {
            color: #b45309;
        }
        .status.concluido {
            color: #065f46;
        }
        .status.cancelado {
            color: #991b1b;
        }
        footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
<h1>Relatório de Agendamentos</h1>
<h2>Gerado em {{ now()->format('d/m/Y H:i') }}</h2>

<table>
    <thead>
    <tr>
        <th>Cliente</th>
        <th>Serviço</th>
        <th>Celular</th>
        <th>Mecânico</th>
        <th>Data</th>
        <th>Hora</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($appointments as $a)
        <tr>
            <td>{{ $a->client }}</td>
            <td>{{ $a->service }}</td>
            <td>{{ $a->cellphone }}</td>
            <td>{{ $a->mechanic ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
            <td>{{ $a->time }}</td>
            <td class="status {{ $a->status }}">{{ ucfirst($a->status) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align:center; padding:15px; color:#777;">
                Nenhum agendamento encontrado.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<footer>
    Sistema de Agendamentos — {{ config('app.name') }}
</footer>
</body>
</html>
