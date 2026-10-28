# 20C-02 — Codex Read-Only Handshake (No Coding)

> Status: **READY TO TEST** once owner has an open Codex pane in the correct VS Code workspace. This is a **read-only environment verification**, not an ICHES code work order and not a coding GO.

## Goal
Confirm the **running Codex VS Code session** can see the correct repository, observe active Git branch, and read all four repository Skills. Nothing may be installed, created, modified, committed, merged, migrated or deployed.

## Paste exactly this in Codex chat

```text
ICHES-2027 / PRE-03 — READ-ONLY HANDSHAKE (BUKAN GO CODING)

Anda sedang berada pada workspace ICHES. Tugas ini hanya AUDIT BACA-SAJA.

1. Verifikasi current working folder, git remote (jangan tampilkan credential), branch, HEAD, dan git status. Target folder: D:\PINJAM-AFNAN\iches-unisya.
2. Baca AGENTS.md, PLANS.md, WORKSPACE.md, dan docs/19F-CURRENT-SPRINT-BOARD.md hanya untuk menemukan aturan current task dan larangan coding.
3. Temukan dan baca file SKILL.md dari:
   - .agents/skills/iches-codex-engineering/
   - .agents/skills/iches-public-frontend/
   - .agents/skills/iches-backoffice-ui/
   - .agents/skills/iches-code-verification/
   Tampilkan nama keempat Skill dan satu kalimat fungsi masing-masing. Jelaskan apakah file berhasil terbaca; jangan mengklaim auto-trigger Skill telah teruji bila belum.
4. Konfirmasikan bahwa Sprint ICH-SPRINT-01 masih NO_GO. Jangan membuat branch, file, log atau kode. Jangan install package, jalankan migration, fetch credential, atau menjalankan tes yang menulis database.

FORMAT BALASAN SINGKAT:
WORKSPACE:
REMOTE (nama repository, tanpa token):
BRANCH / HEAD / WORKTREE:
AGENTS / PLANS / BOARD: READABLE atau BLOCKED
SKILLS (4): READABLE atau BLOCKED + nama
CODEX SIGNED IN / EXECUTION: VERIFIED (jika terbukti) atau NOT_VERIFIED
GO CODING: NO
ISSUES / NEXT ACTION:
```

## Human verification
Codex shows 4 Skills readable, checks correct branch and does not edit anything. User shares **its textual result** with ChatGPT (without credential). ChatGPT updates 19F board after reviewing actual evidence, not from a screenshot of a chat input alone.

## Known pre-coding product conflict for PRE-02

Decision Register ~docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md~ marks **NEW-003: Email OTP / Magic Link without a participant password — DISETUJUI**, whereas later **PROP-067 / RD-06** proposes Laravel email/password/session login as a technical draft. **Do not silently overwrite the approved passwordless participant decision.** The chosen Laravel auth mechanism can still be session-based, but implementation mode for participants must honor the approved OTP/Magic Link feature unless owner explicitly revises it; staff account and MFA details remain separate. This is an **open implementation reconciliation**, not a new policy decision. Record in the technical owner decision package before GO.

**Status after this document is created:** local clone and VS Code Codex UI already observed; **Skills accessibility through Codex itself remains untested** until actual handshake output is provided.
