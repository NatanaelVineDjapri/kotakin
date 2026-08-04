<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierService
{
    public function getAll(): Collection
    {
        return Supplier::all();
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier;
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }
}
