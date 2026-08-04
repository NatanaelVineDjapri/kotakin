# DESIGN.md — Kotakin

Dokumen ini berisi aturan desain untuk seluruh antarmuka Kotakin (dashboard Super Admin, Admin, Kasir, dan Karyawan). Arah desain yang dituju: **dark theme, fungsional, dan rapi layaknya template admin pada umumnya** — bukan landing page maupun produk konsumen. Prioritas utama adalah keterbacaan data, kepadatan informasi yang wajar, serta konsistensi antar halaman.

## Prinsip

1. **Fungsi diutamakan di atas gaya.** Ini adalah alat kerja yang digunakan setiap hari (memeriksa stok, absensi, transaksi), bukan media showcase. Animasi berlebihan, gradient dekoratif, dan ilustrasi sebaiknya dihindari.
2. **Satu warna aksen aktif.** Seluruh elemen non-status (tombol utama, tautan aktif, fokus input) menggunakan satu warna aksen yang sama. Warna chromatic lain hanya digunakan untuk keperluan **status** (badge sukses/gagal/pending).
3. **Hairline border, bukan shadow.** Pemisah antar card atau panel menggunakan border tipis 1px, bukan drop shadow tebal, agar tampilan tetap flat dan tidak berat dipandang mengingat dashboard ini dibuka dalam durasi lama setiap harinya.
4. **Data padat namun tetap nyaman dibaca.** Tabel dan card statistik boleh disusun rapat, tetapi tetap perlu diberi ruang yang cukup (lihat bagian spacing) agar antar baris tabel tidak terlihat berhimpitan.

---

## Warna

| Nama | Value | Peran |
|---|---|---|
| Canvas | `#0b0c0e` | Latar belakang utama halaman |
| Surface | `#141518` | Card, sidebar, table container, modal |
| Surface Raised | `#1c1e22` | Elemen di atas Surface (dropdown, popover, nested card) |
| Border | `#26282d` | Border standar, pemisah antar section |
| Border Strong | `#34363c` | Border input saat focus/hover, divider yang lebih tegas |
| Text Primary | `#f2f2f3` | Judul, angka penting, isi tabel utama |
| Text Secondary | `#a6a9b1` | Label, deskripsi, isi tabel sekunder |
| Text Muted | `#6b6e76` | Placeholder, metadata (timestamp, ID), teks nonaktif |
| Accent | `#3b82f6` | Tombol utama, tautan aktif, checkbox/radio terpilih, fokus input |
| Accent Hover | `#2f6fdb` | State hover dari Accent |
| Success | `#22c55e` | Badge lunas/hadir/aktif, angka nominal pemasukan |
| Warning | `#eab308` | Badge pending/menipis/mendekati jatuh tempo |
| Danger | `#ef4444` | Badge telat/gagal/hutang, angka nominal pengeluaran, tombol hapus |
| Info | `#06b6d4` | Badge/ikon informatif netral (opsional, jarang digunakan) |

### Aturan warna status (penting untuk data bisnis)
- **Success** digunakan khusus untuk: status "lunas", "hadir", "aktif", serta angka pemasukan.
- **Danger** digunakan khusus untuk: status "telat", "hutang belum lunas", angka pengeluaran, dan aksi destruktif (hapus/nonaktifkan).
- **Warning** digunakan khusus untuk: stok mendekati batas minimum, piutang mendekati jatuh tempo, status "sebagian".
- Accent tidak digunakan untuk menyampaikan status baik/buruk — fungsinya murni untuk aksi dan navigasi, bukan makna data.

---

## Tipografi

**Font:** Inter (dengan `system-ui` sebagai fallback), digunakan untuk seluruh elemen UI termasuk angka pada tabel dan dashboard.

| Role | Size | Weight | Line Height | Digunakan untuk |
|---|---|---|---|---|
| display | 28px | 590 | 1.2 | Angka besar pada stat card (mis. "Rp 12.500.000") |
| heading | 20px | 510 | 1.3 | Judul halaman ("Data Karyawan") |
| subheading | 16px | 510 | 1.4 | Judul card, judul section dalam halaman |
| body | 14px | 400 | 1.5 | Isi tabel, isi form, teks umum |
| body-sm | 13px | 400 | 1.5 | Label form, keterangan singkat |
| caption | 12px | 400 | 1.4 | Metadata, timestamp, teks pada badge |

**Aturan:**
- Weight maksimal yang digunakan adalah **590** (setara semi-bold). Weight 700 ke atas (bold penuh) tidak digunakan, agar kesan visual tetap konsisten dan tidak berat.
- Angka nominal (uang, jumlah stok) dapat menggunakan `font-variant-numeric: tabular-nums` agar tampilan angka rapi dan sejajar saat ditampilkan dalam kolom tabel.
- Warna teks default: `Text Primary` untuk konten utama, `Text Secondary` untuk label/pendukung, `Text Muted` untuk metadata. Warna chromatic (Accent/Success/Danger) tidak digunakan untuk body text biasa, dan hanya dicadangkan untuk badge, status, atau angka finansial yang memang perlu ditonjolkan.

---

## Spacing & Layout

**Base unit:** 4px

| Token | Value | Digunakan untuk |
|---|---|---|
| gap-xs | 4px | Jarak antar ikon-teks, antar elemen inline kecil |
| gap-sm | 8px | Jarak antar field form, antar item pada toolbar |
| gap-md | 16px | Padding dalam card, jarak antar card dalam grid |
| gap-lg | 24px | Jarak antar section dalam satu halaman |
| gap-xl | 40px | Jarak antara header halaman dan konten utama |

- **Sidebar width:** 240px (expanded), 64px (collapsed — hanya ikon).
- **Topbar height:** 56px.
- **Content max-width:** tidak dibatasi secara ketat (berbeda dari landing page) — tabel dan dashboard dapat mengikuti lebar container sepenuhnya, dengan padding kiri-kanan 24px dari tepi layar.
- **Table row height:** 44px, cukup lega untuk digunakan pada layar sentuh mengingat kemungkinan penggunaan di tablet oleh kasir atau staf gudang.

### Border Radius
- **card / modal:** 8px
- **button / input:** 6px
- **badge / pill:** 4px (bukan pill penuh, agar konsisten dengan gaya "kotak rapi" khas admin tool, berbeda dari badge bulat penuh pada produk konsumen)
- **avatar / foto profil:** 9999px (pengecualian, khusus elemen berbentuk bulat)

---

## Komponen

### Sidebar Navigation
Background `Surface`, border kanan 1px `Border`. Item aktif: background `Accent` dengan opacity 12% dan teks `Accent`, ikon diletakkan di kiri. Item nonaktif: teks `Text Secondary`, berubah menjadi `Surface Raised` saat hover.

### Topbar
Background `Canvas` (menyatu dengan latar halaman), border bawah 1px `Border`. Berisi breadcrumb/judul halaman di sisi kiri, serta avatar, nama pengguna, dan notifikasi di sisi kanan.

### Stat Card (untuk dashboard)
Background `Surface`, border 1px `Border`, radius 8px, padding 16px. Terdiri dari label kecil (`body-sm`, `Text Secondary`) di bagian atas dan angka besar (`display`, `Text Primary`) di bawahnya, dengan opsi badge kecil di pojok kanan atas sebagai indikator naik/turun (menggunakan Success/Danger).

### Tabel Data
Header kolom: background `Surface Raised`, teks `body-sm` weight 510 `Text Secondary`, uppercase bersifat opsional. Baris: background `Surface`, dengan border-bottom 1px `Border` sebagai pemisah antar baris (bukan border penuh pada setiap sel, agar tampilan tidak terlalu "grid berat"). Hover pada baris: background berubah menjadi `Surface Raised`. Zebra-striping (perbedaan warna baris genap-ganjil) **tidak diperlukan** — cukup mengandalkan border-bottom tipis sebagai pemisah.

### Tombol Utama (Primary Button)
Background `Accent`, teks putih, radius 6px, padding 8px 16px, `body` weight 510. Hover: `Accent Hover`.

### Tombol Sekunder (Ghost/Outline)
Background transparan, border 1px `Border`, teks `Text Secondary`, radius 6px, padding 8px 16px. Hover: background `Surface Raised`.

### Tombol Bahaya (Danger)
Mengikuti gaya Tombol Utama dengan background `Danger`. Digunakan khusus untuk aksi hapus atau nonaktifkan yang bersifat permanen.

### Input Form
Background `Surface`, border 1px `Border`, radius 6px, padding 10px 12px, teks `body` `Text Primary`. Placeholder menggunakan `Text Muted`. Saat focus, border berubah menjadi `Accent` tanpa tambahan glow atau shadow.

### Badge / Status Pill
Radius 4px, padding 2px 8px, `caption` weight 510. Background menggunakan warna status dengan opacity 15%, teks menggunakan warna status secara solid. Contoh: badge "Lunas" menggunakan background Success dengan opacity 15% dan teks Success solid.

### Modal / Dialog
Background `Surface Raised`, border 1px `Border`, radius 8px, padding 24px, dengan overlay `rgba(0,0,0,0.6)` di belakangnya (tanpa efek blur berlebihan).

### Chart (grafik dashboard)
Garis atau bar utama menggunakan `Accent`. Untuk chart yang memerlukan multi-series (misalnya perbandingan pemasukan dan pengeluaran), gunakan Success untuk pemasukan dan Danger untuk pengeluaran, mengikuti aturan warna status di atas. Grid line pada chart menggunakan `Border` secara tipis agar tidak mendominasi tampilan.

---

## Do's and Don'ts

### Do
- Gunakan border 1px `Border` untuk memisahkan card atau section — bukan shadow tebal.
- Konsisten menggunakan `Accent` yang sama untuk seluruh tombol utama dan elemen aktif — hindari mencampur beberapa warna untuk aksi dengan tingkat kepentingan yang sama.
- Pertahankan hierarki warna teks (Primary → Secondary → Muted) sesuai tingkat kepentingan informasi.
- Gunakan warna status (Success/Warning/Danger) hanya untuk menyampaikan kondisi data, bukan sebagai dekorasi.
- Jaga agar tabel tetap mudah dipindai: angka rata kanan, teks rata kiri, header kolom jelas.

### Don't
- Hindari gradient dekoratif pada background, card, atau tombol — ini bukan landing page.
- Hindari penggunaan lebih dari satu warna chromatic untuk elemen aksi (Accent tetap tunggal).
- Hindari radius besar (16px ke atas) pada card/panel — kesan yang timbul akan lebih mendekati aplikasi konsumen, bukan admin tool.
- Hindari animasi atau transisi berlebihan (fade panjang, bounce, dan sejenisnya) — cukup gunakan transisi warna 100–150ms untuk hover/focus.
- Hindari penggunaan bold penuh (700 ke atas) di seluruh bagian antarmuka — batas maksimal tetap pada weight 590.
- Hindari istilah akuntansi teknis pada label UI (sebagaimana telah diatur pada `RULES.md`) — ketentuan ini juga berlaku pada penataan hierarki visual: uang masuk dan uang keluar perlu dibedakan secara jelas melalui warna (Success/Danger), bukan melalui istilah yang rumit.