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
*Dokumen ini digenerate secara otomatis untuk mempermudah dokumentasi arsitektur Sistem Pendukung Keputusan dalam Repositori Clementine.*
