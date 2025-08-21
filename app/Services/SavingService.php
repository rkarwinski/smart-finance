<?php
namespace App\Services;

use App\Models\Saving;
use App\Services\Contracts\SavingServiceInterface;

class SavingService implements SavingServiceInterface
{
    public function store(array $data)
    {
        return Saving::create($data);
    }
    public function getAll($month = null, $year = null)
    {
        $query = Saving::query();
        if ($month && $year) {
            // Lógica do ciclo financeiro: dia 25 do mês anterior até dia 25 do mês atual
            $startDate = \Carbon\Carbon::create($year, $month, 25)->subMonth();
            $endDate = \Carbon\Carbon::create($year, $month, 25);
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function findById($id)
    {
        return Saving::findOrFail($id);
    }
    
    public function update($id, array $data)
    {
        $saving = Saving::findOrFail($id);
        $saving->update($data);
        return $saving;
    }
    public function filterByMonth($month, $year)
    {
        // Lógica do ciclo financeiro: dia 25 do mês anterior até dia 25 do mês atual
        $startDate = \Carbon\Carbon::create($year, $month, 25)->subMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 25);
        
        return Saving::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function delete($id)
    {
        $saving = Saving::findOrFail($id);
        return $saving->delete();
    }
    
    public function getTotals()
    {
        // Calcula o total considerando depósitos como positivos e retiradas como negativos
        $totals = Saving::selectRaw('
            currency,
            SUM(CASE WHEN transaction_type = "deposit" THEN amount ELSE -amount END) as total
        ')
        ->groupBy('currency')
        ->get()
        ->pluck('total', 'currency')
        ->toArray();

        return [
            'BRL' => $totals['BRL'] ?? 0,
            'EUR' => $totals['EUR'] ?? 0
        ];
    }
}
