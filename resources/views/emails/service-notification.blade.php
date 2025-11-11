<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del servicio #{{ $service->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2563eb;
            font-size: 22px;
            margin-bottom: 10px;
        }
        p {
            line-height: 1.6;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .highlight {
            background: #e0f2fe;
            padding: 8px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <p style="text-align:center; margin-top:20px;">
    <img src="{{ asset('images/system/logo.png') }}" alt="Logo" height="50">
</p>
<div class="container">
    <h1>Detalle del Servicio #{{ $service->id }}</h1>

    <p>Estimado(a) <strong>{{ $service->customer->names ?? 'Cliente' }}</strong>,</p>

    @if(!empty($messageBody))
        <p>{!! nl2br(e($messageBody)) !!}</p>
        <hr>
    @endif

    <p>A continuación encontrará el detalle del servicio que ha sido atendido por nuestro equipo técnico.</p>

    <div class="highlight">
        <p><strong>Equipo:</strong> {{ $service->equipmentType->name ?? 'N/A' }}</p>
        <p><strong>Marca:</strong> {{ $service->brand->name ?? 'N/A' }}</p>
        <p><strong>Modelo:</strong> {{ $service->model }}</p>
        <p><strong>Fecha de ingreso:</strong> {{ \Carbon\Carbon::parse($service->date_entry)->format('d/m/Y H:i') }}</p>
        <p><strong>Estado actual:</strong> {{ $service->state->name ?? 'N/A' }}</p>
    </div>

    <p>
        Si seleccionó la opción de adjuntar PDF, encontrará el documento con el resumen completo del servicio.
    </p>

    <p>Gracias por confiar en nuestros servicios.</p>

    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
    </div>
</div>
</body>
</html>
