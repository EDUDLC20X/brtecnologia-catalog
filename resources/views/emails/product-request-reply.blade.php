<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuesta a su consulta</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #003366 0%, #1a5276 100%); padding:30px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:24px;">
                                B&R Tecnología
                            </h1>
                            <p style="margin:10px 0 0; color:#a0c4e8; font-size:14px;">
                                Respuesta a su consulta
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 20px; font-size:16px; color:#333;">
                                Estimado/a <strong>{{ $productRequest->name }}</strong>,
                            </p>
                            
                            <p style="margin:0 0 20px; font-size:14px; color:#666;">
                                Hemos recibido y procesado su consulta sobre el producto:
                            </p>
                            
                            @if($productRequest->product)
                            <!-- Product Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f9fa; border-radius:6px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:15px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align:top;">
                                                    <p style="margin:0; font-weight:bold; color:#003366; font-size:16px;">
                                                        {{ $productRequest->product->name }}
                                                    </p>
                                                    <p style="margin:5px 0 0; color:#888; font-size:12px;">
                                                        SKU: {{ $productRequest->product->sku_code }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- Original Message -->
                            <div style="background-color:#fff3cd; border-left:4px solid #ffc107; padding:15px; margin-bottom:20px; border-radius:0 6px 6px 0;">
                                <p style="margin:0 0 10px; font-weight:bold; color:#856404; font-size:12px;">
                                    <span style="text-transform:uppercase;">Su mensaje original:</span>
                                </p>
                                <p style="margin:0; color:#856404; font-size:14px; font-style:italic;">
                                    "{{ $productRequest->message }}"
                                </p>
                            </div>
                            
                            <!-- Admin Reply -->
                            <div style="background-color:#d4edda; border-left:4px solid #28a745; padding:15px; margin-bottom:20px; border-radius:0 6px 6px 0;">
                                <p style="margin:0 0 10px; font-weight:bold; color:#155724; font-size:12px;">
                                    <span style="text-transform:uppercase;">Nuestra respuesta:</span>
                                </p>
                                <div style="color:#155724; font-size:14px; line-height:1.6;">
                                    {!! nl2br(e($productRequest->admin_reply)) !!}
                                </div>
                            </div>
                            
                            <p style="margin:20px 0; font-size:14px; color:#666;">
                                Si tiene alguna pregunta adicional, no dude en contactarnos respondiendo a este correo o visitando nuestra página de contacto.
                            </p>
                            
                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:20px 0;">
                                        @if($productRequest->product)
                                        <a href="{{ route('catalog.show', $productRequest->product->slug) }}" 
                                           style="display:inline-block; background-color:#003366; color:#ffffff; text-decoration:none; padding:12px 30px; border-radius:5px; font-weight:bold; font-size:14px;">
                                            Ver Producto
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8f9fa; padding:20px; text-align:center; border-top:1px solid #eee;">
                            <p style="margin:0; color:#888; font-size:12px;">
                                © {{ date('Y') }} B&R Tecnología. Todos los derechos reservados.
                            </p>
                            <p style="margin:10px 0 0; color:#aaa; font-size:11px;">
                                Este correo fue enviado en respuesta a su consulta.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
