# Architecture — Kotaklin

## 1. Pola Arsitektur

Kotaklin menggunakan pola **decoupled/headless**:

```
[ Next.js Frontend ]  <-- REST API (JSON) -->  [ Laravel Backend ]  <-->  [ MySQL/PostgreSQL ]
```

- **Backend (Laravel)**: API-only, tidak render HTML/Blade untuk aplikasi utama. Bertugas: autentikasi, business logic, validasi, akses database.
- **Frontend (Next.js)**: App Router, konsumsi API via `fetch`/`axios`, render UI, state management di sisi klien.

Alasan pemilihan pola ini:
- Backend dan frontend bisa di-deploy dan di-scale terpisah.
- Membuka jalan untuk klien lain di masa depan (mobile app) tanpa menulis ulang logic bisnis.
- Tim frontend & backend bisa bekerja paralel dengan kontrak API yang jelas.

## 2. Struktur Repository (Monorepo)

```
kotaklin/
├── backend/          # Laravel API
├── frontend/         # Next.js App
└── docs/             # PRD, Architecture, Rules, Schema
```

## 3. Tech Stack

| Layer | Teknologi | Alasan |
|---|---|---|
| Backend framework | Laravel 11 | Ekosistem matang, cepat untuk CRUD-heavy app |
| Auth | Laravel Sanctum | Token-based, ringan, cocok untuk SPA |
| Role & Permission | Spatie Laravel-Permission | Standar industri, fleksibel untuk role Super Admin (platform) + Admin/Karyawan/Kasir (per bisnis) |
| Export Excel | Maatwebsite/Excel | Library export/import Excel paling stabil untuk Laravel |
| Export PDF | Barryvdh/Laravel-DomPDF | Cukup untuk laporan sederhana, tanpa dependency berat |
| Frontend framework | Next.js (App Router) + TypeScript | Type-safety, SSR/SSG opsional, ekosistem React |
| Styling & UI | Tailwind CSS + shadcn/ui | Cepat membangun UI konsisten, mudah dikustomisasi |
| Data fetching | TanStack Query (React Query) | Caching, loading/error state otomatis |
| Global state | Zustand | Ringan, tanpa boilerplate seperti Redux |
| Chart | Recharts | Cocok untuk dashboard finansial |
| Form & validasi | React Hook Form + Zod | Validasi type-safe, performant |

## 4. Alur Autentikasi

1. User login dari Next.js `.
2. Laravel Sanctum mengeluarkan token.
3. Token disimpan di klien (memory/http-only cookie — **hindari localStorage untuk token di production**, gunakan cookie httpOnly jika memungkinkan).
4. Setiap request API menyertakan token via header `Authorization: Bearer <token>`.
5. Middleware `auth:sanctum` + `role:xxx` (Spatie) mem-validasi akses di tiap endpoint.

**Catatan khusus Super Admin:** role ini beroperasi di luar scope `business_id` (lintas tenant). Endpoint yang hanya untuk Super Admin ditaruh di prefix terpisah, mis. `/api/v1/platform/...`, dan dilindungi middleware `role:super_admin` — **tidak digabung** dengan endpoint bisnis biasa (`/api/v1/...`) agar tidak ada celah akses lintas tenant yang tidak disengaja.

## 5. Struktur Folder Backend

```
backend/app/
├── Models/                  # Eloquent models
├── Http/
│   ├── Controllers/Api/     # dikelompokkan per modul (Finance, Inventory, Employee, dst)
│   ├── Requests/            # Form Request validation per aksi
│   └── Resources/           # JsonResource, kontrol format response
├── Services/                 # Business logic (mis. BusinessHealthScoreService)
└── Policies/                 # Otorisasi per role/resource
```

**Prinsip:** Controller harus tipis (thin controller). Logic bisnis (perhitungan, aturan) ditaruh di `Services/`, bukan di controller.

## 6. Struktur Folder Frontend

```
frontend/src/
├── app/
│   ├── (auth)/               # login, register
│   └── (dashboard)/          # halaman setelah login, per fitur
├── components/
│   ├── ui/                   # komponen dasar (shadcn)
│   └── <feature>/            # komponen spesifik fitur (finance/, inventory/, dst)
├── lib/                       # axios instance, utils
├── hooks/                     # custom hooks (useAuth, useBusiness, dst)
├── store/                     # zustand stores
└── types/                     # TypeScript interfaces
```

## 7. Format Response API (kontrak standar)

```json
{
  "data": { },
  "message": "Success",
  "errors": null
}
```

Untuk list dengan pagination:
```json
{
  "data": [ ],
  "meta": { "current_page": 1, "total": 100, "per_page": 20 },
  "message": "Success"
}
```

## 8. Keputusan Desain (Decision Log)

| Keputusan | Alasan |
|---|---|
| Sanctum dipilih daripada Passport | Kotaklin tidak butuh OAuth2 penuh, Sanctum lebih ringan untuk SPA/token auth |
| Monorepo, bukan 2 repo terpisah | Memudahkan koordinasi versi API-frontend di tahap awal, bisa dipecah nanti jika tim membesar |
| Business Health Score dihitung di backend (Service), bukan di frontend | Logic bisnis harus konsisten di satu tempat, frontend hanya menampilkan hasil |
| Semua angka finansial disimpan sebagai `decimal`, bukan `float` | Menghindari floating point error pada perhitungan uang |

## 9. Rencana Skalabilitas

- Setiap modul bisnis (Finance, Inventory, Employee) dirancang sebagai unit terpisah agar mudah diekstrak jadi microservice di masa depan bila diperlukan.
- Business Health Score & Analytics menggunakan Service Layer terpisah agar logic bisa dites unit secara independen.
- API versioning (`/api/v1/`) sejak awal untuk menghindari breaking change saat scale.
