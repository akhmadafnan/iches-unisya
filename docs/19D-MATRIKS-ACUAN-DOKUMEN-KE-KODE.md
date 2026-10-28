# 19D — Matriks Traceability: Keputusan Produk → Dokumen → Area Kode → Tes

> **28 Oktober 2026 · MATRIKS DRAFT UNTUK KONVERSI BLUEPRINT KE CODING TICKETS · BELUM GO.** Semua file target pada kolom Code Area adalah **calon area kode**, **bukan path yang sudah ada** di repo baru (yang masih documentation-only). Source prioritas adalah keputusan terbaru user dan register.
>
> [19A Collaboration Protocol](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [19B Technical Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [19C Codex Work Orders](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) · [09 Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Matriks domain utama

| Domain / keputusan | Primary source di repository | Future code area (Codex only) | UAT/negative proof yang harus ada | Task |
|---|---|---|---|---|
| App foundation/repo reuse | 18C-05, 18C-05A, `AGENTS.md` | `bootstrap/`, `app/`, `composer.json`, `package.json` | Install/build/migrate on test, no old data/secret | S0-01/02/07 |
| Identity one-user multi-role | 18A Role, 18C-03 Security | Auth controllers/Policies, role permissions, account menu | Same user ID, cross-event forbidden | S0-03/04/06 |
| Switch Workspace and Superadmin read-only | 18C-03B, 18C-03A | Session middleware/Policy, personal/backoffice layouts | Original actor retained; no mutation in support view | S0-04/08 |
| Private files & public media | 18C-03, 18C-05A | `stored_files`, private/public disks, file policies | IDOR forbidden, no public proof/manuscript | S0-05, S1-07 |
| Public Academic Editorial brand | 18C-04B/C, 18C-04E, public frontend skill | `resources/js/pages/public/`, `components/` | Mobile/desktop/RTL, authentic assets, no AI slop | S1-05/08 |
| **Website CMS** whole public contents | 18C-04D, 18C-04E, public skill CMS contract | CMS pages/revisions/section registry/Backoffice routes | New page, add/hide/reorder/publish/rollback; unauthorized denied | S1-01..04 |
| **Countdown D/H/M/S** to conference | 18C-04E, official TOR | Event Master `starts_at`, CMS Countdown block | No guessed hour, timezone, zero boundary, Admin edit | S1-06 |
| Conference Tracks and CFP dates | 10 TOR, 17 Admin, 18B-02 | Event settings, ConferenceTrack CRUD, CFP Routes | Public CTA matches actual submission gate | S2-01 |
| Abstract Text+PDF no screening | 13 Submission, 18B-02/03 | Papers/Files/Author Actions, Reviewer Index | Draft incomplete allowed, valid Submit directly in Index | S2-02/03/05 |
| Reviewer self-claim all tracks + COI | 18B-03, 18C-03A | reviewer_claims, reviews, Policies | Cannot review own paper/overclaim; review not final decision | S3-01 |
| Published Academic ACCEPT unlocks Full Paper | 18B-03/09, 18C-02 | academic_decisions, FullPaperPolicy | Reviewer recommendation not enough; accepted unpaid still upload | S3-02, S5-01 |
| Co-Host one IDR3m invoice/five members+papers | 12–16, 18B-04, 18C-01 | institutional invoices, roster, sponsor rights | Five slots only after Verified, no second regular fee | S4-01/03 |
| Personal international package IDR1m/four rights | 15, 18B-05 | package orders, entitlements | One personal invoice, four rights after Verified, PIC cannot see personal | S4-01/04 |
| Manual Finance one official bank + credit matching | 18B-06/07/08, 18C-05A | invoices, bank credits, payment review Actions | screenshot ≠ verified; idempotent ledger, Superadmin only bank settings | S4-01/02 |
| Full Paper OJS-like metadata & versions | 18B-09, 18C-01B | scholarly metadata, files, revisions | Prefill authors, one canonical version chain, immutable v1 | S5-01 |
| Moderator Presented vs QR | 18B-03, 18C-03A | conference sessions, moderator actions | Scanner cannot mark Presented | S6-01 |
| Assessment/revision/Final ACC | 18B-09, 18C-01B | postpresentation assessments, Revision Requests, Committee policy | No Revision no v2 needed, Final ACC only Committee | S6-02 |
| QR attendance/services/certificates | 05–06, 18B-00/01 | QR/tokens, service fulfillments, cert batches | Double scan guard, private QR, manual issue | S7-01 |
| Proceedings/10 *candidates* journal/OJS import | 18B-09, 18C-01B | publication outlets/snapshots/export adapters | Selection not acceptance, staging XML only “Ready” | S8-01 |
| Role/security/reliability end-to-end | 18C-03A, 18C-05B | Tests/CI/E2E/Deployment configuration | Negative tests + backups/restore & staging evidence | S0-07/S9-01 |

**Path references are illustrative, not proof files exist**. Always inspect repo after Codex ports foundation.

## 2. Decision status and blockers explicitly preserved

| Open decision | Default proposal (NOT approved) | Blocks / must not assume |
|---|---|---|
| Canonical repo | `akhmadafnan/iches-unisya` + selective old reuse | S0-01 branch/scope |
| DB engine / PK | MySQL + UUIDv7 | S0-02 and first migrations |
| PHP/Node versions | PHP 8.4 target, Laravel 13, Node compatible with Vite tooling | S0 installation; actual host unknown |
| Authentication baseline | Laravel email/password+verify/reset; MFA staff TBD | S0-03/privileged auth |
| Countdown exact hour | **NONE**; Admin sets authoritative local start time | Live D/H/M/S; never invent `00:00` |
| Co-Host member replacement/rejected paper | Unsettled | Sponsor seat/replacement mutations beyond simple 5 slots |
| Multi-paper fees/refund/top-up | Unsettled | Finance invoice cases and adjustment actions |
| OJS target exact version, staging, 10 actual journals | Missing/undecided | Ready-to-import/public journal catalogue |
| Full Paper file type/number of assessor review rounds | Unsettled | Final validation and post-event process |
| Offline scanner | Online-first is recommendation | Offline guarantees and native/PWA coding |
| Superadmin support session TTL | Configurable proposal, not exact default | Production activation of View as User |
| Production host/domain/SMTP/assets/bank account | Not inspected/verified | Live deploy, email, official bank and photo publication |

**Sensible partial development:** Missing publication/OJS decisions **do not block** initial public CMS/Abstract as long as UI avoids false promises and data model supports later extension. Conversely, missing DB/PK policy **does block** first production-compatible migrations.

## 3. Document reading guidance — avoid bloating Codex context

For a specific task, read:
- Always: `AGENTS.md`, `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md` relevant section, `docs/19B...` task row and Engineering Skill.
- S0 Auth/DB: 18C-01/A, 18C-03/A, 18C-05A.
- S1 Public: `.agents/skills/iches-public-frontend/SKILL.md`, 18C-04D/E, 18C-04A.
- S2/S3 Academic: 18B-02/03, 13, 18C-03A.
- S4 Finance/CoHost: 18B-04/05/06/08, 18C-05A and policy register.
- S5/S6/S8 Publication: 18B-09, 18C-01B, specific OJS adapter docs as needed.
- S7 Ops: 18B-00/01, 05/06 and 18C-03A.

**No need to load 53 files each task**. Start with 2–4 primary docs, follow only specific cross-links relevant to implementation.

## 4. Change impact rule

When user changes a policy (e.g., fee, bank, review stage, CMS functionality):
1. **ChatGPT** records decision/supersession in the Register, reviews affected work orders and tests.
2. Determine if affected tasks are PLANNED vs IN_CODEX vs REVIEW vs ACCEPTED.
3. For work already coded, ChatGPT sends **new Change Request** with delta and negative regression to **Codex VS Code**.
4. Codex **only** edits code, reruns actual tests, reports PR diff; ChatGPT reviews and updates documentation.
5. Owner approves merge/deploy separately.

**Do not fix a source/document inconsistency by silently changing business logic in Codex.**

## 5. Minimal evidence vocabulary

| Label | Valid only when |
|---|---|
| **SPECIFIED** | Task/acceptance criteria in Markdown |
| **IMPLEMENTED** | Source diff exists, task behavior evidenced |
| **TESTED** | Commands and test results shown from environment |
| **REVIEWED** | Diff/PR compared against source-of-truth and security |
| **ACCEPTED** | Owner approved completed scope |
| **DEPLOYED** | Target environment, URL/release/version and runbook verified |

A blueprint or a source diff alone must not become "TESTED", "ACCEPTED" or "DEPLOYED".

---

[19A Protocol](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [19C Work Orders](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md).
