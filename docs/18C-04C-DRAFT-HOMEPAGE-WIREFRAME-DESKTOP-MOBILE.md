# 18C-04C — DRAFT Homepage Visual Wireframe: Desktop, Mobile & Variasi Komponen

> **SUPERSESSION WORKFLOW 28 OKTOBER:** User tidak mewajibkan pemilihan satu per satu varian H-01/H-02/H-03, N-01/N-02/N-03 atau susunan section sebelum AI membuat homepage. **AI menggunakan [18C-04E feature contract](18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md) dan [Skill](../.agents/skills/iches-public-frontend/SKILL.md) untuk membuat initial design koheren**, termasuk **countdown wajib**, lalu user bebas merevisi semua komponen. Semua sketsa dalam dokumen ini tetap referensi, bukan gate pilihan.

> **28 Oktober 2026 · EKSPLORASI, BUKAN FINAL.** Pemilik produk menyetujui **arah Academic Editorial**, namun secara eksplisit meminta **“komponen-komponen frontend sesuai keinginan saya, jangan mengunci.”** Dokumen ini mengusulkan *beberapa alternatif* komposisi yang dapat dikritik, diganti, dihapus, atau digabung. **Tidak ada hero, section, urutan, bentuk card, foto, typography scale, CTA, breakpoint, atau layout preset yang dikunci.**
>
> **Bukan source frontend / bukan aplikasi / bukan mockup yang telah disetujui.** Semua wireframe teks dalam dokumen ini adalah referensi untuk review visual sebelum prototyping dan coding.
>
> [18C-04B Arah Academic Editorial](18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md) · [18C-04D Rancangan CMS Fleksibel](18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md) · [18C-04 Frontend Architecture](18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md) · [Register Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Yang sudah disepakati vs yang sengaja bebas

| Lapisan | Status | Cara merevisi |
|---|---|---|
| **Bahasa visual: Academic Editorial** (clean, modern, restrained, akademik, bukan AI slop) | **ARAH DIPILIH USER** | Karakter visual masih dapat dipertajam melalui review |
| ICHES 2027 identity, warna forest `#164734`, ivory `#F7F6F1`, near-black `#17211C`, gold `#B79A5B`, Inter/Sora, EN/ID/AR RTL, tanpa carousel | **BASELINE IDENTITAS YANG SUDAH DIBAHAS** | Perincian pemakaian token, jarak dan hierarchy **belum dikunci** |
| Header navigation, hero geometry, background image, CTA position, section count/order, card/banner/panel composition | **SELURUHNYA DRAFT** | User dapat memilih, meniadakan, menambah atau mengganti bagian |
| CMS section gallery dan edit via Backoffice | **ARAH KEBUTUHAN DARI USER** | Tipe/varian blok, mekanisme visual editor & workflow masih **usulan** |
| Data akademik/tarif/jadwal/status Finance | **SUMBER KEBENARAN MODUL BISNIS YANG DISETUJUI** | Boleh ubah lewat master yang berwenang; CMS tidak boleh menimpa nilai operasional sepihak |
| Implementasi Laravel/Vue frontend | **BELUM ADA GO** | Tunggu persetujuan detail dan implementation readiness |

**Prinsip desain:** tidak boleh menyebut “sudah final”, “paten”, “locked” terhadap komponen hanya karena wireframe dibuat. Pengguna adalah pihak yang menilai bentuk akhir.

## 2. Tiga variasi *hero* yang boleh dipilih/digabung (semuanya DRAFT)

### H-01 Split Editorial — **rekomendasi awal, BUKAN pilihan final**

~~~text
DESKTOP: ───────────────────────────────────────────────────────────────
  ICHES / UNISYA                                   EN · ID · AR  [Login]
  ────────────────────────────────────────────────────────────────────
  THE 6TH INTERNATIONAL CONFERENCE 2027     [STATUS: COMING SOON]
  ┌────────────────────────────────┬───────────────────────────────┐
  │ ISLAMIC VALUES                 │ [IMAGE: real venue / local]   │
  │ IN A CHANGING WORLD            │ [photography permission       │
  │                                │  verified; crop responsive]  │
  │ Education, Accountability,     │                               │
  │ and Social Resilience...       │                               │
  │                                │                               │
  │ [Explore Conference] [Journey] │                               │
  └────────────────────────────────┴───────────────────────────────┘
  05–06 JAN 2027? [READ FROM EVENT MASTER, NEVER HARDCODE DATE]
~~~

**Kekuatan:** teks dominan, foto sebagai pelengkap; estetika editorial. **Risiko:** terlalu umum jika menggunakan stock photo/kolase template; crop mobile harus terencana.

### H-02 Typography First — tanpa foto di atas fold

~~~text
DESKTOP: ───────────────────────────────────────────────────────────────
  [ICHES 2027]                          ABOUT | PROGRAM | SUBMISSION
  ────────────────────────────────────────────────────────────────────

           ISLAMIC VALUES IN A CHANGING WORLD
          Education · Accountability · Social Resilience

   THE 6TH INTERNATIONAL CONFERENCE ON HUMANITY, EDUCATION & SOCIETY

            [Explore the Conference]     [Important Dates]

  ─── First editorial image BELOW hero, not a full-width hero ────
~~~

**Kekuatan:** kesan institusional, berkarakter lewat tipografi tanpa bergantung foto. **Risiko:** terasa terlalu datar bila spacing, scale atau teks tidak ditangani dengan presisi.

### H-03 Image-led Editorial — foto dominan, teks pada bidang terpisah

~~~text
DESKTOP: ───────────────────────────────────────────────────────────────
  Navigation (minimal)
  ┌─────────────────────────────────────────────────────────────────┐
  │ [REAL PHOTOGRAPH / VENUE — image dominant, NO TEXT OVER IMAGE] │
  │ [Contextual location/credit placed outside the photo]          │
  └─────────────────────────────────────────────────────────────────┘
  EYE-BROW / EVENT            [DATE / LOCATION from CMS master]
  ISLAMIC VALUES IN A CHANGING WORLD
  One paragraph of academic positioning     [Explore Programme]
~~~

**Kekuatan:** atmosfer editorial lebih kuat. **Risiko:** terlihat seperti portal wisata apabila foto lanskap terlalu dominan atau akademik justru tenggelam. **Belum dipilih**.

**Semua varian hero dapat punya COMING SOON atau CFP state**, bergantung CMS dan jadwal event. CTA **tidak boleh** memicu payment-before-accept. Foto hanya dari bahan autentik/berlisensi, jangan mengarang speaker atau institusi.

## 3. Wireframe desktop homepage — komposisi H-01 sebagai TITIK AWAL, bukan final

~~~text
DESKTOP VIEWPORT (informational wireframe only, not exact pixels)
╔══════════════════════════════════════════════════════════════════╗
║ [ICHES / UNISYA]           About  Call for Papers  Programs  EN  ║
╠══════════════════════════════════════════════════════════════════╣
║ THE 6TH INTERNATIONAL CONFERENCE               [Coming Soon]     ║
║ ┌────────────────────────────────┬─────────────────────────────┐ ║
║ │ Main conference theme          │ Editorial photo placeholder│ ║
║ │ Academic supporting statement  │ Authentic, no AI fake      │ ║
║ │ [Explore] [Submission Journey] │ Crop/credit controlled     │ ║
║ └────────────────────────────────┴─────────────────────────────┘ ║
╠══════════════════════════════════════════════════════════════════╣
║ EVENT ESSENTIALS · date · venue · attendance format (LIVE master)  ║
╠══════════════════════════════════════════════════════════════════╣
║ About ICHES (short editorial copy)                 [Read more]   ║
╠══════════════════════════════════════════════════════════════════╣
║ Submission Journey (timeline/numbered editorial list)             ║
║ Abstract → Review → Published ACCEPT → Full Paper → Presented      ║
║ → Revision if required → Final ACC → Publication handoff           ║
╠══════════════════════════════════════════════════════════════════╣
║ Programs (1 feature editorial panel + compact supporting list)    ║
╠══════════════════════════════════════════════════════════════════╣
║ Conference Tracks (read from Conference Master; optional on home) ║
╠══════════════════════════════════════════════════════════════════╣
║ Speakers / Academic Dialogue (only if truly confirmed & public)   ║
╠══════════════════════════════════════════════════════════════════╣
║ Publication / Proceedings + selected journal info (not guarantee) ║
╠══════════════════════════════════════════════════════════════════╣
║ Partners + Institutional identity + Contact + Policies footer     ║
╚══════════════════════════════════════════════════════════════════╝
~~~

**Susunan di atas bukan kewajiban sembilan section**. Pengguna dapat menghapus, memindahkan, mengganti layout section, atau meminta bentuk baru. Jika halaman terlalu panjang, bagian rinci seperti Tracks, Speakers, Publication diarahkan ke inner pages. **Tidak membuat angka statistik konferensi palsu.**

## 4. Wireframe mobile: urutan dan prioritas interaksi (DRAFT)

~~~text
MOBILE VIEWPORT (informational wireframe only)
┌──────────────────────────────┐
│ ICHES                 ☰  EN  │   navigation via accessible sheet
├──────────────────────────────┤
│ THE 6TH CONFERENCE           │
│ [COMING SOON]                │
│                              │
│ ISLAMIC VALUES               │
│ IN A CHANGING                │
│ WORLD                        │
│                              │
│ Supporting academic text     │
│                              │
│ [ Explore Conference ]       │   CTA full-width if suitable
│ [ Submission Journey ]       │
├──────────────────────────────┤
│ [OPTIONAL HERO IMAGE]        │   crop/focal point per breakpoint
│ [real photo; credit]         │
├──────────────────────────────┤
│ Date / Venue / Mode          │   labels + real event master
├──────────────────────────────┤
│ ABOUT                        │
│ Headline + short paragraph   │
├──────────────────────────────┤
│ SUBMISSION JOURNEY           │
│ 01 Abstract                  │
│ 02 Review                    │
│ 03 Published ACCEPT          │
│ 04 Full Paper                │
│ 05 Presented & Publication   │
├──────────────────────────────┤
│ PROGRAMS / TRACKS / SPEAKERS │   collapsed to readable sections
├──────────────────────────────┤
│ PUBLICATION & PARTNERS       │
├──────────────────────────────┤
│ Contact / Policies / Footer  │
└──────────────────────────────┘
~~~

**Alasan rekomendasi mobile:** teks tema tetap terbaca; visual turun setelah CTA bila crop menghabiskan tinggi layar; navigasi pendek; tabel/daftar berubah menjadi stack yang sesuai. **Tidak mengunci breakpoint atau urutan foto**. Dalam bahasa Arab layout RTL harus benar, sementara angka ID, DOI, tanggal tertentu bisa tetap LTR sesuai kebutuhan.

## 5. Menu header yang sedang diuji (pilih/revisi, tidak dikunci)

| Variasi | Nav utama desktop | Tradeoff |
|---|---|---|
| N-01 **Ringkas** | About, Conference, Programs, Publication, EN/ID/AR, Login/Dashboard | Prioritaskan informasi, sisanya via dropdown halaman |
| N-02 **Akademik** | About, Call for Papers, Important Dates, Programs, Speakers, EN/ID/AR, Dashboard | Lebih cepat bagi calon presenter, tetapi header lebih ramai |
| N-03 **Editorial** | Conference, Explore Programs, Submission Journey, Contact, Languages, Account | Lebih artistik tetapi beberapa tujuan tersembunyi |

Semua opsi membutuhkan footer lengkap, akses keyboard/focus, viewport mobile, dan redirect CTA berdasarkan status pendaftaran. **Pilihan N-01 belum otomatis final**.

## 6. Alternatif tampilan section, bukan semuanya harus “Card Grid”

| Section | Varian yang bisa dipertimbangkan | Dapat diganti oleh pengguna? |
|---|---|---|
| About | Copy split editorial / centered manifesto / image+copy | **Ya** |
| Event Essentials | Inline facts / narrow strip / bordered editorial facts | **Ya** |
| Submission Journey | Numbered list / vertical timeline / clean horizontal stages | **Ya** |
| Programs | Editorial feature + list / three panels / simple rows | **Ya** |
| Tracks | Numbered typography list / two columns / tabs (tanpa carousel) | **Ya** |
| Speakers | Portrait grid hanya jika publik / editorial feature / hide until confirmed | **Ya** |
| Partners | Logo cloud restrained / categorised rows / no gallery until authorized | **Ya** |
| Publication | Editorial copy with 2 paths / accordion FAQ / dedicated page only | **Ya** |
| Footer | Split institute + links / multicolumn compact | **Ya** |

**Anti-slop design tests (sebelum disetujui):** (1) tidak semua section memakai kartu identik, (2) aksen gold proporsional, (3) tanpa gradien dekoratif besar, (4) tidak ada angka/testimoni rekaan, (5) foto bukan AI representation of speakers, (6) tidak ada animasi untuk menutupi hierarki lemah, (7) metadata / kontak yang belum sah jangan muncul sebagai fakta.

## 7. Urutan review yang tidak mengunci komponen

1. **Arah hero**: H-01/H-02/H-03 atau gabungan/koreksi sesuai selera pengguna.
2. **Header/navigation**: N-01/N-02/N-03 atau versi baru.
3. **Homepage composition**: apa yang perlu ada di halaman awal dan apa yang cukup di halaman dalam.
4. **Mobile priority**: perbedaan hero/CTA/media pada mobile.
5. **CMS editing boundaries**: varian apa yang boleh diubah dari Backoffice tanpa deployment.
6. **Satu visual high-fidelity hanya setelah komposisi cukup cocok**, lalu lakukan revisi sampai pengguna menyatakan “final”.

**Tidak menutup review dengan pernyataan “sudah final” atau “sudah dikunci.”** Beri nomor revisi pada dokumen jika pengguna meminta perubahan; jangan paksa user memilih salah satu preset jika semua tidak sesuai.

## 8. Output dan batas saat ini

**Sudah tersedia:** konsep hero multiopsi, wireframe desktop/mobile, varian navigasi, alternatif komponen dan rencana review. **Belum tersedia:** desain Figma final, file source Vue, actual browser prototype, gambar/foto autentik berizin, CMS bekerja, responsive test, atau UAT yang benar-benar dijalankan. Semua tetap dapat diubah tanpa mengubah kesepakatan bisnis Academic/Finance/Co-Host.

---

[18C-04B Academic Editorial](18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md) · [18C-04D CMS Block Contract DRAFT](18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).
