# Product Requirements Document (PRD) — Kotaklin

## 1. Ringkasan Produk

**Kotaklin** adalah platform SaaS *all-in-one business management* untuk pelaku UMKM di Indonesia. Kotaklin membantu UMKM mengelola keuangan, stok barang, dan karyawan dalam satu tempat, dengan antarmuka yang sederhana dan mudah dipahami — dirancang khusus untuk pengguna yang **tidak punya latar belakang akuntansi**.

## 2. Masalah yang Diselesaikan

UMKM di Indonesia umumnya:
- Mencatat keuangan secara manual (buku/Excel) yang rawan salah dan sulit dipantau.
- Tidak punya sistem untuk memantau stok barang, sehingga sering kehabisan stok tanpa peringatan.
- Mengelola absensi dan gaji karyawan secara manual.
- Tidak punya gambaran menyeluruh (satu layar) tentang kondisi kesehatan bisnis mereka.

Kotaklin menyatukan semua ini dalam satu platform yang simpel.

## 3. Target Pengguna

- Pemilik UMKM (Owner) — biasanya bukan orang dengan latar belakang akuntansi/finansial.
- Manager/supervisor toko atau cabang.
- Karyawan operasional (kasir, staf gudang, dll).

## 4. Role & Hak Akses

Kotaklin punya 2 tingkat role: **platform-level** (lintas semua bisnis/tenant) dan **business-level** (khusus 1 bisnis UMKM).

### Platform-level

| Role | Akses |
|---|---|
| **Super Admin** | Khusus pemilik Kotaklin (bukan pengguna UMKM). Bisa melihat & mengelola seluruh tenant/bisnis yang terdaftar di platform, memantau langganan, dan melakukan tindakan administratif lintas bisnis. Tidak terikat ke satu `business_id` tertentu. |

### Business-level (per UMKM)

| Role | Akses |
|---|---|
| **Admin** | Akses penuh ke seluruh fitur dalam 1 bisnis: keuangan, stok, hutang-piutang, karyawan, payroll, analitik, laporan, pengaturan bisnis. Setara dengan "Owner" pada rancangan awal. |
| **Karyawan** | Hanya bisa: absensi (check-in/check-out/izin), melihat slip gaji sendiri, dan melihat informasi terkait pekerjaannya sendiri. |
| **Kasir** | Fokus transaksi penjualan: mencatat transaksi pemasukan (penjualan) yang otomatis mengurangi stok terkait, dan melihat (read-only) daftar stok barang. Tidak bisa mengubah data stok, keuangan non-penjualan, karyawan, atau payroll. |

## 5. Fitur Utama (MVP)

1. **Dashboard bisnis** — profit, pemasukan, pengeluaran, ringkasan operasional dalam satu layar.
2. **Manajemen keuangan** — pemasukan, pengeluaran, arus kas, laporan keuangan.
3. **Manajemen stok barang** — CRUD produk, notifikasi stok menipis.
4. **Manajemen hutang & piutang** — pencatatan, status lunas/belum, jatuh tempo.
5. **Absensi karyawan** — check-in, check-out, izin, riwayat kehadiran.
6. **Payroll & slip gaji** — perhitungan gaji sederhana, slip gaji per karyawan.
7. **Multi-user & role-based access** — Admin, karyawan, dan kasir.
8. **Analitik bisnis** — performa penjualan, tren, ringkasan kehadiran.
9. **Export laporan** — ke PDF dan Excel.
10. **Business Health Score** — skor tunggal yang merangkum kondisi bisnis secara keseluruhan.

## 6. Prinsip Desain Produk

- **Sederhana dulu, lengkap kemudian.** Jangan tampilkan istilah akuntansi rumit (debit/kredit, jurnal) — gunakan bahasa awam ("uang masuk", "uang keluar").
- **Visual di atas angka.** Gunakan chart, badge warna (hijau/kuning/merah), dan skor, bukan tabel angka mentah, di halaman dashboard.
- **Notifikasi proaktif** — stok menipis, piutang jatuh tempo, harus muncul tanpa user perlu mencari.
- **Mobile-friendly** — banyak pemilik UMKM mengecek bisnis dari HP.

## 7. Out of Scope (untuk versi awal)

- Integrasi POS/kasir fisik.
- Multi-cabang dengan konsolidasi lintas cabang otomatis.
- Integrasi pajak otomatis (e-Faktur, dsb).
- Marketplace/e-commerce integration.

## 8. Metrik Keberhasilan (Indikatif)

- Owner bisa memahami kondisi bisnisnya dalam < 30 detik setelah membuka dashboard.
- Waktu yang dibutuhkan mencatat 1 transaksi keuangan < 15 detik.
- Tingkat penggunaan fitur absensi oleh karyawan (adopsi harian).
