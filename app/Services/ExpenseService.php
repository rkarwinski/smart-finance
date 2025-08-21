<?php
namespace App\Services;

use App\Models\Expense;
use App\Services\Contracts\ExpenseServiceInterface;
use Carbon\Carbon;

class ExpenseService implements ExpenseServiceInterface
{
    public function store(array $data)
    {
        return Expense::create($data);
    }
    public function getAll($month = null, $year = null)
    {
        $query = Expense::query();
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
        return Expense::findOrFail($id);
    }
    
    public function update($id, array $data)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($data);
        return $expense;
    }
    
    public function filterByMonth($month, $year)
    {
        // Lógica do ciclo financeiro: dia 25 do mês anterior até dia 25 do mês atual
        $startDate = \Carbon\Carbon::create($year, $month, 25)->subMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 25);
        
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
    
    public function delete($id)
    {
        $expense = Expense::findOrFail($id);
        return $expense->delete();
    }
}
