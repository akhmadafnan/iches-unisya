# 17 — Admin-Centered CRUD dan Pengaturan Seluruh ICHES 2027

> **Keputusan final, 27 Oktober 2026:** semua aspek **International Conference** dan rangkaian kegiatan lain dikelola melalui Backoffice **Admin** yang menyediakan CRUD sesuai kebutuhan, supaya panitia dapat mengubah pengaturan tanpa meminta developer atau deploy kode. **Batas akhir pendaftaran Paket Program Internasional harus dapat ditentukan dan diubah Admin kapan diperlukan.** Dokumentasi spesifikasi, belum implementasi.

## 1. Prinsip arsitektur pengelolaan

**Admin mengatur data dan kebijakan operasional; pengguna dengan peran masing-masing menjalankan alur.** CRUD pusat bukan berarti Admin harus menyetujui setiap abstrak atau melakukan semua pekerjaan. Reviewer tetap self-claim, Komite Akademik tetap memutuskan ACCEPT, Finance memverifikasi pembayaran, Moderator menandai Presented, dan petugas mengonfirmasi QR/layanan.

```mermaid
flowchart TD
  A["ADMIN — CRUD & SETTINGS"] --> B["Conference & Review Settings"]
  A --> C["Co-Host Roster: 5 member → 5 paper"]
  A --> D["Paket Program Internasional + Deadline"]
  A --> E["MoU / Upgrading / PKM / Destinasi"]
  A --> F["Agenda, Venue, QR Services"]
  A --> G["Website CMS & Co-Host Branding"]
  A --> H["Finance, Staff, Certificates, Reports"]
  B --> I["Author & Reviewer Dashboard"]
  C --> J["PIC Co-Host + Member Account"]
  D --> K["Peserta membeli SATU paket Rp1 juta"]
  E --> K
  F --> L["Personal QR + Absensi"]
  G --> M["Public Website"]
```

## 2. Matriks modul CRUD Backoffice

| Menu Admin | Pengaturan dan aksi utama |
|---|---|
| **Event & Site Settings** | Nama/tema ICHES, tanggal, venue, teks, kontak, status publikasi |
| **Public Website CMS** | Hero, call for papers, dates, speakers, partners, Co-Host name/logo, jadwal, FAQ, footer |
| **Conference Tracks** | Delapan subtema TOR: create/edit/order/disable |
| **Abstract Submission Settings** | Open/Close, batas waktu, format PDF, batas kata, keywords, template, file size, bahasa |
| **Academic Submissions** | Cari/filter paper, versi, reviewer index, jumlah reviewer (konfigurasi setelah disahkan), review, keputusan, publish decision |
| **Full Paper & Sessions** | Template, persyaratan, jadwal/panel, moderator, status Presented; **unlock setelah ACCEPT diumumkan**, bukan tanggal kaku |
| **Co-Host Institutions** | Institusi, PIC, roster lima perwakilan, **satu paper per anggota/total lima**, invoice Rp3 juta, publikasi branding |
| **Participants** | Identitas, registrasi Conference dan paket pribadi, status, koreksi sesuai hak |
| **Paket Program Internasional** | Harga satu paket Rp1 juta/orang dan empat benefit, **tanggal/jam buka–tutup yang dapat diedit**, manual Open/Closed, peserta dan transaksi |
| **International MoU** | Institusi PIC, delegasi, signatory terverifikasi, jadwal, status penandatanganan |
| **Upgrading Kasubdit Penelitian-PKM Kemenag RI** | Jadwal, deskripsi, peserta, check-in |
| **PKM Internasional** | Kelompok, lokasi, peserta, PIC, daftar kehadiran |
| **Destinasi Internasional** | Tumpak Sewu–Teras Semeru, agenda, rombongan, titik kehadiran |
| **Finance** | Tarif, invoice **institusi vs pribadi**, bukti, koreksi/verifikasi, ekspor |
| **QR, Facilities & Attendance** | Stasiun/pos, petugas, layanan, slot konsumsi, stok, Default/Grant/Revoke, kehadiran |
| **Certificates** | Template, signer, preview, batch publish, koreksi/revoke, URL verifikasi |
| **Staff/Roles & Reports** | Penugasan dan kewenangan, rekap serta ekspor kegiatan |

**Jangan membangun satu halaman CRUD raksasa.** Menu dikelompokkan sesuai aktivitas, dengan pola tabel, filter, form, modal dan tombol Simpan yang konsisten, shadcn-vue putih–hijau. Admin memiliki hak pusat, petugas lain dapat memperoleh scope terbatas sesuai tanggung jawab.

## 3. CRUD batas akhir Paket Program Internasional: keputusan wajib

```mermaid
flowchart TD
  A["Admin: Package Settings"] --> B["Edit mulai/akhir pendaftaran + status"]
  B --> C["Simpan pengaturan di database"]
  C --> D["Dashboard peserta membaca aturan terbaru"]
  D --> E{"Registrasi masih Open?"}
  E -->|Ya| F["User dapat mendaftar satu paket Rp1 juta"]
  E -->|Tidak| G["Daftar ditutup, tampil keterangan"]
  F --> H["Backend cek aturan yang sama sebelum membuat satu tagihan user"]
```

| Field admin | Fungsi |
|---|---|
| Nama | **Paket Program Internasional** |
| Harga | **Rp1.000.000/orang**, satu paket empat program (sesuai kebijakan saat ini) |
| Termasuk | MoU; Upgrading; PKM Internasional; Destinasi Tumpak Sewu–Teras Semeru |
| Registration Opens | Tanggal dan jam mulai pendaftaran (Asia/Jakarta) |
| **Registration Deadline** | **Tanggal dan jam batas akhir; CRUD Admin, dapat diubah kapan diperlukan** |
| Registration Status | **Open / Closed**, penutupan manual jika dibutuhkan |
| Visibility | Tampil/tidak tampil di Dashboard peserta |
| Registered Users | Daftar peserta dan status pembayaran pribadi |
| Save Changes | Menerapkan perubahan untuk pendaftaran berikutnya, tanpa perubahan source code |

**Rekomendasi minimal:** tanggal deadline yang diubah langsung memengaruhi apakah peserta *baru* masih dapat mendaftar. Jangan hanya disable tombol Vue: backend memverifikasi status & deadline pada saat request. Peserta yang sudah mendaftar tetap dapat melihat tagihan dan riwayatnya meski pendaftaran ditutup. Admin bisa memperpanjang atau menutup sesuai ketentuan acara tanpa deploy. **Status pembayaran Finance VERIFIED** sebagai syarat aktivasi empat akses masih rekomendasi operasional yang perlu diputuskan.

**Deadline Paket Program Internasional terpisah dari deadline Conference Abstract Submission**; penutupan salah satu tidak otomatis menutup yang lain. **Full Paper dibuka ketika ACCEPT diumumkan**, bukan karena admin mengubah tanggal program pendamping.

## 4. Batas kewenangan agar CRUD tidak merusak catatan

| Boleh dikelola dengan Admin CRUD | Tetap dilindungi dari overwrite/hapus biasa |
|---|---|
| Tanggal pendaftaran, status Open/Closed, jadwal, lokasi, isi website, daftar program | Riwayat pembayaran/bukti dan nominal invoice lama |
| Tarif untuk **transaksi baru** setelah kebijakan disetujui | Naskah/version dan review yang telah resmi Submitted |
| Kuota, PIC, anggota Co-Host dan relasi paper (dengan koreksi) | Keputusan akademik historis, dokumen LoA yang sudah issued |
| Pos scanner, petugas, fasilitas, stok/entitlement | Bukti kedatangan dan layanan yang benar-benar diserahkan |
| Layout sertifikat dan daftar publikasi | Sertifikat Published dan histori revoke/reissue |

**CRUD pusat tidak menghilangkan validasi:** Admin dapat menetapkan kuota sesuai kebijakan penyelenggara, tetapi satu perwakilan tidak berhak mengonsumsi dua slot paper paket Co-Host yang sama. Mengubah tenggat **tidak secara retroaktif membatalkan** pengguna yang sebelumnya mendaftar; pengubahan biaya **tidak mengubah invoice lama**.

**Tidak perlu approval multilapis atau audit setiap tombol**, tetapi untuk koreksi data transaksi/sertifikat/keputusan akademik pertahankan fakta historis dan siapa yang berwenang memutuskan.

## 5. Acceptance criteria untuk UAT

1. Admin mengubah **tanggal/jam Deadline Paket Program Internasional** dari Backoffice tanpa developer; pendaftaran baru mengikuti nilai terbaru.
2. Admin dapat Open/Close pendaftaran paket; user lama tetap melihat tagihan dan status miliknya.
3. User membeli **satu paket Rp1 juta untuk empat akses** di Dashboard pribadi, tidak melalui PIC Co-Host.
4. Co-Host: **maks. lima perwakilan, satu paper masing-masing = maksimal lima paper**; satu invoice institusi Rp3 juta.
5. Semua paper Co-Host mengikuti validasi form/server → Reviewer Index langsung, reviewer self-claim dan Komite memutuskan.
6. Admin dapat CRUD tema/track, important dates, informasi konferensi, jadwal, program MoU, upgrading, PKM, destinasi, hak QR dan publikasi institusi.
7. Admin dapat mengelola deadline dan data **tanpa menghapus riwayat** keuangan, akademik, presensi atau sertifikat.

## 6. Register

| ID | Ketentuan | Status |
|---|---|---|
| NEW-046 | Satu institusi Co-Host: **lima perwakilan, lima paper, masing-masing satu paper** | **DISETUJUI** |
| NEW-047 | **Admin-centered CRUD** untuk International Conference dan rangkaian kegiatan ICHES lainnya | **DISETUJUI prinsip** |
| NEW-048 | Deadline Paket Program Internasional **editable Admin kapan diperlukan** | **DISETUJUI** |
| NEW-049 | Sistem membaca pengaturan Admin, sedangkan reviewer/finance/moderator/user tetap menjalankan perannya | **KONSEKUENSI DISETUJUI** |
| PROP-015 | Struktur menu, sakelar Open/Closed, visibility, pencadangan jatah paper pada Draft | **USULAN TEKNIS** |

Yang masih perlu didetailkan: aturan paper Rejected ditarik/ganti, ketentuan refund, hak paket sesudah verifikasi Finance, dan daftar hak CRUD tiap role. **Rancangan Admin sudah menjelaskan arah operasional tanpa menulis kode fitur.**

[Lihat kuota paper Co-Host](16-COHOST-LIMA-PAPER-PERWAKILAN.md) · [Paket user](15-PAKET-INTERNASIONAL-DASHBOARD-PESERTA.md) · [Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).