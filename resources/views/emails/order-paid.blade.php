<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra — HaloSound</title>
    <style>
        body {
            font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            height: 48px;
            margin-bottom: 20px;
        }
        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            color: #94a3b8;
            font-size: 14px;
            margin: 8px 0 0 0;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 12px;
            color: #0f172a;
        }
        .intro-text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 30px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .card-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 6px 0;
            font-size: 14px;
        }
        .info-label {
            color: #64748b;
            font-weight: 500;
            width: 120px;
        }
        .info-value {
            color: #0f172a;
            font-weight: 600;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-th {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            text-align: left;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }
        .table-td {
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }
        .item-name {
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .item-meta {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0 0;
        }
        .price-text {
            font-weight: 600;
            color: #0f172a;
        }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-row {
            text-align: right;
            font-size: 14px;
            color: #475569;
        }
        .summary-label {
            padding: 6px 12px 6px 0;
        }
        .summary-value {
            padding: 6px 0;
            font-weight: 600;
            color: #0f172a;
            width: 100px;
        }
        .total-row {
            font-size: 16px;
            font-weight: 800;
            color: #6366f1;
        }
        .total-row .summary-label {
            color: #0f172a;
        }
        .total-row .summary-value {
            color: #6366f1;
            font-size: 20px;
            font-weight: 900;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }
        .footer-logo {
            height: 24px;
            margin-bottom: 12px;
            opacity: 0.6;
        }
        .footer-text {
            margin: 0 0 10px 0;
        }
        .color-badge {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
            border: 1px solid #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera -->
        <div class="header">
            <!-- Incrusta el logotipo de la empresa -->
            @if(file_exists(public_path('images/image 3@2x.png')))
                <img src="{{ $message->embed(public_path('images/image 3@2x.png')) }}" alt="HaloSound Logo" class="logo">
            @endif
            <h1 class="header-title">HaloSound</h1>
            <p class="header-subtitle">¡Tu pago ha sido confirmado!</p>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <p class="greeting">Hola, {{ $order->user->name }}:</p>
            <p class="intro-text">
                Queremos confirmarte que hemos recibido el pago de tu pedido correctamente. Estamos preparando los detalles para enviártelo lo antes posible. A continuación, tienes el resumen de tu compra.
            </p>

            <!-- Datos Generales -->
            <div class="card">
                <h3 class="card-title">Detalle del Pedido</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-cell info-label">Pedido #:</div>
                        <div class="info-cell info-value">{{ $order->idOrder }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell info-label">Fecha:</div>
                        <div class="info-cell info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell info-label">Estado:</div>
                        <div class="info-cell info-value" style="color: #059669;">Pagado</div>
                    </div>
                    @if($order->shippingCompany)
                        <div class="info-row">
                            <div class="info-cell info-label">Envío por:</div>
                            <div class="info-cell info-value">{{ strtoupper($order->shippingCompany) }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabla de Productos -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="table-th" style="width: 60%;">Producto</th>
                        <th class="table-th" style="width: 15%; text-align: center;">Cant.</th>
                        <th class="table-th" style="width: 25%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        @php
                            $hex = $item->color?->hex ?? '#cccccc';
                            $colorName = $item->color?->name ?? 'N/A';
                        @endphp
                        <tr>
                            <td class="table-td">
                                <p class="item-name">{{ $item->earphone->name }}</p>
                                <p class="item-meta">
                                    <span class="color-badge" style="background-color: {{ $hex }};"></span>
                                    Color: {{ $colorName }}
                                </p>
                            </td>
                            <td class="table-td" style="text-align: center; font-weight: 600; color: #475569;">
                                {{ $item->quantity }}
                            </td>
                            <td class="table-td price-text" style="text-align: right;">
                                ${{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Desglose de totales -->
            <table class="summary-table">
                <tr class="summary-row">
                    <td class="summary-label">Subtotal:</td>
                    <td class="summary-value">${{ number_format($order->totalAmount - $order->shippingCost, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td class="summary-label">Envío:</td>
                    <td class="summary-value">${{ number_format($order->shippingCost, 2) }}</td>
                </tr>
                <tr class="summary-row total-row">
                    <td class="summary-label">Total Pagado:</td>
                    <td class="summary-value">${{ number_format($order->totalAmount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Pie de página del correo -->
        <div class="footer">
            @if(file_exists(public_path('images/image 3@2x.png')))
                <img src="{{ $message->embed(public_path('images/image 3@2x.png')) }}" alt="HaloSound Logo" class="footer-logo">
            @endif
            <p class="footer-text">© {{ date('Y') }} HaloSound Inc. Todos los derechos reservados.</p>
            <p class="footer-text" style="font-size: 11px;">Este es un correo automático. Por favor, no respondas directamente a este mensaje.</p>
        </div>
    </div>
</body>
</html>
