<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private readonly Collection $evaluations)
    {
    }

    public function collection(): Collection
    {
        return $this->evaluations;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Fecha',
            'Tienda',
            'Cadena',
            'Estatus',
            'Calidad',
            'Precio observado',
            'Promoción',
            'Incidencias',
            'Comentarios',
        ];
    }

    public function map($evaluation): array
    {
        $incidentCount = collect($evaluation->incidents['categories'] ?? [])->filter()->count();

        return [
            $evaluation->code,
            optional($evaluation->visited_at ?? $evaluation->created_at)->format('Y-m-d H:i'),
            $evaluation->store?->name,
            $evaluation->store?->chain?->name,
            ucfirst($evaluation->status),
            $evaluation->quality_rating,
            $evaluation->price_observed,
            $evaluation->has_promotion ? 'Sí' : 'No',
            $incidentCount,
            $evaluation->review_notes,
        ];
    }
}
