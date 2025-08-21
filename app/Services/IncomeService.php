<?php
namespace App\Services;

use App\Models\Income;
use App\Services\Contracts\IncomeServiceInterface;
use Carbon\Carbon;

class IncomeService implements IncomeServiceInterface
{
    public function store(array $data)
    {
        return Income::create($data);
    }
    public function getAll($month = null, $year = null)
    {
        $query = Income::query();
        if ($month && $year) {
            // Lógica do ciclo financeiro: dia 25 do mês anterior até dia 25 do mês atual
            $startDate = \Carbon\Carbon::create($year, $month, 25)->subMonth();
            $endDate = \Carbon\Carbon::create($year, $month, 25);
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        return $query->orderBy('date', 'desc')->get();
    }
    
    public function findById($id)
    {
        return Income::findOrFail($id);
    }
    
    public function update($id, array $data)
    {
        $income = Income::findOrFail($id);
        $income->update($data);
        return $income;
    }
    
    public function filterByMonth($month, $year)
    {
        // Lógica do ciclo financeiro: dia 25 do mês anterior até dia 25 do mês atual
        $startDate = \Carbon\Carbon::create($year, $month, 25)->subMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 25);
        
        return Income::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
    
    public function delete($id)
    {
        $income = Income::findOrFail($id);
        return $income->delete();
    }
}
