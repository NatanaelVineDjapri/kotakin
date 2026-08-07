<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service) {}

    public function index()
    {
        return SupplierResource::collection($this->service->getAll());
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = $this->service->create($request->validated());
        return new SupplierResource($supplier);
    }

    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier = $this->service->update($supplier, $request->validated());
        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $this->service->delete($supplier);
        return response()->json(['message' => 'Supplier berhasil dihapus']);
    }
}
