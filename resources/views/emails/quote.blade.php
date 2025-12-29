<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdmin ? 'Nueva Cotización Recibida' : 'Tu Cotización' }} - {{ $quote->quote_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8;">
    <div style="max-width: 600px; margin: 0 auto; padding: 24px;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #0f2744 0%, #1e3a5f 100%); border-radius: 12px 12px 0 0; padding: 24px 32px; text-align: center;">
            <h1 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 600;">
                📋 {{ $isAdmin ? 'Nueva Cotización Recibida' : 'Tu Cotización' }}
            </h1>
            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                {{ $quote->quote_number }}
            </p>
        </div>
        
        <!-- Content -->
        <div style="background: #ffffff; padding: 32px; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
            @if($isAdmin)
                <p style="margin: 0 0 20px; color: #475569; font-size: 14px;">
                    Un cliente ha solicitado una cotización. A continuación los detalles:
                </p>
            @else
                <p style="margin: 0 0 20px; color: #475569; font-size: 14px;">
                    Hola <strong>{{ $quote->customer_name }}</strong>, gracias por tu interés. Aquí está el resumen de tu cotización:
                </p>
            @endif
            
            <!-- Customer Info -->
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                <h3 style="margin: 0 0 12px; color: #0f2744; font-size: 14px;">Datos del Cliente</h3>
                <p style="margin: 0 0 4px; color: #334155;"><strong>{{ $quote->customer_name }}</strong></p>
                <p style="margin: 0 0 4px; color: #64748b;">{{ $quote->customer_email }}</p>
                @if($quote->customer_phone)
                    <p style="margin: 0 0 4px; color: #64748b;">Tel: {{ $quote->customer_phone }}</p>
                @endif
                @if($quote->customer_company)
                    <p style="margin: 0; color: #64748b;">Empresa: {{ $quote->customer_company }}</p>
                @endif
            </div>

            <!-- Products Table -->
            <h3 style="margin: 0 0 12px; color: #0f2744; font-size: 14px;">Productos Cotizados</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 10px; text-align: left; font-size: 12px; color: #475569; border-bottom: 2px solid #e2e8f0;">Producto</th>
                        <th style="padding: 10px; text-align: center; font-size: 12px; color: #475569; border-bottom: 2px solid #e2e8f0;">Cant.</th>
                        <th style="padding: 10px; text-align: right; font-size: 12px; color: #475569; border-bottom: 2px solid #e2e8f0;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $item)
                        <tr>
                            <td style="padding: 12px 10px; border-bottom: 1px solid #e2e8f0;">
                                <strong style="color: #334155;">{{ $item->product->name }}</strong><br>
                                <small style="color: #94a3b8;">SKU: {{ $item->product->sku_code }}</small>
                            </td>
                            <td style="padding: 12px 10px; text-align: center; border-bottom: 1px solid #e2e8f0; color: #475569;">{{ $item->quantity }}</td>
                            <td style="padding: 12px 10px; text-align: right; border-bottom: 1px solid #e2e8f0; color: #334155; font-weight: 600;">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Subtotal:</td>
                        <td style="padding: 4px 0; text-align: right; color: #334155;">${{ number_format($quote->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">IVA ({{ get_tax_rate() }}%):</td>
                        <td style="padding: 4px 0; text-align: right; color: #334155;">${{ number_format($quote->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0 0; font-size: 16px; font-weight: 700; color: #0f2744;">Total:</td>
                        <td style="padding: 8px 0 0; text-align: right; font-size: 18px; font-weight: 700; color: #0f2744;">${{ number_format($quote->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            @if($quote->notes)
                <div style="margin-top: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 0 8px 8px 0;">
                    <strong style="color: #92400e; font-size: 12px;">Notas:</strong>
                    <p style="margin: 4px 0 0; color: #78350f; font-size: 13px;">{{ $quote->notes }}</p>
                </div>
            @endif

            <!-- Action Button -->
            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ route('quote.view', $quote) }}" style="display: inline-block; background: #3b82f6; color: #ffffff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                    Ver Cotización Completa
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; padding: 20px 32px; text-align: center;">
            <p style="margin: 0 0 4px; color: #64748b; font-size: 12px;">
                Cotización válida hasta: <strong>{{ $quote->valid_until?->format('d/m/Y') }}</strong>
            </p>
            <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                B&R Tecnología — {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
