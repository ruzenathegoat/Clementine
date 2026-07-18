Table : 

id
user_id
ip_address
country
city
region
latitude
longitude
user_agent
browser
platform
device
is_verified
created_at

Alur : 
User login
        │
        ▼
Password benar
        │
        ▼
Ambil IP
        │
        ▼
Ambil lokasi (stevebauman/location)
        │
        ▼
Ambil User Agent
        │
        ▼
Bandingkan dengan login terakhir
        │
        ├── Tidak mencurigakan
        │       │
        │       └── Login berhasil
        │
        └── Mencurigakan
                │
                ▼
        Generate token verifikasi
                │
                ▼
        Kirim email
                │
                ▼
User klik link verifikasi
                │
                ▼
Status login diaktifkan


| No | Skenario                        | Hasil yang Diharapkan               |
| -- | ------------------------------- | ----------------------------------- |
| 1  | Login dari IP yang sama         | Login berhasil tanpa verifikasi     |
| 2  | Login dari IP baru              | Email verifikasi dikirim            |
| 3  | Login dari negara berbeda (VPN) | Email verifikasi dikirim            |
| 4  | Login dari browser berbeda      | Sistem mencatat perubahan browser   |
| 5  | Login dari perangkat berbeda    | Sistem mencatat perubahan perangkat |
| 6  | Token verifikasi valid          | Login diizinkan                     |
| 7  | Token kedaluwarsa               | Login ditolak                       |
| 8  | Token digunakan dua kali        | Penggunaan kedua ditolak            |


Risk Score :
| Kondisi         | Skor |
| --------------- | ---: |
| Device baru     |  +50 |
| Browser berubah |  +15 |
| OS berubah      |  +20 |
| Negara berubah  |  +50 |
| Kota berubah    |  +20 |
| IP berubah      |  +10 |
