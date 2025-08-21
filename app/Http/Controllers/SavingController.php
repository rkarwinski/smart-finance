<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\SavingServiceInterface;

class SavingController extends Controller
{
    protected $service;
    public function __construct(SavingServiceInterface $service)
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
            'transaction_type' => 'required|in:deposit,withdrawal',
            'reason' => 'required_if:transaction_type,withdrawal|nullable|string',
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
            'transaction_type' => 'required|in:deposit,withdrawal',
            'reason' => 'required_if:transaction_type,withdrawal|nullable|string',
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
        return response()->json(['message' => 'Saving deleted successfully']);
    }
    
    public function getTotals()
    {
        return response()->json($this->service->getTotals());
    }
}
