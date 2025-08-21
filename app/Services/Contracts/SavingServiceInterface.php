<?php
namespace App\Services\Contracts;

interface SavingServiceInterface
{
    public function store(array $data);
    public function getAll($month = null, $year = null);
    public function findById($id);
    public function filterByMonth($month, $year);
    public function update($id, array $data);
    public function delete($id);
    public function getTotals();
}
