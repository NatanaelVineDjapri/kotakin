<?php

namespace App\Services;

use App\Models\Umkm;
use App\Models\User;

class UmkmService
{
    public function getAllUmkm()
    {
        return Umkm::all();
    }

    public function updateUmkmById($id, array $validated): Umkm
    {
        $umkm = Umkm::findOrFail($id);
        $allowed = collect($validated)->only(['nama_umkm', 'email_pemilik', 'no_hp', 'alamat'])->toArray();
        $umkm->update($allowed);
        return $umkm->fresh();
    }

    public function delete(User $user): void
    {
        $umkm = Umkm::findOrFail($user->umkm_id);
        $umkm->delete();
        $user->update(['umkm_id' => null]);
    }

    public function getUmkmById(int $id): Umkm
    {
        return Umkm::findOrFail($id);
    }   

    public function createUmkm(array $validated): Umkm
    {
        $allowed = collect($validated)->only(['nama_umkm', "email_pemilik",'no_hp', 'alamat'])->toArray();

        return Umkm::create($allowed);
    }

    public function deleteUmkmById(int $id): void
    {
        $umkm = Umkm::findOrFail($id);
        $umkm->delete();
    }   
}