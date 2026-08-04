<?php

namespace App\Services;

use App\Models\Umkm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register UMKM baru beserta akun Admin-nya.
     * Satu registrasi = 1 UMKM + 1 User (role: admin), status: trial.
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $umkm = Umkm::create([
                'nama_umkm'                   => $data['nama_umkm'],
                'email_pemilik'               => $data['email'],
                'no_hp'                       => $data['no_hp'] ?? null,
                'alamat'                      => $data['alamat'] ?? null,
                'status_langganan'            => 'trial',
                'tanggal_mulai_langganan'     => now(),
                'tanggal_berakhir_langganan'  => now()->addDays(14),
            ]);

            $user = User::create([
                'umkm_id'  => $umkm->id,
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('admin');

            $token = $user->createToken('auth_token')->plainTextToken;

            return compact('user', 'token');
        });
    }

    /**
     * Login dengan email & password.
     * Return token Sanctum jika kredensial valid.
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun kamu tidak aktif. Hubungi admin bisnis kamu.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Logout — hapus token yang sedang dipakai saja.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
