<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Evaluaciones</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
            h1 { font-size: 20px; margin-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-top: 12px; }
            th, td { border: 1px solid #cbd5f5; padding: 6px 8px; }
            th { background: #f1f5f9; text-transform: uppercase; font-size: 10px; letter-spacing: 0.08em; }
            .meta { font-size: 10px; color: #64748b; }
        </style>
    </head>
    <body>
        <h1>Reporte de Evaluaciones</h1>
        <p class="meta">Generado el {{ $generatedAt->format('d/m/Y H:i') }} · Filtrado por: {{ implode(', ', array_filter($filters)) ?: 'Todos' }}</p>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Tienda</th>
                    <th>Cadena</th>
                    <th>Estatus</th>
                    <th>Calidad</th>
                    <th>Precio</th>
                    <th>Incidencias</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluations as $evaluation)
                    @php
                        $incidentCount = collect($evaluation->incidents['categories'] ?? [])->filter()->count();
                    @endphp
                    <tr>
                        <td>{{ $evaluation->code }}</td>
                        <td>{{ optional($evaluation->visited_at ?? $evaluation->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $evaluation->store?->name }}</td>
                        <td>{{ $evaluation->store?->chain?->name }}</td>
                        <td>{{ ucfirst($evaluation->status) }}</td>
                        <td>{{ $evaluation->quality_rating ?? 'N/D' }}</td>
                        <td>${{ number_format($evaluation->price_observed ?? 0, 2) }}</td>
                        <td>{{ $incidentCount }}</td>
                        <td>{{ $evaluation->review_notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
