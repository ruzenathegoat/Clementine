# Clementine Admin — CMS & Analytical Dashboard
### Product Requirements Document (PRD) — v1 (Expanded)

**Author:** Senior Product Manager
**Date:** 13 Juli 2026
**Status:** Draft v1 — Expanded dari Brief
**Relasi:** Dokumen terpisah dari `PRD_Clementine_v2.md` (customer-facing), sesuai keputusan
Section 8 PRD v2 bahwa Admin Dashboard adalah scope tersendiri dengan persona internal, bukan
customer-facing.

---

## 1. Executive Summary

Kami membangun **Clementine Admin**, pusat kendali operasional internal untuk mengelola katalog,
transaksi, keanggotaan, dan kesehatan finansial Clementine — digunakan oleh 4 tim internal
(Inventory, Ops/Fulfillment, Customer Success, Finance/Management) yang saat ini kemungkinan
besar masih bergantung pada proses manual/spreadsheet untuk merilis produk, memproses pesanan,
mengelola status VIP, dan merekonsiliasi laporan keuangan.

Berbeda total dari frontend Clementine (yang sengaja bold-editorial/brutalist untuk Gen Z), admin
dashboard ini mengutamakan **densitas data, kejelasan, dan efisiensi** — komponen standar
Flowbite/DaisyUI, bukan sistem desain streetwear. Keberhasilannya diukur dari seberapa cepat tim
ops memproses pesanan, seberapa akurat laporan keuangan yang dihasilkan, dan seberapa ketat RBAC
mencegah kebocoran data finansial ke role yang tidak berwenang.

---

## 2. Problem Statement

### Siapa yang punya masalah ini?
Empat tim internal yang menjalankan operasional Clementine sehari-hari:
1. **Inventory Manager** — perlu menyiapkan dan menjadwalkan rilis produk (termasuk drop Limited
   Edition) tanpa alat yang terintegrasi dengan katalog live di frontend.
2. **Ops/Fulfillment Staff** — perlu memproses pesanan bernilai tinggi (jam tangan premium,
   butuh asuransi pengiriman) secara akurat dan cepat, tanpa visibilitas status pembayaran yang
   jelas saat ini berarti risiko salah kirim atau salah proses pesanan yang belum lunas.
3. **Customer Success** — perlu mengelola status VIP pelanggan secara manual berdasarkan
   penilaian, tapi tanpa dashboard terpusat mereka tidak punya visibilitas riwayat pembelian yang
   memudahkan keputusan itu.
4. **Finance/Management** — perlu rekonsiliasi HPP, laba/rugi, dan cashflow, yang saat ini
   kemungkinan dikerjakan manual dari data mentah — rawan human error dan lambat untuk keputusan
   real-time.

### Apa masalahnya?
Tanpa alat internal terpusat, empat fungsi ini berjalan dengan alat terpisah-pisah (spreadsheet,
akses database langsung, atau komunikasi manual antar-tim) — menyebabkan keterlambatan proses
pesanan, risiko kesalahan data produk yang tayang ke publik sebelum waktunya, dan laporan
keuangan yang tidak real-time.

### Kenapa ini penting?
- **Dampak ke operasional:** Keterlambatan update status pesanan → pelanggan (termasuk Heritage
  Collector yang sensitif ke trust) tidak menerima notifikasi pengiriman tepat waktu untuk barang
  bernilai tinggi.
- **Dampak ke bisnis:** Tanpa dashboard finansial real-time, Management tidak bisa merespons cepat
  terhadap tren margin yang menurun atau masalah cashflow — keputusan bisnis jadi reaktif, bukan
  proaktif.
- **Dampak ke keamanan data:** Tanpa RBAC yang jelas, staf Ops berpotensi mengakses data
  Laba/Rugi yang sensitif — risiko kebocoran informasi finansial internal.

### Evidence
> 📊 **DATA:** Industri e-commerce secara umum menargetkan 24–48 jam dari order placement ke shipment handoff sebagai standar industri, dengan standar top-performer di angka 95% on-time delivery rate dan retailer menengah biasanya menargetkan 90-95% on-time performance. Ini jadi acuan realistis untuk metrik Ops Staff di Section 6 — bukan angka yang kita karang sendiri.

---

## 3. Target Users & Personas

### Persona 1 — Inventory Manager
| Atribut | Detail |
|---|---|
| Tanggung jawab | Mengelola stok, detail produk (Movement, Material, Diameter), menjadwalkan drop Limited Edition |
| Kebutuhan utama | Form CRUD produk yang cepat diisi, kontrol status (Draft/Published/Out of Stock), penjadwalan rilis |
| Jobs-to-be-Done | "Ketika saya menyiapkan drop Limited Edition minggu depan, saya ingin menyiapkan halaman produknya dari sekarang dalam status Draft, agar begitu waktu rilis tiba saya tinggal ubah ke Published tanpa terburu-buru." |

### Persona 2 — Ops/Fulfillment Staff
| Atribut | Detail |
|---|---|
| Tanggung jawab | Memproses pesanan, verifikasi pembayaran, update resi pengiriman (termasuk asuransi barang mewah) |
| Kebutuhan utama | Daftar pesanan dengan status pembayaran jelas (Pending/Paid/Failed), update status cepat dengan input resi |
| Jobs-to-be-Done | "Ketika saya mulai shift pagi, saya ingin langsung melihat pesanan mana yang sudah Paid dan siap dikirim, agar saya tidak salah memproses pesanan yang belum lunas." |

### Persona 3 — Customer Success
| Atribut | Detail |
|---|---|
| Tanggung jawab | Mengelola profil pelanggan, meninjau riwayat transaksi, mengatur status VIP |
| Kebutuhan utama | Daftar pengguna dengan riwayat pembelian terlihat jelas, toggle status VIP manual |
| Jobs-to-be-Done | "Ketika pelanggan sudah 3x membeli di atas $1000, saya ingin bisa langsung menandai mereka VIP dari satu tempat, agar mereka segera dapat akses eksklusif tanpa menunggu proses manual berhari-hari." |

### Persona 4 — Finance/Management
| Atribut | Detail |
|---|---|
| Tanggung jawab | Memantau performa penjualan, menganalisis Laba/Rugi, mengekspor laporan untuk rekonsiliasi eksternal |
| Kebutuhan utama | Dashboard visual Gross Profit/Net Profit per bulan, ringkasan cashflow, filter+export laporan .xls |
| Jobs-to-be-Done | "Ketika saya tutup buku bulanan, saya ingin mengekspor laporan penjualan dalam rentang tanggal tertentu ke Excel dengan satu klik, agar tim akuntansi eksternal bisa langsung rekonsiliasi tanpa saya rekap manual." |

🔵 **Catatan RBAC (lihat Section 10):** Brief awal hanya menyebut contoh "Ops tidak bisa lihat
menu Laba/Rugi milik Finance" — dengan 4 persona di atas, kita butuh **matriks RBAC eksplisit**
per modul (Inventory, Order, User/VIP, Financial Analytics), bukan cuma satu contoh kasus.

---

## 4. Strategic Context

### Positioning
Clementine Admin adalah **internal ops tool**, bukan produk yang bersaing di pasar — jadi
"kompetitor" di sini lebih relevan sebagai **acuan pola desain** dibanding pesaing bisnis:
Shopify Admin, Linear, dan Retool adalah acuan umum untuk dashboard data-dense dengan RBAC dan
data table yang solid.

### Mengapa dibangun in-house (bukan pakai Retool/Airtable off-the-shelf)?
Karena kalkulasi finansial (HPP, Laba/Rugi) di Section 4 brief butuh logika kustom yang terikat
erat ke skema data Supabase yang sama dengan frontend customer-facing — solusi off-the-shelf akan
butuh integrasi tambahan yang lebih rumit dibanding membangun modul backend Laravel langsung di
atas skema yang sudah ada, sekaligus tim engineering (Firman, Asrap) sudah memiliki konteks
penuh atas skema tersebut dari pengerjaan PRD v2 customer-facing.

### Prinsip Desain
> *"Menggunakan komponen standar tanpa harus mengikuti gaya brutalist ketat."* — ini keputusan
> yang benar. Admin dashboard MEMANG harus terasa berbeda dari frontend: prioritas adalah scan-
> ability data dalam tabel padat, bukan ekspresi brand. Rekomendasi: pertahankan sedikit benang
> merah brand (mis. wordmark kecil "CLEMENTINE" di sidebar, warna ink #0A0A0A untuk sidebar/nav)
> tapi biarkan seluruh area kerja (tabel, form, chart) memakai default Flowbite/DaisyUI tanpa
> modifikasi radius/shadow yang ketat seperti di frontend.

---

## 5. Solution Overview

### Epic 1 — Inventory & Drops Management
- CRUD produk dengan field spesifikasi (Movement, Material, Diameter)
- Status produk: Draft → Published → Out of Stock, dengan penjadwalan rilis (drop scheduling)
- Upload gambar langsung ke Supabase Storage bucket

### Epic 2 — Order & Transaction Processing
- Daftar pesanan dengan filter status pembayaran (Pending/Paid/Failed)
- Update status pesanan → "Shipped" + input nomor resi → trigger notifikasi otomatis ke pelanggan

### Epic 3 — User & VIP Management
- Daftar pengguna terdaftar + riwayat pembelian
- Toggle status VIP manual per user

### Epic 4 — Financial Analytics & Reporting
- Dashboard visual tren Gross Profit & Net Profit per bulan
- Ringkasan Cashflow harian/mingguan
- Filter laporan penjualan by date range + export ke .xls/.xlsx (kalkulasi HPP otomatis)

### Technical Architecture
| Layer | Keputusan | PIC | Catatan |
|---|---|---|---|
| Backend, API, Report Generation | PHP + Laravel, Laravel Excel untuk .xls | Firman | Kalkulasi finansial kompleks (HPP, Laba/Rugi) di-handle server-side, bukan client-side, untuk akurasi & auditability |
| Database, Storage, RBAC | Supabase — Row Level Security khusus admin/finance | Asrap | RLS harus didefinisikan per modul (lihat Section 10 untuk matriks RBAC yang perlu difinalisasi) |
| UI/UX Design | Komponen standar (non-brutalist), fokus densitas data | Rijal | Lihat prinsip desain di Section 4 |
| Frontend Implementation | Tailwind CSS + Flowbite + Alpine.js | Shandika & Kemal | Fokus performa render ribuan baris — lihat rekomendasi virtualisasi di Section 9 |
| Analytics Charts | Highcharts atau ApexCharts | Shandika & Kemal | Perlu keputusan final salah satu (lihat Open Question) |

---

## 6. Success Metrics

| Metrik | Target | Alasan |
|---|---|---|
| Waktu proses pesanan (order placed → status Shipped diperbarui) | ≤24 jam untuk 90% pesanan | Selaras standar industri 24–48 jam; ditarik ke batas bawah karena barang bernilai tinggi butuh kepercayaan ekstra dari Heritage Collector |
| On-time status update rate (resi dimasukkan sebelum SLA) | ≥95% | Sejalan benchmark top-performer 95%+ on-time |
| Akurasi kalkulasi HPP/Laba-Rugi otomatis vs rekonsiliasi manual | 100% match, 0 selisih pada audit bulanan | Ini bukan metrik "cukup baik" — kalkulasi finansial yang salah berdampak langsung ke laporan eksternal |
| Waktu publish produk baru (draft → published) | <10 menit untuk 1 SKU lengkap | Mengukur seberapa efisien form CRUD Inventory Manager |
| Waktu generate laporan .xls (rentang 1 bulan transaksi) | <10 detik | Mengukur performa Data Export Engine di skenario data real |
| Waktu render tabel data (1,000+ baris transaksi) | <2 detik initial render, scroll tanpa jank | Menjawab requirement "fokus performa render ribuan baris" secara terukur |
| Insiden akses tidak sah ke modul Finance oleh role Ops | 0 insiden | Metrik keberhasilan RBAC — bukan "seberapa jarang", tapi nol |

---

## 7. User Stories & Requirements

### User Story 001 — Menambahkan produk baru dengan spesifikasi lengkap
**As a** Inventory Manager
**I want to** menambahkan jam tangan baru beserta spesifikasi (Movement, Material, Diameter) dan
gambar produk
**so that** produk siap ditampilkan di frontend tanpa perlu bantuan engineering

**Acceptance Criteria:**
- **Scenario:** Menyimpan produk baru sebagai Draft
- **Given** saya login sebagai Inventory Manager dan berada di halaman "Add Product"
- **And Given** saya sudah mengisi seluruh field wajib (nama, harga, Movement, Material, Diameter) dan mengunggah minimal 1 gambar
- **When** saya klik "Save as Draft"
- **Then** produk tersimpan dengan status "Draft", gambar ter-upload ke Supabase Storage bucket, dan produk TIDAK muncul di katalog frontend publik

---

### User Story 002 — Menjadwalkan status produk untuk drop Limited Edition
**As a** Inventory Manager
**I want to** mengatur status produk dari Draft ke Published pada waktu yang saya tentukan
**so that** saya bisa menyiapkan halaman produk Limited Edition jauh-jauh hari tanpa buru-buru
saat waktu drop tiba

**Acceptance Criteria:**
- **Scenario:** Produk otomatis tayang sesuai jadwal
- **Given** saya mengatur sebuah produk berstatus "Draft" dengan `scheduled_publish_at` di masa depan
- **When** waktu sistem mencapai `scheduled_publish_at`
- **Then** status produk otomatis berubah menjadi "Published" tanpa perlu aksi manual, dan produk langsung muncul di katalog frontend

---

### User Story 003 — Memproses pesanan berdasarkan status pembayaran
**As a** Ops/Fulfillment Staff
**I want to** melihat daftar pesanan dengan status pembayarannya (Pending, Paid, Failed)
**so that** saya tahu pesanan mana yang harus diproses hari ini tanpa salah kirim barang yang
belum lunas

**Acceptance Criteria:**
- **Scenario:** Filter daftar pesanan berdasarkan status
- **Given** saya berada di halaman "Orders" dengan campuran pesanan Pending/Paid/Failed
- **When** saya memilih filter status "Paid"
- **Then** tabel hanya menampilkan pesanan dengan status Paid, diurutkan dari yang paling lama menunggu diproses

---

### User Story 004 — Update status pengiriman dan notifikasi otomatis
**As a** Ops/Fulfillment Staff
**I want to** memperbarui status pesanan menjadi "Shipped" dan memasukkan nomor resi
**so that** pelanggan otomatis menerima notifikasi pengiriman tanpa saya harus menghubungi manual

**Acceptance Criteria:**
- **Scenario:** Update status memicu notifikasi pelanggan
- **Given** saya membuka detail pesanan berstatus "Paid"
- **And Given** saya memasukkan nomor resi yang valid pada field "Tracking Number"
- **When** saya klik "Mark as Shipped"
- **Then** status pesanan berubah menjadi "Shipped", dan sistem mengirim notifikasi (email/in-app) ke pelanggan berisi nomor resi tersebut dalam waktu <1 menit

---

### User Story 005 — Meninjau riwayat pembelian pelanggan
**As a** Customer Success
**I want to** melihat daftar pengguna terdaftar beserta riwayat pembelian mereka
**so that** saya punya dasar objektif saat menentukan siapa yang layak menjadi VIP

**Acceptance Criteria:**
- **Scenario:** Melihat detail riwayat transaksi satu pengguna
- **Given** saya mencari nama/email pengguna di halaman "Users"
- **When** saya klik ke profil pengguna tersebut
- **Then** saya melihat total belanja, jumlah transaksi, dan daftar pesanan individual pengguna tersebut dalam satu halaman

---

### User Story 006 — Mengubah status pengguna menjadi VIP
**As a** Customer Success
**I want to** mengubah status seorang pengguna menjadi "VIP" secara manual
**so that** mereka segera mendapat akses eksklusif (early-bird Limited Edition, sesuai PRD
customer-facing User Story 003)

**Acceptance Criteria:**
- **Scenario:** Toggle status VIP tersimpan dan berlaku instan
- **Given** saya berada di halaman profil seorang pengguna berstatus "Reguler"
- **When** saya mengaktifkan toggle "VIP Status" dan konfirmasi
- **Then** status pengguna berubah menjadi "VIP" secara real-time, dan pada login berikutnya pengguna tersebut langsung mendapat akses early-bird di frontend (terhubung dengan PRD v2 customer-facing User Story 003)

---

### User Story 007 — Dashboard visual tren Laba Kotor & Net Profit
**As a** Finance Manager
**I want to** melihat dashboard visual yang menampilkan tren Gross Profit dan Net Profit per bulan
**so that** saya mengetahui kesehatan bisnis secara real-time tanpa menunggu laporan manual

**Acceptance Criteria:**
- **Scenario:** Grafik tren memuat data 12 bulan terakhir
- **Given** saya login sebagai Finance Manager dan membuka halaman "Financial Analytics"
- **When** halaman selesai dimuat
- **Then** saya melihat chart garis (Highcharts/ApexCharts) menampilkan Gross Profit dan Net Profit per bulan untuk 12 bulan terakhir, dengan angka HPP dihitung otomatis dari data transaksi backend — bukan input manual

---

### User Story 008 — Filter dan ekspor laporan penjualan ke Excel
**As a** Finance Manager
**I want to** memfilter laporan penjualan berdasarkan rentang waktu tertentu dan mengekspornya ke
.xls
**so that** tim akuntansi eksternal bisa melakukan audit dan rekonsiliasi tanpa saya rekap manual

**Acceptance Criteria:**
- **Scenario:** Export laporan sesuai rentang tanggal yang dipilih
- **Given** saya memilih rentang tanggal "1 Juni 2026 – 30 Juni 2026" di halaman "Reports"
- **When** saya klik "Export to Excel"
- **Then** sistem men-generate file .xlsx berisi seluruh transaksi dalam rentang tersebut (termasuk kolom HPP dan pendapatan bersih terhitung) dalam waktu <10 detik, dan file otomatis terunduh

---

### User Story 009 — Ringkasan cashflow untuk Management *(baru, dari brief halaman 3)*
**As a** Management
**I want to** melihat ringkasan Cashflow harian/mingguan
**so that** saya memastikan ketersediaan likuiditas perusahaan tanpa menunggu laporan bulanan

**Acceptance Criteria:**
- **Scenario:** Ringkasan cashflow mingguan ditampilkan sebagai default view
- **Given** saya membuka dashboard utama sebagai role Management
- **When** halaman dimuat
- **Then** saya melihat ringkasan cashflow masuk vs keluar untuk 7 hari terakhir sebagai default, dengan opsi toggle ke tampilan harian

---

### User Story 010 — Penegakan RBAC antar-role *(baru, turunan Functional Requirement RBAC)*
**As a** system
**I want to** membatasi akses menu Financial Analytics hanya untuk role Finance/Management
**so that** Ops Staff dan Inventory Manager tidak bisa melihat data Laba/Rugi yang sensitif

**Acceptance Criteria:**
- **Scenario:** Ops Staff mencoba mengakses URL Financial Analytics secara langsung
- **Given** saya login sebagai role "Ops Staff"
- **When** saya mengetik/navigasi langsung ke URL halaman "/admin/financial-analytics"
- **Then** saya menerima halaman 403 Forbidden (bukan cuma menu yang disembunyikan di sidebar — proteksi harus di level route/backend, bukan cuma UI)

---

## 8. Out of Scope

**Tidak termasuk di rilis v1 ini:**
- Manajemen multi-gudang/multi-lokasi inventaris (brief tidak menyebutkan ini — asumsi saat ini
  Clementine beroperasi dari satu lokasi fulfillment)
- Aplikasi mobile khusus admin — dashboard ini **desktop-first**, tidak dioptimalkan untuk
  smartphone (berbeda dari frontend customer-facing yang mobile-first) karena kepadatan data
  tabel/chart secara natural butuh layar lebar
- Fitur CRM otomatis (email marketing, segmentasi otomatis) — Customer Success saat ini hanya
  melihat & mengubah status manual, bukan otomasi campaign
- Integrasi asisten Live Concierge Chat (mis. "assign representative", live chat inbox untuk staf)
  — ini disebut di PRD v2 customer-facing tapi belum ada requirement admin-side untuk
  mengelolanya; perlu PRD/epic tambahan kalau staf butuh interface untuk menjawab chat
- Audit log/histori perubahan data secara detail (siapa mengubah apa, kapan) — belum disebut di
  brief, tapi sangat direkomendasikan untuk modul finansial (lihat Open Questions)

---

## 9. Dependencies & Risks

### Dependencies
- **Skema data bersama dengan PRD v2 customer-facing:** tabel produk, order, dan user harus
  dirancang selaras antara frontend dan admin sejak awal — perubahan skema di satu sisi
  berdampak langsung ke sisi lain. Perlu satu sumber kebenaran skema (single migration set),
  bukan dua PRD yang mendefinisikan tabel secara independen.
- **Laravel Excel package:** ketersediaan dan kompatibilitas versi paket ini menentukan
  kelayakan Data Export Engine — perlu dicek kompatibilitasnya dengan versi Laravel yang dipakai.
- **Row Level Security (RLS) Supabase:** matriks RBAC (Section 10) harus final sebelum RLS policy
  ditulis — menulis RLS sebelum matriks final berisiko harus dirombak ulang.

### Risks & Mitigations
| Risk | Dampak | Mitigasi |
|---|---|---|
| Kalkulasi HPP/Laba-Rugi otomatis salah karena logika bisnis kompleks (diskon, ongkir, retur) tidak sepenuhnya ditangkap di brief | Laporan keuangan yang diekspor ke akuntan eksternal tidak akurat — risiko rekonsiliasi gagal | Sesi requirement gathering khusus dengan Finance untuk memetakan SEMUA komponen HPP sebelum development kalkulasi dimulai, bukan diasumsikan sederhana |
| Render tabel ribuan baris transaksi menyebabkan halaman lambat/freeze | Ops Staff dan Finance tidak bisa bekerja efisien, defeat tujuan utama tool ini | Implementasi server-side pagination + virtualisasi tabel (bukan render semua baris di client), sudah diantisipasi brief tapi perlu jadi requirement eksplisit, bukan catatan performa umum |
| RBAC hanya diterapkan di level UI (sembunyikan menu) tapi tidak di level API/route | Staf teknis-savvy bisa akses data sensitif lewat URL langsung atau API call | Wajib proteksi di level backend route (lihat User Story 010) — RBAC di UI hanya untuk UX, bukan satu-satunya lapisan keamanan |
| Satu PIC per fungsi teknis (Firman untuk backend, Asrap untuk Supabase, dst.) — bus factor tinggi | Kalau salah satu PIC tidak tersedia, development terhambat signifikan | Dokumentasikan keputusan arsitektur & skema database secara tertulis (bukan tacit knowledge), supaya tidak sepenuhnya bergantung pada satu orang |
| Data export finansial (.xlsx) berisi data sensitif tapi belum ada requirement soal siapa yang bisa export dan bagaimana file itu diamankan setelah diunduh | Kebocoran data finansial lewat file yang ter-download dan tersebar | Batasi tombol export hanya untuk role Finance/Management (bagian dari matriks RBAC), pertimbangkan watermark/log setiap kali export dilakukan |

---

## 10. Open Questions

- 🔵 **Matriks RBAC lengkap belum final.** Brief hanya memberi 1 contoh ("Ops tidak bisa lihat
  Laba/Rugi"). Perlu didefinisikan eksplisit: modul apa saja (Inventory, Orders, Users/VIP,
  Financial Analytics) × role apa saja (Inventory Manager, Ops Staff, Customer Success,
  Finance/Management, dan kemungkinan Super Admin) — siapa punya akses **read/write/none** di
  masing-masing modul.
- 🔵 **Apakah admin dashboard pakai halaman Login yang sama dengan customer-facing?** Prompt
  Stitch Login/Register/Forgot Password di `STITCH_PROMPTS_FULL_FLOW.md` sudah mencatat asumsi
  ini sebagai open question dari sisi customer-facing — sekarang saatnya diputuskan: (a) login
  terpisah dengan domain/path berbeda (mis. `/admin/login`) dan desain non-brutalist sesuai
  prinsip Section 4, atau (b) satu sistem auth dengan role check setelah login. Rekomendasi:
  opsi (a), supaya tidak ada risiko customer biasa "menemukan" pintu masuk admin secara tidak
  sengaja.
- 🔵 **Highcharts atau ApexCharts?** Brief menyebut keduanya sebagai opsi ("seperti Highcharts
  atau ApexCharts") tapi belum ada keputusan final — ini berdampak ke lisensi (Highcharts
  berbayar untuk penggunaan komersial, ApexCharts open-source) dan harus diputuskan sebelum
  development chart dimulai.
- 🔵 **Apakah dibutuhkan audit log untuk perubahan data finansial dan status VIP?** Tidak
  disebutkan di brief, tapi mengingat ini data finansial dan status keanggotaan yang berdampak
  ke pengalaman pelanggan, praktik umum internal tool serupa selalu mencatat "siapa mengubah apa,
  kapan" minimal untuk modul Financial Analytics dan User/VIP Management.
- 🔵 **Definisi lengkap komponen HPP** (harga pokok termasuk ongkos kirim? biaya kemasan
  asuransi? diskon promo?) — perlu divalidasi dengan tim Finance sebelum kalkulasi otomatis
  dibangun (lihat Section 9, Risk #1).

---

*Clementine Admin PRD v1 © 2026. Strictly Confidential.*