# AGENTS.md

## Version
v1.0.0

## Scope
This document applies to `app/V1/Modules/ReportCatalog/Report/**`.

## Purpose
This file defines module-specific rules for the `Report` module.

## Module rules
- In the report submission flow, a `Report` is created together with its related `Listing` context in the same request/transaction. Do not redesign this flow into a standalone report-only creation path unless the user explicitly asks for that architectural change.
- Treat the submitted listing payload as part of report intake responsibility: when no matching listing exists, create it; when an existing listing is matched, keep report creation linked to that listing lifecycle.
