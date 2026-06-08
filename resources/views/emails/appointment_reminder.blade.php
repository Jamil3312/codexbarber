<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Cita</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #030712;
            color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #030712;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #111827;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            border: 1px solid #1f2937;
        }
        .header {
            background-color: #1f2937;
            padding: 30px;
            text-align: center;
            border-bottom: 2px solid #eab308;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 28px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 20px;
        }
        .details-card {
            background-color: #1f2937;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #eab308;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-item:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 18px;
            color: #ffffff;
            font-weight: 600;
        }
        .highlight {
            color: #eab308;
        }
        .footer {
            text-align: center;
            padding: 30px;
            background-color: #0a0f1c;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #1f2937;
        }
        .note {
            background-color: rgba(234, 179, 8, 0.1);
            color: #d1d5db;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }
        .app-link-text {
            text-align: center;
            margin: 30px 0 10px;
            color: #eab308;
            font-weight: 600;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ $barbershopName }}</h1>
            </div>
            
            <div class="content">
                <div class="greeting">¡Hola, {{ $clientName }}!</div>
                
                <p style="color: #d1d5db; font-size: 16px; line-height: 1.6;">
                    Este es un recordatorio amigable de que tienes una cita programada para el día de <strong style="color: #ffffff;">hoy</strong>.
                </p>
                
                <div class="details-card">
                    <div class="detail-item">
                        <div class="detail-label">Hora de tu cita</div>
                        <div class="detail-value highlight" style="font-size: 24px;">{{ $time }}</div>
                    </div>
                    <div class="detail-item" style="margin-top: 20px;">
                        <div class="detail-label">Servicio</div>
                        <div class="detail-value">{{ $serviceName ?? 'Servicio Reservado' }}</div>
                    </div>
                </div>
                
                <div class="note">
                    <strong>Importante:</strong> Te pedimos amablemente llegar con 5 a 10 minutos de anticipación para garantizarte el mejor servicio y respetar el tiempo de todos nuestros clientes.
                </div>
                
                <div class="app-link-text">
                    📱 Visualiza todos los detalles de tu cita en nuestra App.
                </div>
            </div>
            
            <div class="footer">
                <p style="margin: 0;">Gracias por elegir <strong>{{ $barbershopName }}</strong>.</p>
                <p style="margin: 10px 0 0 0; font-size: 11px;">Este es un mensaje automático generado por CodexBarber, por favor no respondas a este correo.</p>
            </div>
        </div>
    </div>
</body>
</html>
