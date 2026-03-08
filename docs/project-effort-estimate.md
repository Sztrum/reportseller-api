# Project Effort Estimate

## Purpose
Track cumulative recommended human development effort for the whole project using modules-only scope.

## Baseline
- Date: 2026-03-03
- Scope snapshot: `app/V1/Modules/**` (172 files, ~7,197 LOC in PHP/TS/JS/Blade/MD)
- Estimated effort delta added: 150 h - 250 h
- Running total: 157 h 30 min - 262 h

## Update Rule
- On each new task, add a new `Entry` with estimated task delta (`X h Y min - A h B min`) and update `Running total`.
- Use only `app/V1/Modules/**` as estimation scope.
- Exclude `app/V1/Core/**`, `app/V1/Shared/**`, and shared assets/frontend infrastructure from effort totals.
- On each commit communication, report the latest `Running total` value.

## Entries
1. 2026-03-03: baseline recalibrated to modules-only scope (previous broad-scope estimate replaced).
2. 2026-03-06: report store hardens default `new` status resolution with domain validation and narrows listing URL fallback lookup to the resolved platform context.
   - Estimated effort delta added: 1 h 30 min - 3 h
   - Running total: 151 h 30 min - 253 h
3. 2026-03-07: report store improvements added model `belongsTo` relations, enforced seller type enum usage, and removed attachment persistence from the report store flow.
   - Estimated effort delta added: 3 h - 5 h
   - Running total: 154 h 30 min - 258 h
4. 2026-03-07: listing change proposals were decoupled from reports by removing `report_id` and keeping proposals keyed only by listing context.
   - Estimated effort delta added: 1 h 30 min - 2 h
   - Running total: 156 h - 260 h
5. 2026-03-08: anonymous report authors were changed from IP-based storage to optional email-based reuse, allowing repeated emails to link new reports to an existing author record.
   - Estimated effort delta added: 1 h 30 min - 2 h
   - Running total: 157 h 30 min - 262 h
