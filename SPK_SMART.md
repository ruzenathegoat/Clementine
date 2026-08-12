# Sistem Pendukung Keputusan (SPK) Menggunakan Metode SMART

Dokumen ini menjelaskan rancangan Sistem Pendukung Keputusan (SPK) menggunakan metode **SMART (Simple Multi-Attribute Rating Technique)** yang diterapkan pada fitur *Smart Advisor* di proyek Clementine.

## 1. Pendahuluan
Metode SMART digunakan di *Smart Advisor* untuk merekomendasikan jam tangan (produk) terbaik bagi pelanggan berdasarkan berbagai parameter pencarian (seperti *Budget*, *Gender*, *Case Material*, *Movement*, dan *Strap*). SMART adalah metode pengambilan keputusan multikriteria yang sederhana dan efisien. Model ini menggunakan **Normalisasi Bobot Dinamis**, di mana kriteria yang tidak diisi oleh pengguna akan diabaikan dari perhitungan dan bobot akan disesuaikan.

## 2. Kriteria dan Pembobotan Dasar (Base Weights)
Terdapat 5 kriteria preferensi yang menjadi acuan penilaian algoritma ini. Kriteria stok telah dihapus dari perhitungan SMART dan murni dijadikan sebagai filter kelayakan (hanya produk dengan stok > 0 yang diproses).

| Kriteria (Atribut) | Tipe Kriteria | Bobot Dasar | Deskripsi |
| :--- | :--- | :--- | :--- |
| **C1: Budget Fit** | *Target Match* | 31.58% (6/19) | Kecocokan harga jam tangan terhadap batas anggaran pengguna. |
| **C2: Gender** | *Benefit* | 21.05% (4/19) | Kecocokan peruntukan jam tangan (Men/Women/Unisex) dengan pilihan. |
| **C3: Case Material** | *Benefit* | 15.79% (3/19) | Kecocokan material *case* dengan preferensi pelanggan. |
| **C4: Movement** | *Benefit* | 15.79% (3/19) | Kecocokan mesin jam (*Automatic*/*Quartz*). |
| **C5: Strap Match** | *Benefit* | 15.79% (3/19) | Kecocokan jenis strap/tali jam dengan preferensi. |

> Jika semua kriteria diisi, total bobot adalah 1.0 (100%).
> **Normalisasi Dinamis:** Jika pengguna mengosongkan suatu kriteria (misal: tidak memilih *Strap*), maka kriteria tersebut tidak dihitung (tidak mendapat utilitas 1 secara otomatis). Sistem kemudian membagi bobot dasar dari kriteria yang aktif dengan total bobot aktif agar kembali menjadi 1.0.

## 3. Evaluasi Kriteria dan Normalisasi (Utility)
Setiap kriteria aktif untuk masing-masing alternatif (produk) diubah menjadi nilai utilitas ($U$) dengan rentang $0$ hingga $1$.

### A. Kriteria Harga (Target Match - C1)
Tujuan fungsi harga adalah mencari harga yang paling mendekati batas anggaran dari bawah. Hanya produk dengan harga $\le Budget$ yang menjadi kandidat utama.
$U_{budget} = \frac{Price}{Budget}$
(Semakin mendekati batas budget, utilitasnya semakin mendekati 1).

### B. Kriteria Gender (Matriks Kompatibilitas - C2)
Pencocokan gender menggunakan aturan matriks, bukan perbandingan *string* murni.
- Pengguna **Men**: produk Men/Unisex $\rightarrow U = 1$, lainnya $0$.
- Pengguna **Women**: produk Women/Unisex $\rightarrow U = 1$, lainnya $0$.
- Pengguna **Unisex**: produk Unisex $\rightarrow U = 1$, lainnya $0$.

### C. Kriteria Material, Movement, dan Strap (Benefit Biner - C3, C4, C5)
Kriteria *Material*, *Movement*, dan *Strap* bernilai biner.
- Jika pengguna **tidak memilih**, kriteria ini diabaikan (tidak menyumbang skor).
- Jika pengguna **memilih** parameter, sistem akan mencocokkan *string* (pencarian *case-insensitive*):
  - Jika **Cocok** $\rightarrow U = 1$
  - Jika **Tidak Cocok** $\rightarrow U = 0$

## 4. Perhitungan Nilai Akhir (Final Score)
Nilai akhir (*SMART Score*) setiap produk merupakan hasil penjumlahan dari perkalian antara nilai utilitas ($U$) dengan bobot dinamis ($W'$). Skor ini direntang ke 0 - 100.

$SMART Score = 100 \times \sum (W'_j \times U_j)$

Produk kemudian diurutkan berdasarkan:
1. Skor SMART tertinggi.
2. Jumlah kecocokan kategori (kriteria biner) terbanyak.
3. Selisih harga absolut terhadap budget terkecil.
4. ID produk (sebagai penentu urutan yang stabil).

## 5. Keputusan dan Fallback Terkontrol
- Sistem akan mengembalikan **Top 3** produk dengan urutan terbaik.
- **Fallback Mechanism:** Jika jumlah kandidat utama (yang sesuai budget) kurang dari 3, sistem mencari produk di luar budget namun masih dalam batas **toleransi 20%** ($t = 0.2$).
- Untuk produk fallback, dihitung utilitas budget penalti: 
  $U_{budget}^{fallback} = \max\left(0, 1 - \frac{Price - Budget}{t \times Budget}\right)$
- Produk fallback akan selalu ditambahkan di urutan akhir setelah kandidat utama, tanpa menggusur mereka, dan ditandai dengan label "OVER BUDGET".

---

# Bagian II: SPK Penentuan Prioritas Restock Inventory

Selain pada fitur *Smart Advisor*, metode **SMART** juga diterapkan pada modul Admin Inventory untuk menentukan prioritas *restock* (pengadaan barang) secara otomatis. Hal ini membantu admin mengambil keputusan produk mana yang harus segera disuplai ulang agar penjualan dan keuntungan maksimal.

## 1. Kriteria dan Pembobotan (Weights)
Terdapat 4 kriteria utama dalam menentukan prioritas *restock*. Bobot total direpresentasikan dalam bentuk persentase (100%).

| Kriteria (Atribut) | Tipe Kriteria | Bobot ($W_j$) | Deskripsi |
| :--- | :--- | :--- | :--- |
| **C1: Sisa Stok (Stock)** | *Cost* | 35% | Jumlah persediaan barang saat ini. Semakin sedikit sisa stok, semakin butuh di-*restock*. |
| **C2: Sales Velocity** | *Benefit* | 30% | Kecepatan penjualan (jumlah barang terjual dalam 30 hari terakhir). |
| **C3: Popularity** | *Benefit* | 20% | Jumlah *Page Views* dan *Add to Cart* produk tersebut dalam 30 hari terakhir. |
| **C4: Profit Margin** | *Benefit* | 15% | Persentase margin keuntungan ((Price - COGS) / Price). Semakin besar untung, semakin prioritas. |

> Total Bobot = 35 + 30 + 20 + 15 = 100

## 2. Evaluasi Kriteria dan Normalisasi (Utility)
Nilai utilitas ($U_i$) dihitung dengan rentang $0$ hingga $1$ untuk setiap kriteria berdasarkan nilai Minimum dan Maksimum secara global di dalam *database*.

### A. Kriteria Stok (Cost - C1)
Karena semakin kecil stok maka kebutuhannya semakin tinggi (skor semakin tinggi), perhitungannya adalah:
$U_{stock} = \frac{MaxStock - Stock}{MaxStock - MinStock}$

### B. Kriteria Velocity, Popularity, dan Margin (Benefit - C2, C3, C4)
Ketiga kriteria ini berjenis *Benefit*, semakin tinggi nilainya semakin baik.
$U_{benefit} = \frac{Value - MinValue}{MaxValue - MinValue}$

## 3. Perhitungan Nilai Akhir (Final Score)
Nilai utilitas setiap kriteria dikalikan dengan persentase bobot, dan dijumlahkan untuk mendapatkan skor dari 0 - 100.

$Final Score = (U_{C1} \times 35) + (U_{C2} \times 30) + (U_{C3} \times 20) + (U_{C4} \times 15)$

## 4. Keputusan Level Prioritas
Berdasarkan *Final Score* yang didapat, sistem akan otomatis mengelompokkan produk ke dalam tiga level prioritas:
- **High Priority:** Jika *Final Score* > 75
- **Medium Priority:** Jika *Final Score* $\ge$ 40 dan $\le$ 75
- **Low Priority:** Jika *Final Score* < 40

---
*Dokumen ini digenerate secara otomatis untuk mempermudah dokumentasi arsitektur Sistem Pendukung Keputusan dalam Repositori Clementine.*
