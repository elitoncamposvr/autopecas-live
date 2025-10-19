<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Pedidos (Excel)</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            padding: 6px;
            text-align: left;
            border: 1px solid #ccc;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
            color: #555;
        }

        .status-pendente { color: #ca8a04; font-weight: bold; }
        .status-andamento { color: #2563eb; font-weight: bold; }
        .status-concluido { color: #16a34a; font-weight: bold; }
        .status-cancelado { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
<h1>Relatório de Pedidos</h1>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Cliente</th>
        <th>OS</th>
        <th>Status</th>
        <th>Descrição</th>
        <th>Observações</th>
        <th>Preço</th>
        <th>Entrega Prevista</th>
        <th>Criado em</th>
    </tr>
    </thead>
    <tbody>
    @forelse($orders as $index => $order)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order->client }}</td>
            <td>{{ $order->os_reference }}</td>
            <td class="status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</td>
            <td>{{ $order->description ?? '-' }}</td>
            <td>{{ $order->notes ?? '-' }}</td>
            <td>
                @if($order->price)
                    R$ {{ number_format($order->price, 2, ',', '.') }}
                @else
                    -
                @endif
            </td>
            <td>{{ $order->expected_delivery ? \Carbon\Carbon::parse($order->expected_delivery)->format('d/m/Y') : '-' }}</td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="text-align:center; color:#666; padding:20px;">
                Nenhum pedido encontrado.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Gerado em {{ now()->format('d/m/Y H:i') }} — Sistema de Gestão Autocenter</p>
</div>
</body>
</html>
