# Sistem Pendukung Keputusan (SPK) Menggunakan Metode SMART

Dokumen ini menjelaskan rancangan Sistem Pendukung Keputusan (SPK) menggunakan metode **SMART (Simple Multi-Attribute Rating Technique)** yang diterapkan pada fitur *Smart Advisor* di proyek Clementine.

## 1. Pendahuluan
Metode SMART digunakan di *Smart Advisor* untuk merekomendasikan jam tangan (produk) terbaik bagi pelanggan berdasarkan berbagai parameter pencarian (seperti *Budget*, *Gender*, *Case Material*, *Movement*, dan *Strap*). SMART adalah metode pengambilan keputusan multikriteria yang sederhana dan efisien, di mana setiap kriteria diberikan bobot dan nilai utilitasnya dihitung untuk setiap alternatif.

## 2. Kriteria dan Pembobotan (Weights)
Terdapat 6 kriteria utama yang menjadi acuan penilaian algoritma ini. Bobot total dari semua kriteria adalah 1.0 (atau 100%).

| Kriteria (Atribut) | Tipe Kriteria | Bobot ($W_j$) | Deskripsi |
| :--- | :--- | :--- | :--- |
| **C1: Price / Budget** | *Cost* | 0.30 (30%) | Seberapa sesuai harga jam tangan dengan budget pelanggan. |
| **C2: Gender** | *Benefit* | 0.20 (20%) | Kecocokan peruntukan jam tangan (Men/Women/Unisex) dengan pilihan. |
| **C3: Case Material** | *Benefit* | 0.15 (15%) | Kecocokan material *case* dengan preferensi pelanggan. |
| **C4: Movement** | *Benefit* | 0.15 (15%) | Kecocokan mesin jam (*Automatic*/*Quartz*). |
| **C5: Strap Match** | *Benefit* | 0.15 (15%) | Kecocokan jenis strap/tali jam dengan preferensi. |
| **C6: Stock** | *Benefit* | 0.05 (5%) | Ketersediaan jumlah stok di *inventory* (semakin banyak semakin baik). |

> Total Bobot = 0.30 + 0.20 + 0.15 + 0.15 + 0.15 + 0.05 = 1.0

## 3. Evaluasi Kriteria dan Normalisasi (Utility)
Setiap kriteria untuk masing-masing alternatif (produk) diubah menjadi nilai utilitas ($U_i$) dengan rentang $0$ hingga $1$.

### A. Kriteria Harga (Cost - C1)
Harga merupakan kriteria *Cost*. Tujuan normalisasinya adalah mencari harga yang paling mendekati dari bawah (tidak melebihi budget).
- **Jika harga produk melampaui budget:**
  $U_{price} = \max\left(0, \frac{Budget - (Price - Budget)}{MaxPrice}\right)$
  (Diberikan penalti berat sehingga nilainya turun drastis jika melebihi batas).
- **Jika harga produk berada di bawah/sama dengan budget:**
  $U_{price} = \frac{MaxPrice - Price}{MaxPrice - MinPrice}$
  (Semakin murah atau semakin mendekati budget dari bawah tanpa melewatinya, semakin tinggi nilainya).

### B. Kriteria Kecocokan (Benefit Biner - C2, C3, C4, C5)
Kriteria *Gender*, *Material*, *Movement*, dan *Strap* bernilai biner.
- Jika pengguna **tidak memilih/kosong** pada form, maka $U = 1$ (dianggap cocok semua).
- Jika pengguna **memilih** parameter, sistem akan mencocokkan *string* (pencarian *case-insensitive*):
  - Jika **Cocok** $\rightarrow U = 1$
  - Jika **Tidak Cocok** $\rightarrow U = 0$

### C. Kriteria Stok (Benefit - C6)
Stok merupakan kriteria *Benefit* kuantitatif. Semakin banyak stok yang ada, semakin tinggi nilai utilitasnya.
$U_{stock} = \frac{Stock - MinStock}{MaxStock - MinStock}$

## 4. Perhitungan Nilai Akhir (Final Score)
Nilai akhir (*Smart Score*) setiap produk merupakan hasil penjumlahan dari perkalian antara nilai utilitas ($U$) dengan bobot kriteria ($W$).

$Final Score = (U_{C1} \times 0.30) + (U_{C2} \times 0.20) + (U_{C3} \times 0.15) + (U_{C4} \times 0.15) + (U_{C5} \times 0.15) + (U_{C6} \times 0.05)$

## 5. Keputusan dan Fallback
- Produk-produk kemudian diurutkan dari *Final Score* tertinggi ke terendah.
- Sistem akan mengembalikan **Top 3** produk dengan skor tertinggi untuk direkomendasikan kepada pengguna.
- **Fallback Mechanism:** Jika hasil tertinggi memiliki skor di bawah 30% ($0.3$), sistem berasumsi algoritma tidak menemukan kecocokan yang layak. Sebagai *fallback*, sistem otomatis mengembalikan 3 produk *best seller* (atau yang harganya paling mendekati batas budget dari bawah) terlepas dari kriteria spesifik lainnya.

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
