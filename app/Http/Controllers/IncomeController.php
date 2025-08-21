<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\IncomeServiceInterface;

class IncomeController extends Controller
{
    protected $service;
    public function __construct(IncomeServiceInterface $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        return response()->json($this->service->getAll($month, $year));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|in:BRL,EUR',
            'amount_eur' => 'nullable|numeric',
            'note' => 'nullable|string',
            'category' => 'required|string',
            'date' => 'nullable|date',
        ]);
        return response()->json($this->service->store($data));
    }
    
    public function show($id)
    {
        return response()->json($this->service->findById($id));
    }
    
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|in:BRL,EUR',
            'amount_eur' => 'nullable|numeric',
            'note' => 'nullable|string',
            'category' => 'required|string',
            'date' => 'nullable|date',
        ]);
        return response()->json($this->service->update($id, $data));
    }
    
    public function filterByMonth($month, $year)
    {
        return response()->json($this->service->filterByMonth($month, $year));
    }
    
    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Income deleted successfully']);
    }
}
