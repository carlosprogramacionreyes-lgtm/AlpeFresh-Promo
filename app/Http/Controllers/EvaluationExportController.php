<?php

namespace App\Http\Controllers;

use App\Exports\EvaluationsExport;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationExportController extends Controller
{
    public function __invoke(Request $request, string $format)
    {
        $format = Str::lower($format);

        $evaluations = $this->buildQuery($request)->get();

        if ($format === 'xlsx' || $format === 'excel') {
            $fileName = 'evaluaciones-' . now()->format('Ymd-His') . '.xlsx';

            return Excel::download(new EvaluationsExport($evaluations), $fileName);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.evaluations.pdf', [
                'evaluations' => $evaluations,
                'generatedAt' => Carbon::now(),
                'filters' => $request->only(['store', 'status', 'date_from', 'date_to', 'search']),
            ])->setPaper('a4', 'portrait');

            $fileName = 'evaluaciones-' . now()->format('Ymd-His') . '.pdf';

            return $pdf->download($fileName);
        }

        abort(404, 'Formato de exportación no soportado.');
    }

    protected function buildQuery(Request $request)
    {
        $query = Evaluation::query()->with(['store.chain', 'store.zone']);

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereRaw('DATE(COALESCE(visited_at, created_at)) >= ?', [$dateFrom]);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereRaw('DATE(COALESCE(visited_at, created_at)) <= ?', [$dateTo]);
        }

        if ($search = $request->input('search')) {
            $term = '%' . mb_strtolower($search) . '%';
            $query->where(function ($subQuery) use ($term) {
                $subQuery->whereRaw('LOWER(code) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(review_notes) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(incident_comments) LIKE ?', [$term])
                    ->orWhere('availability', 'like', $term)
                    ->orWhereHas('store', function ($storeQuery) use ($term) {
                        $storeQuery->whereRaw('LOWER(name) LIKE ?', [$term]);
                    });
            });
        }

        return $query->orderByDesc('visited_at')->orderByDesc('created_at');
    }
}
