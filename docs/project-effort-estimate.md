# Project Effort Estimate

## Purpose
Track cumulative recommended human development effort for the whole project using modules-only scope.

## Baseline
- Date: 2026-03-03
- Scope snapshot: `app/V1/Modules/**` (172 files, ~7,197 LOC in PHP/TS/JS/Blade/MD)
- Estimated effort delta added: 150 h - 250 h
- Running total: 151 h 30 min - 253 h

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
