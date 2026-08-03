# Coding Rules & Conventions — Kotaklin

Dokumen ini adalah aturan wajib saat menulis kode di project ini — baik oleh developer maupun AI coding assistant (Claude Code, dll). Tujuannya: konsistensi, agar siapapun (atau AI manapun) yang menyentuh kode tetap menghasilkan pola yang sama.

## 1. Umum

- Commit message pakai **bahasa Inggris**, format Conventional Commits:
  `feat: ...`, `fix: ...`, `chore: ...`, `refactor: ...`, `docs: ...`
- Nama variabel & fungsi dalam kode: **bahasa Inggris**.
- Nama tabel & kolom database: **snake_case**. Project ini menggunakan **bahasa Indonesia** untuk nama tabel/kolom bisnis (`umkms`, `karyawans`, `absensis`, `bahan_bakus`, dst) — ini keputusan final, jangan diterjemahkan ke bahasa Inggris. Nilai ENUM juga wajib snake_case tanpa spasi (`super_admin`, bukan `super admin`).
- Label yang tampil ke user (UI): **bahasa Indonesia**, kasual tapi jelas, hindari istilah akuntansi teknis.

## 2. Backend (Laravel)

- Controller **tidak boleh** berisi query kompleks atau logic bisnis. Pindahkan ke `app/Services/`.
- Semua input dari request divalidasi lewat **Form Request class** (`app/Http/Requests/`), bukan validasi manual di controller.
- Semua response API wajib melalui **JsonResource**, agar format konsisten (lihat `ARCHITECTURE.md` bagian format response).
- Otorisasi role **wajib** lewat middleware (`role:admin`, `role:super_admin`, dll) atau Policy — **jangan** hardcode `if ($user->role === 'admin')` di controller.
- Role yang berlaku: `super_admin` (platform, lintas bisnis), `admin`, `karyawan`, `kasir` (semua business-level, terikat `business_id`).
- Endpoint khusus `super_admin` dipisah di prefix `/api/v1/platform/...`, **jangan** dicampur dengan endpoint business-level biasa.
- Kasir hanya boleh **membuat** transaksi bertipe penjualan (income) dan **membaca** data stok — endpoint update/delete stok dan transaksi non-penjualan harus menolak role `kasir` di level Policy, bukan disembunyikan di frontend saja.
- Route API selalu diawali `/api/v1/`.
- Kolom foreign key yang menunjuk ke `users` pada tabel data historis/finansial (mis. `transaksis.kasir_id`, `keuangans.created_by`) **wajib** menggunakan `nullOnDelete()`, **bukan** `cascadeOnDelete()` — data transaksi/keuangan tidak boleh terhapus otomatis hanya karena user penginputnya dihapus dari sistem.
- Setiap tabel yang berkaitan dengan uang: kolom bertipe `decimal(15,2)`, **jangan** `float`.
- Setiap model yang scoped ke bisnis tertentu wajib punya `business_id` dan di-scope otomatis (Global Scope) agar data antar bisnis tidak bocor.

## 3. Frontend (Next.js)

- Semua pemanggilan API lewat instance `lib/api.ts` (axios), **jangan** `fetch` langsung tersebar di berbagai file.
- Data fetching pakai **TanStack Query** — jangan `useEffect` + `useState` manual untuk fetch data.
- Komponen UI dasar (button, card, dll) dari `components/ui/` (shadcn) — jangan bikin ulang komponen serupa secara manual.
- Komponen spesifik fitur dikelompokkan per folder fitur (`components/finance/`, `components/inventory/`, dst), bukan ditaruh rata di `components/`.
- Semua tipe data (Transaction, Product, dst) didefinisikan di `types/`, dipakai ulang — jangan duplikasi `interface` di banyak file.
- Form wajib pakai **React Hook Form + Zod** untuk validasi, konsisten di semua form.

## 4. Keamanan & Data

- Jangan pernah expose kolom sensitif (password hash, token) lewat JsonResource.
- Setiap endpoint yang mengubah data wajib dicek: apakah `business_id` milik user yang login? (mencegah user bisnis A mengubah data bisnis B).
- File `.env` **tidak boleh** ikut di-commit — selalu cek `.gitignore` sebelum push.

## 5. Penamaan File & Folder

- File komponen React: `PascalCase.tsx` (mis. `ProfitChart.tsx`).
- File Service/Model Laravel: `PascalCase.php` (mis. `BusinessHealthScoreService.php`).
- Migration: format default Laravel (`2026_01_01_000000_create_xxx_table.php`).

## 6. Yang Harus Dihindari

- ❌ Query N+1 (selalu pakai `->with()` untuk eager loading relasi).
- ❌ Menyimpan token auth di `localStorage` untuk production (rawan XSS) — gunakan cookie httpOnly bila memungkinkan.
- ❌ Menampilkan istilah akuntansi teknis (debit/kredit/jurnal) di UI — target user tidak familiar dengan itu.
- ❌ Hardcode role check tersebar di banyak file — selalu terpusat di middleware/policy.
