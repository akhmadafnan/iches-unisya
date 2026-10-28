# 18C-04B — Arah Visual Public Frontend & CMS ICHES 2027

> **ADENDUM 28 OKT 2026 — USER MELARANG PENGUNCIAN KOMPONEN:** **Academic Editorial adalah ARAH**, sementara semua **hero, header, section, komposisi mobile/desktop, foto, varian CMS, dan urutan** harus **bisa diubah sesuai keinginan user**. [18C-04C eksplorasi homepage](18C-04C-DRAFT-HOMEPAGE-WIREFRAME-DESKTOP-MOBILE.md) dan [18C-04D rancangan CMS fleksibel](18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md) berstatus **DRAFT, belum ada opsi komponen final**.

> **28 Oktober 2026 · STATUS: ARAH VISUAL DIPILIH, DETAIL HOMEPAGE DAN CMS MASIH DRAFT · BELUM CODING.**
>
> **Keputusan eksplisit pengguna:** “Oke saya memilih OPSI A saja ya.” Artinya **Opsi A — Academic Editorial** menjadi **arah visual resmi yang dipilih untuk rancangan Public Frontend ICHES 2027**. Ini **bukan** persetujuan atas layout tertentu, desain homepage final, pilihan fitur CMS final, atau perintah implementasi.
>
> Lihat: [18C-04 Frontend & Dashboard Architecture](18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md), [18C-04A Screen Inventory](18C-04A-DRAFT-SCREEN-INVENTORY-COMPONENTS-INTERACTION-UAT.md), [Register Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md). Dua referensi pengguna untuk struktur dan penyajian informasi: [AICIS](https://aicis.uinssc.ac.id/) dan [Multaqa Dawli 2026](https://multaqadawli2026.mahadalylirboyo.ac.id/index.html). Keduanya **referensi untuk dipelajari**, bukan template untuk disalin.

## 1. Arah yang sudah dipilih

| Aspek | Arah visual **DIPILIH** |
|---|---|
| Bahasa desain | **Contemporary Academic Editorial**: modern, clean, elegan, matang, akademik internasional |
| Komposisi | Hierarki tipografi kuat, ruang kosong terukur, grid/asimetri disiplin, bukan kartu seragam di tiap section |
| Identitas | The 6th ICHES 2027, aksen **H oranye–gold** UNISYA tetap; belum membuat/mengubah logo |
| Palet | Ivory `#F7F6F1`, forest `#164734`, near black `#17211C`, gold `#B79A5B` |
| Tipografi | Inter untuk body/UI, Sora untuk display; tipografi Arabic butuh fallback yang terbukti baik |
| Foto/ilustrasi | Foto nyata, dokumentasi institusi/venue dan konteks Lumajang secukupnya; bukan foto AI yang menggambarkan narasumber/peserta fiktif |
| Mood | Kredibel, editorial, restrained, tidak bertema wisata meskipun menampilkan destinasi sebagai program |
| Yang dihindari | **AI slop**: gradien/ornamen berlebihan, testimonial/statistik rekaan, hero generik, kartu monoton, animasi berlebihan, **tanpa carousel** |
| Responsif | Mobile dan desktop disusun sengaja, bukan sekadar skala elemen; EN/ID/AR dengan RTL Arabic |

**Jangan dianggap telah dipilih:** opsi hero dua kolom vs full-width, foto mana yang dipakai, jumlah section, komponen title ukuran final, desain footer, microinteraction, dan durasi animasi. Semua akan dibahas melalui wireframe/prototype, bukan diputuskan secara sepihak.

## 2. Referensi sebagai bahan eksplorasi, bukan cloning

- **AICIS** menjadi contoh kelengkapan informasi konferensi: alur submission, tema, tanggal, FAQ, unduhan, dan navigasi; ICHES tidak harus mengulangi kepadatan semuanya pada homepage.
- **Multaqa Dawli 2026** menjadi contoh struktur lebih ringkas dan kontekstual terhadap institusi/acara; ICHES tidak perlu menyalin komposisi yang sama.
- **ICHES** menempatkan karya akademik, tema utama, jalur submission, mitra, kegiatan internasional, dan publikasi sebagai hierarki informasi yang ditetapkan melalui content strategy tersendiri.

## 3. Struktur homepage awal untuk diuji di wireframe (DRAFT)

1. **Hero/editorial statement:** identitas acara, tema, status COMING SOON atau CFP sesuai CMS, CTA yang tidak menyesatkan, satu foto asli apabila cocok.
2. **Event essentials:** tanggal, lokasi dan format yang benar-benar tersimpan pada master Event.
3. **About ICHES:** penjelasan singkat, tautan untuk membaca lebih jauh.
4. **Submission Journey:** Abstract Text+PDF → Reviewer Index tanpa screening → ACCEPT published → Full Paper OJS-like → Presented → revisi atau tanpa revisi → publication routing.
5. **Conference & International Programs:** konferensi, sesi MoU, upgrading, PKM, destinasi.
6. **Conference Tracks:** delapan topik sebagai master data conference (bukan duplikasi isi CMS).
7. **Speakers/academic dialogue:** hanya data yang telah dikonfirmasi dan disetujui untuk tampil.
8. **Publication opportunities:** Proceedings dan jurnal pilihan sesuai status aktual; **Selected for Journal bukan Accepted by Journal**.
9. **Partners & footer:** mitra dengan izin publikasi, kontak, FAQ/kebijakan.

Jumlah, urutan dan kepadatan section adalah **usulan yang akan ditinjau melalui desktop/mobile wireframe**. Homepage tidak wajib menampilkan semua data detail. CMS dapat mengatur urutan dan visibility **jika model CMS terstruktur nanti disetujui**.

## 4. Structured Visual CMS sebagai REKOMENDASI, belum final

**Kebutuhan pengguna:** halaman publik semestinya bisa diedit dan dikelola di Backoffice tanpa mengubah source code untuk pembaruan rutin.

**Usulan implementasi:** **structured blocks / curated section presets**, bukan drag-and-drop HTML/CSS bebas. Dalam CMS, Content Editor/Admin berizin dapat mengelola teks, foto, CTA, navigasi, section order, visibility, konten EN/ID/AR, SEO, dan halaman informasi. Form data akademik, tarif, tanggal penting, program dan status pendaftaran **membaca dari modul sumber**, bukan disalin manual ke teks terpisah.

| CMS capability | Rekomendasi DRAFT | Catatan |
|---|---|---|
| Page list | Home, About, CFP, Programs, Publications, Contact, FAQ, halaman editorial tambahan | Struktur halaman perlu review |
| Section library | Hero editorial, rich copy, event essentials, journey, track list, speaker grid, program showcase, partners, CTA, FAQ | Komponen terkurasi, varian terbatas |
| Editor | Input terstruktur; rich text hanya untuk bagian konten; upload media teroptimasi | Bukan editor HTML/JS bebas |
| Publish workflow | Draft → Preview (desktop/mobile/locale) → Publish; revisions dan rollback | Policy/role khusus |
| Lokal | EN/ID/AR termasuk RTL; fallback terkontrol dan penanda konten belum diterjemahkan | Tidak otomatis membuat terjemahan resmi |
| SEO | Meta title/description, Open Graph image, sitemap/canonical sesuai implementasi | Optimasi untuk public indexing |
| Data source | Conference Tracks, deadline, fees, speakers, partner status dari master/domain asli | Hindari dua sumber kebenaran |
| Component governance | Design tokens dan responsive rules dipertahankan developer | Admin mengedit isi/urutan, tidak merusak style keseluruhan |
| Media safety | Foto asli/berlisensi, izin tampil, alt text, cropping responsive | Tanpa gambar rekaan institusi/speaker |

**Batasan jujur:** CMS tetap harus dibangun sekali pada codebase (Laravel + Inertia/Vue 3, Admin shadcn-vue). Setelah dibangun, konten dan kombinasi section yang tersedia bisa berubah tanpa deploy; komponen visual yang sepenuhnya baru masih memerlukan coding. Jangan menjanjikan no-code 100%.

## 5. Pembagian CMS dan Backoffice lain

- **CMS Website:** Konten publik, media, navigation/footer, draft/publish, SEO, terjemahan.
- **Conference Master:** Event info, submission dates, tracks, status CFP, terms dan metadata.
- **Program Master:** Jadwal/aktivitas dan paket yang sudah disetujui.
- **Publication:** Outcomes, proceeding/journal outlet yang benar dan status yang boleh dipublikasikan.
- **Admin Dashboard:** Pengguna/role, Finance/Co-Host/Review/QR sesuai permissions; **Content Editor tidak otomatis memperoleh Finance Verified, ACC, Presented, atau Bank Settings**.

Satu Laravel app tetapi **sumber data tidak diduplikasi** agar edit deadline pada Backoffice tidak membuat halaman publik bertentangan dengan proses submission.

## 6. Paket desain berikutnya sebelum Implementation Readiness (18C-05)

| Langkah | Deliverable | Gate |
|---|---|---|
| **A. Visual language** | Referensi komposisi, typography hierarchy, layout rules, fotografi dan pattern anti-slop | Review nuansa, bukan langsung coding |
| **B. Homepage wireframe** | Sketsa desktop + mobile: hero, hierarchy, copy, primary CTA, sections | Pengguna memilih/revisi komposisi |
| **C. Key inner pages** | CFP, programs, publication, fees, speakers/partners; tidak semua halaman wajib unik | Navigasi dan kelengkapan konten diperiksa |
| **D. CMS admin editor UX** | Page list, section controls, content fields, multilingual, media, live preview dan publication states | Pastikan dapat dikelola non-developer |
| **E. Content data contracts** | Data model pages/sections/translations/media/revisions dan relasi ke master akademik | Cegah duplikasi data |
| **F. Responsive/RTL/SEO QA plan** | Acceptance criteria, pengujian EN/ID/AR, aksesibilitas dan performa | Baru siap masuk readiness stage |

**Tidak perlu memutuskan semua di satu pesan.** Mulai dari wireframe homepage sebagai jangkar kualitas desain, lalu turunkan CMS section library berdasarkan bagian yang benar-benar diperlukan.

## 7. Review register — hal yang masih terbuka

| ID | Pertanyaan desain yang belum final |
|---|---|
| VIS-01 | Hero dua kolom editorial vs opsi komposisi lain; foto editorial apa yang nyata dan tersedia? |
| VIS-02 | Prioritas visual: identitas konferensi, agenda, akademik, atau mitra di atas fold? |
| VIS-03 | Section mana wajib tampil pada fase COMING SOON vs CFP vs event berlangsung? |
| VIS-04 | Pilihan layout presets yang boleh diganti Admin |
| VIS-05 | Apakah CMS dapat menambah *jenis* section sendiri atau hanya menyusun dari katalog developer? (Rekomendasi hanya dari katalog) |
| VIS-06 | Konten fallback EN/ID/AR, approval/publish workflow dan izin Content Editor |
| VIS-07 | Asset logo, foto venue/Lumajang, speaker yang diizinkan, lisensi media |
| VIS-08 | Pilihan rendering/SSR dan optimasi gambar sesuai hosting aktual |

**Status:** visual direction **DIPILIH**, blueprint homepage/CMS **DRAFT**, implementasi **BELUM dimulai**.

---

[18C-04 Frontend UX](18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md) · [18C-04A Screen Inventory](18C-04A-DRAFT-SCREEN-INVENTORY-COMPONENTS-INTERACTION-UAT.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).
