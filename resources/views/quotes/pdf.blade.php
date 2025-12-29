<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ $quote->quote_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .container { padding: 20px 30px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; border-bottom: 3px solid #0C3B67; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #0C3B67; }
        .quote-info { text-align: right; }
        .quote-number { font-size: 18px; font-weight: bold; color: #0C3B67; }
        .quote-date { color: #666; margin-top: 5px; }
        
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #0C3B67; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        
        .customer-info { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .customer-info p { margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #0C3B67; color: white; padding: 10px; text-align: left; font-size: 11px; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { width: 300px; margin-left: auto; }
        .totals td { padding: 8px 10px; }
        .totals .total-row { font-size: 16px; font-weight: bold; background: #0C3B67; color: white; }
        
        .notes { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; }
        .notes-title { font-weight: bold; margin-bottom: 5px; }
        
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 10px; }
        .validity { background: #e8f4fd; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; }
        .validity strong { color: #0C3B67; }
    </style>
</head>
<body>
    <div class="container">
        <table style="width:100%; margin-bottom:30px; border:none;">
            <tr>
                <td style="border:none; padding:0;">
                    <div class="logo">B&R Tecnología</div>
                    <div style="color:#666; font-size:11px;">Su equipo de tecnología en las mejores manos</div>
                </td>
                <td style="border:none; padding:0; text-align:right;">
                    <div class="quote-number">COTIZACIÓN</div>
                    <div style="font-size:16px; font-weight:bold; color:#0C3B67;">{{ $quote->quote_number }}</div>
                    <div class="quote-date">Fecha: {{ $quote->created_at->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

        <div class="section">
            <div class="section-title">DATOS DEL CLIENTE</div>
            <div class="customer-info">
                <p><strong>{{ $quote->customer_name }}</strong></p>
                <p>{{ $quote->customer_email }}</p>
                @if($quote->customer_phone)
                    <p>Tel: {{ $quote->customer_phone }}</p>
                @endif
                @if($quote->customer_company)
                    <p>Empresa: {{ $quote->customer_company }}</p>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">PRODUCTOS COTIZADOS</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Descripción</th>
                        <th style="width:80px" class="text-center">Cant.</th>
                        <th style="width:100px" class="text-right">P. Unit.</th>
                        <th style="width:100px" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product->name }}</strong><br>
                                <span style="color:#666; font-size:10px;">SKU: {{ $item->product->sku_code }}</span>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($quote->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>IVA ({{ get_tax_rate() }}%):</td>
                    <td class="text-right">${{ number_format($quote->tax, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="text-right">${{ number_format($quote->total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($quote->notes)
            <div class="section">
                <div class="notes">
                    <div class="notes-title">Notas del cliente:</div>
                    {{ $quote->notes }}
                </div>
            </div>
        @endif

        <div class="validity">
            <strong>Cotización válida hasta: {{ $quote->valid_until?->format('d/m/Y') ?? 'N/A' }}</strong>
        </div>

        <div class="footer">
            <p><strong>B&R Tecnología</strong> - Su equipo de tecnología en las mejores manos</p>
            <p>Esta cotización es informativa y no constituye una factura. Los precios pueden variar sin previo aviso.</p>
            <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
