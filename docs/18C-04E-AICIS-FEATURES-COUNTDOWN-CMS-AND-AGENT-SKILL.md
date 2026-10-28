# 18C-04E — ICHES Public Frontend: AICIS Feature Inspiration, Mandatory Countdown, CMS & Agent Skill

> **STATUS LANJUTAN 28 OKT 2026:** Tahap **18C-05 Implementation Readiness** telah **ditulis sebagai DRAFT** di [18C-05](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md), [18C-05A](18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md), [18C-05B](18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md). Prioritas jika GO coding diberikan nanti adalah **S0 foundation, S1 public CMS/countdown, S2 abstract**. Agent Skill tersedia tetapi **tidak membuktikan code sudah dibangun**.

> **28 Oktober 2026 · ARAH PRODUK DITERIMA / DESAIN KOMPONEN TETAP TERBUKA / BELUM CODING.**
>
> **Instruksi terbaru pengguna:** Public website ICHES boleh mengikuti **cakupan fitur** AICIS, **WAJIB memiliki countdown menuju acara**, dan **pengelolaan seluruh konten website publik harus tersedia di Backoffice lewat Website CMS** sehingga panitia bisa menambah/mengubah halaman, section dan materi tanpa perlu meminta developer untuk perubahan rutin. Pemilik produk **tidak ingin diskusi komponen per komponen** sebelum AI membuat desain; di masa implementasi, AI developer harus menggunakan **SKILL** khusus UI/UX/website yang powerful, lalu pemilik produk tetap dapat mengoreksi hasil visual kapan pun.
>
> **Yang dipilih sebelum ini:** visual **Academic Editorial** modern, clean, no AI slop, Inter/Sora, forest/ivory/gold dan **tanpa carousel**; semua bentuk hero/layout/komponen tetap **tidak dikunci**. [18C-04B visual](18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md) · [18C-04C wireframe alternatif](18C-04C-DRAFT-HOMEPAGE-WIREFRAME-DESKTOP-MOBILE.md) · [18C-04D flexible CMS](18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md) · [Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).
>
> **Sumber eksternal (fitur yang terlihat, bukan kontrak backend AICIS):** [AICIS homepage](https://aicis.uinssc.ac.id/) (ditelaah 28 Okt 2026). Di sana terlihat navigasi profil, konferensi, submission, event, FAQ, unduhan, tema, tanggal konferensi, **countdown dalam 00 Days/Hours/Minutes/Seconds**, statistik pendaftar, subtema, timeline, dan Call for Papers Process. **Kehadiran fitur dalam situs AICIS bukan izin menyalin data, tampilannya, atau alur akademik mereka.** AICIS memuat tahap *Administrative Check*; ICHES **tidak** menggunakannya.
>
> **SKILL SIAP PAKAI di repo:** [ICHES Public Frontend Agent Skill](../.agents/skills/iches-public-frontend/SKILL.md), dilengkapi 3 referensi on-demand dan [AGENTS.md](../AGENTS.md) sebagai panduan bagi AI coding agent. Ini **instruksi di repository**, bukan fitur website yang sudah berjalan.

## 1. Rebaseline: dari debat komponen ke pengembangan berbasis skill dan review hasil

**Pendekatan baru yang diterima:** AI developer diberi **konteks produk lengkap + skill desain + kontrak CMS + kriteria QA**, lalu menghasilkan **satu pengalaman public frontend yang koheren untuk desktop dan mobile**. Pemilik produk cukup meninjau hasil, memberikan revisi yang relevan, dan boleh mengganti komponen apa pun. **Tidak perlu rangkaian tanya satu-satu “hero A/B/C?”, “grid A/B?”, “FAQ bagaimana?” sebelum ada tampilan yang bisa dinilai.**

Tetap dibedakan:
- **Boleh diputuskan AI dalam rancangan awal:** komposisi hero, layout section, panjang konten, spacing, fotografi berizin, varian navigasi, tata letak countdown, urutan section, empty/loading states dan responsivitas.
- **Tidak boleh diubah AI:** keputusan akademik/finance/Co-Host/participant, data TOR, jam mulai resmi yang belum ada, daftar jurnal fiktif, identitas institusi, status accept/published, maupun hak role.
- **Bisa direvisi pengguna kapan saja:** struktur halaman, visual, CTA copy/layout, section library, ilustrasi, CMS editing experience, fotografi, dan urutan.

**Tidak ada final lock pada hero, countdown placement, menu, section list, page builder UI atau pilihan varian.** Hanya *kemampuan countdown* dan *konten dapat dikelola dari Backoffice CMS* yang kini menjadi kebutuhan diterima.

## 2. Feature parity: apa yang relevan dari AICIS dan bagaimana ICHES mengadaptasi

| Fitur pada AICIS | Arah penerapan ICHES | Sumber data & kebijakan |
|---|---|---|
| **Homepage Conference** | Academic Editorial original, theme, event details, accessible CTA | Event Master + CMS published |
| **Countdown D/H/M/S** | **WAJIB**, berjalan nyata, target dapat dikelola Admin, tidak hardcode tanggal | Event starts_at/timezone official; Admin countdown section controls |
| Profil, About, tema & history | Halaman/konten editorial dapat dibuat dan diubah via CMS | CMS pages/translations/media |
| CFP / Submission Process | Journey ICHES yang benar: Abstract Text+PDF → Reviewer Index self-claim → Committee published ACCEPT → Full Paper OJS-like → Presented → revision/no revision → publication | Academic workflow ICHES, **TANPA Administrative Check** |
| Timeline | Event schedule & CFP dates dari master + versi tampilan CMS yang dapat diubah | Event/Conference Master; tanpa 2 sumber tanggal |
| Subthemes/tracks | Tampilkan 8 track TOR secara rapi dan link CFP | Conference tracks master |
| Download | Panduan, template, file umum berizin | Public CMS Media Library, bukan private paper/bukti bank |
| FAQ | FAQ per topik dan locale, dapat diatur Admin | CMS content |
| Event/program information | Main Conference, MoU, Upgrading, PKM, destinations | Program Master + CMS editorial blocks |
| Speakers, partners, institutional identity | Profil/foto/logo hanya bila resmi dan mendapat izin tayang | CMS/Partner directory & publication approval |
| Registrant data / infographics | **OPSIONAL**. Jika disetujui, ambil agregat statistik nyata dengan izin dan timestamp; **jangan menampilkan angka fiktif** | Aggregated authorized reporting, privacy-safe |
| Login/Register/Author Dashboard CTA | User session & role switching yang disepakati, CTA mengikuti submission state | Auth/Academic backend, bukan CMS yang bypass status |

**Bukan requirement cloning:** AICIS punya kompetisi dan program khususnya sendiri; ICHES tidak otomatis membutuhkan kompetisi video, booth expo, guestbook, Napak Tilas, atau field akademik AICIS yang berbeda. Tambahkan fitur bila benar diminta.

## 3. Countdown ICHES — requirement produk yang wajib ada

**Tanggal sumber TOR:** konferensi utama **Selasa, 5 Januari 2027**, kegiatan International PKM dan destinasi **Rabu, 6 Januari 2027**. **Jam mulai konferensi BELUM ditetapkan oleh pengguna dalam blueprint**; jangan menganggap `00:00 WIB` atau waktu lain sebagai fakta resmi.

**Kontrak:**
1. Menu **Backoffice → Website CMS → Countdown** atau subsection Event Essentials menyediakan pengaturan *enabled*, judul EN/ID/AR, pilihan varian tampilan, posisi/section order, post-start label. Target inti diambil dari **Event Master `starts_at` timezone Asia/Jakarta** (atau referensi event resmi yang dipilih panitia) dan **dapat diubah melalui Backoffice**.
2. Public countdown memiliki **Days / Hours / Minutes / Seconds**. Setiap detik berubah dengan perhitungan timestamp absolut, bukan angka statis. Jika exact time belum tersedia, **section countdown tetap ada** tetapi menampilkan **tanggal resmi + keterangan jam sedang dipastikan**, bukan menghitung dari jam buatan.
3. Setelah waktu target tercapai: **tidak negatif**, timer berhenti dan informasi beralih ke “Event started”/status acara berdasarkan sumber resmi. Setelah selesai, status/concluded ditentukan oleh Event Master.
4. Timezone viewer tidak mengubah target absolute instant; server mengirim ISO datetime dengan offset; untuk SSR hydration gunakan aman dari mismatch dan bila perlu *server_now*. Pergantian target dari Admin terproyeksi setelah publish/cache invalidation.
5. RTL untuk bahasa Arab, label bahasa yang valid, aria label/perubahan tidak diumumkan setiap detik oleh screen-reader; pengujian mobile dan reduced motion.
6. **Countdown tidak mengunci visualnya**: boleh di Hero, strip Event Essentials, atau section mandiri. AI memilih awal yang selaras Academic Editorial; user bisa merevisinya.

**Contoh perhitungan teknis (bukan source final):**

~~~text
target = parse a validated timezone-aware ISO datetime from Event Master
now = current instant; optionally correct clock drift using authoritative server_now
left = max(0, floor((target - now) / 1000))
days    = floor(left / 86400)
hours   = floor((left % 86400) / 3600)
minutes = floor((left % 3600) / 60)
seconds = left % 60
if target is missing: show truthful date/time-unconfirmed state
if left == 0: display event-started state, stop interval
~~~

**Hati-hati:** halaman AICIS yang diambil secara tekstual menampilkan "00 Days / 00 Hours / 00 Minutes / 00 Seconds", mungkin placeholder sebelum JavaScript berjalan; **jangan menyalinnya sebagai output timer ICHES**. Acceptance test memverifikasi hitungan masa depan yang benar.

## 4. Backoffice CMS harus bisa mengelola website publik secara luas

**Requirement yang diterima dari user:** Website tidak berhenti pada frontend yang seluruh teksnya tertulis di source Vue. **Backoffice memiliki menu “Website / CMS”** yang dipakai panitia untuk menambah dan memperbarui segala **konten publik**, termasuk halaman dan section. Pengaturan rutin harus tidak membutuhkan developer atau git deploy.

**Usulan daftar kemampuan CMS:**

| Area | Yang harus bisa dikerjakan dari Backoffice | Catatan implementasi |
|---|---|---|
| **Page management** | Create/duplicate/edit/delete/archive halaman, slug, visibility, template/section composition | Hindari reserved route collision dan hard delete published history |
| **Section management** | Add/duplicate/reorder/show/hide/delete existing blocks; ganti layout variant | Library **extendable**, bukan 12 komponen yang dikunci |
| **Content editing** | Headline, body, CTA, link, rich text aman, info pengantar/closing | Typed fields; no arbitrary JS/CSS |
| **Media** | Foto, logo yang diizinkan, dokumen publik, crop desktop/mobile, alt, caption/credit | Pisahkan dari private manuscript & Finance files |
| **Navigation/footer** | Link menu, urutan, grouping, submenu, footer, social links | Jangan bypass permissions/deadlines |
| **Global theme** | Logo/warna identitas/typography tokens dalam batas desain yang disepakati; layout presets | Review perubahan besar agar tetap Academic Editorial |
| **Countdown** | Enable/disable presentation, section placement, title locale, binding target event time | Engine harus tetap memakai Event Master |
| **Conference content** | CFP, Programs, Tracks, Timeline, Downloads, Speakers, Partners, Publications | Data live/domain references authoritative |
| **Translations** | EN/ID/AR text, review state, fallbacks dan RTL preview | Jangan mengarang terjemahan/identitas |
| **SEO/social** | Meta title/description, OG image, canonical, indexing/preview privacy | Hanya page published indexed |
| **Publishing** | Save draft, preview, publish, revision history/rollback, audit | Permissions scoped; user non-publisher cannot publish |

**Makna “menambah segalanya”:** panitia dapat membuat halaman baru dan menyusun konten dari blok yang didukung, bukan hanya edit homepage. Untuk **jenis layout/komponen teknis yang belum ada sama sekali**, developer/AI perlu menambahkan satu renderer+editor schema baru, lalu Admin dapat menggunakan berulang. Berjanjikan full free-form no-code tanpa batas itu tidak realistis dan membuka risiko keamanan.

**Data yang tidak boleh diduplikasi di CMS:** harga Co-Host/Paket, penerimaan bank, deadline submission, conference track, participant entitlement, accepted/published status jurnal dan academic decisions. Gunakan binding ke modul master aslinya.

## 5. AI Agent Skill yang sudah disiapkan (bukan hanya prompt sekali pakai)

**Lokasi repository:** `/.agents/skills/iches-public-frontend/SKILL.md`. Format Agent Skills portable: direktori dengan `SKILL.md` berisi YAML frontmatter `name` dan `description`, serta referensi yang dibaca on demand.

| File | Isi |
|---|---|
| [SKILL.md](../.agents/skills/iches-public-frontend/SKILL.md) | Instruksi utama desain dan implementasi frontend/CMS, business guardrails, no AI slop, countdown dan workflow AI |
| [DESIGN-DIRECTION.md](../.agents/skills/iches-public-frontend/references/DESIGN-DIRECTION.md) | Original Academic Editorial, hierarki, identitas, contoh varian **tidak locked**, aturan foto/responsif |
| [CMS-AND-DATA-CONTRACT.md](../.agents/skills/iches-public-frontend/references/CMS-AND-DATA-CONTRACT.md) | Menu CMS, pages/sections, role safety, data ownership, countdown D/H/M/S, source of truth |
| [QUALITY-GATES.md](../.agents/skills/iches-public-frontend/references/QUALITY-GATES.md) | Checklist visual dan fungsional, countdown test, CMS workflows, responsive/RTL/accessibility, evidence test |

**Agent Skills reference:** https://agentskills.io/specification; https://github.com/agentskills/agentskills. **Catatan kejujuran:** meletakkan skill di GitHub **tidak membuktikan skill sudah dideteksi/dipakai oleh setiap AI tool**; kompatibilitas discovery/format tetap dicek pada AI coding agent yang dipilih. Proyek saat ini dokumentasi, belum UI aktif. File `AGENTS.md` (jika tersedia) membantu pembaca otomatis memulai dari skill ini.

**Prompt operasional sederhana untuk AI agent (baru digunakan setelah GO coding diberikan):**

> Build and iterate the ICHES UNISYA 2027 public frontend and Website CMS. Read `.agents/skills/iches-public-frontend/SKILL.md` and its references, and honor the latest ICHES decision register. Use AICIS as feature inspiration, not a design clone. Use original Academic Editorial visual language, with modern clean responsive UI, EN/ID/AR RTL, functional editable event countdown, all public content managed via Backoffice CMS, safe dynamic bindings to academic/finance masters, and no fake data. Make sensible coherent decisions instead of asking component-by-component questions, but keep every visual component revisable. Show working desktop/mobile preview and actual test evidence. Do not claim completion for unimplemented features.

## 6. UAT tambahan untuk AICIS feature parity, countdown, CMS dan Skill handoff (25 rencana)

| ID | Skenario | Expected |
|---|---|---|
| AIC-T01 | Website mode COMING SOON | Hero status benar, event essentials dan countdown feature tersedia tanpa CTA submit palsu |
| AIC-T02 | Countdown target 5 Jan 2027 memiliki jam resmi | D/H/M/S menghitung masa depan secara tepat |
| AIC-T03 | Jam mulai belum ditentukan | Tanggal resmi tampil, timer tidak mengarang jam |
| AIC-T04 | Countdown berbeda timezone viewer | Sisa waktu tetap sama untuk target instant yang sama |
| AIC-T05 | Detik timer bertambah waktu satu detik | Countdown turun sesuai waktu nyata dan tidak drift menumpuk |
| AIC-T06 | Exact target tercapai | 0 lalu event-started, tidak angka negatif |
| AIC-T07 | Admin mengubah event start time | Timer publik berubah sesuai data publish/refresh, tanpa deploy |
| AIC-T08 | Admin pindah posisi Countdown section | Variant dan urutan sesuai CMS, logic tetap benar |
| AIC-T09 | Arabic countdown | Label RTL benar, angka/time semantic tidak rusak |
| AIC-T10 | Mobile countdown | Tidak overflow/menutupi CTA, readable dan accessible |
| AIC-T11 | CMS Admin membuat halaman baru | Halaman muncul setelah publish; slug valid |
| AIC-T12 | CMS Admin menambahkan beberapa section baru yang didukung | Tampil sesuai urutan, semua bisa diedit |
| AIC-T13 | CMS hide/duplicate/reorder sections | Publik hanya melihat revisi published yang benar |
| AIC-T14 | CMS edit header/menu/footer | Navigation berubah secara aman setelah publish |
| AIC-T15 | CMS mengubah foto/alt/crop | Public responsive media tampil tanpa deformasi atau data palsu |
| AIC-T16 | CMS mengubah EN tanpa AR | Fallback translation policy jelas, tidak mengarang isi |
| AIC-T17 | Draft dan preview | Draft tidak terindeks publik dan tidak terlihat tanpa izin |
| AIC-T18 | Publish/rollback | Revision audit dan cache invalidation benar |
| AIC-T19 | CMS rich text menyuntik script | Sanitized/rejected, no XSS |
| AIC-T20 | CMS berusaha override fee/deadline master | Sumber business master tetap authoritative |
| AIC-T21 | Public download template tersedia | Hanya dokumen published publik; bukan paper/bukti Finance |
| AIC-T22 | Fitur AICIS “Administrative Check” disalin tanpa sengaja | Test gagal; ICHES Submit abstract valid langsung Reviewer Index |
| AIC-T23 | Statistik belum tersedia | Tidak menampilkan angka dan klaim fiktif |
| AIC-T24 | AI membuat default hero/editorial layout sendiri | Tidak bertanya keputusan tiap komponen; hasil coherent dan dapat di-revisi owner |
| AIC-T25 | Agent membaca Skill di repo | Bisa mengidentifikasi kontrak fitur, guardrail bisnis, CMS, countdown dan quality gates sebelum coding |

**UAT belum dijalankan.** Yang telah disiapkan adalah aturan dan acceptance criteria, bukan frontend HTML/Vue yang telah dibangun.

## 7. Status keputusan dan langkah berikutnya

**DITERIMA SEBAGAI KEBUTUHAN:** AICIS feature inspiration, **countdown wajib**, **Backoffice Website CMS untuk pengelolaan menyeluruh konten publik**, alur pembuatan desain lewat AI Skill tanpa diskusi komponen satu-satu, dan revisibilitas desain.

**MASIH FLEKSIBEL:** komposisi hero, urutan/nama section, bentuk countdown, variasi halaman, jumlah blok CMS, pilihan editor rich text, kategori menu, visual design details, tanggal/jam countdown tepat yang diisi Admin, deployment SSR. **Tidak mengunci estetika yang belum diterima.**

**NEXT:** Tahap **18C-05 Implementation Readiness**, meliputi pengecekan repository, DB/PK, penggabungan tabel, pilihan autentikasi/hosting, urutan implementation vertical slices dan target QA. Pembahasan homepage per komponen **tidak lagi menjadi gate wajib**. **GO coding** tetap harus diputuskan secara eksplisit; tidak ada coding/deploy dalam tahap dokumentasi ini.

---

[SKILL.md](../.agents/skills/iches-public-frontend/SKILL.md) · [Academic Editorial 18C-04B](18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) · [README](../README.md).
