# 20E-01 — Temporary MySQL Administrator Login Setup (local user action)

> 28 October 2026 · BLOCKED: administrator credentials required. **The owner has explicitly authorized creation of only** `iches_unisya_test` and `iches_test` **on the local MySQL server.** This step does not authorize accessing, altering or enumerating any other database. No application coding GO.

> **OPTIONAL FALLBACK ONLY:** superseded as the default by [20E-02 Herd Free + phpMyAdmin](20E-02-HERD-FREE-PHPMYADMIN-WORKFLOW.md). The owner normally administers MySQL via phpMyAdmin. **Do not require `mysql_config_editor` or temporary admin login-path** if phpMyAdmin works. This historical CLI procedure may be used only if explicitly requested.

## Why this one local action is needed

- MySQL Server **9.7.1** runs at `127.0.0.1:3306`. Installed client: `C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql.exe`.
- A read-only no-password attempt for `root` failed with **ERROR 1045 (Access denied)**.
- A separate read-only default-profile attempt also failed with **ERROR 1045**.
- No SQL changes, credential reading, guessing, database creation or grants were attempted. We cannot safely provision a test database without authenticated admin authorization.

## Required action in the user's local VS Code Terminal

In **VS Code → Terminal → New Terminal**, paste this **one command**:

```powershell
& "C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql_config_editor.exe" set --login-path=iches_test_provision --host=127.0.0.1 --port=3306 --user=root --password
```

When MySQL asks for the **administrator password**, **type it only in that local terminal prompt**. It will not show the password as you type. **Never paste a password or login file into ChatGPT, GitHub, Codex chat or screenshots.** If the configured administrator username is not `root`, change only the `--user=root` argument to the correct **administrative username**.

This creates a **temporary local MySQL login-path file** in the Windows user profile. MySQL's stored login-path password is **obfuscated, not a secure encrypted vault**; use it only for this setup and remove it promptly when the provisioning is done. It does not run SQL or change database contents.

### What to report back
Reply **“Login MySQL sementara sudah disiapkan”** if the command succeeded; **do not include password or full sensitive terminal output**. If you do not know the administrator password, report that fact instead; we will not bypass authentication or reset server access without explicit permission.

### What ChatGPT will do only after temporary login is ready
1. Verify admin connection without disclosing secrets; ensure account has appropriate limited DDL/user-grant authority.
2. Verify the requested test schema and test user do **not** already exist. If either exists, **stop and request a safe collision decision**, never overwrite.
3. Create only `iches_unisya_test` (InnoDB-compatible defaults) and `iches_test` limited to that schema with a strong generated password stored **locally outside GitHub/ICHES repository** using Windows user-scoped secret protection. Do not echo password in process output/arguments/logs.
4. Verify connection and schema/permission scope, then **remove** temporary admin login path:
   ```powershell
   & "C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql_config_editor.exe" remove --login-path=iches_test_provision
   ```
5. Update [20E readiness gates](20E-MYSQL-ISOLATED-TEST-DB-READINESS.md) and the 19F status board using only non-sensitive evidence. Application migrations remain **NOT_RUN until a separate named GO SPRINT**.

**No production data, no existing databases and no other MySQL accounts may be modified.**
