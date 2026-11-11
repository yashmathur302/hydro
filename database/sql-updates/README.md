# SQL updates for hosting without a terminal

Each file applies one migration to an existing database through phpMyAdmin
(select the database > Import). Import them in number order, once each; every
file is also safe to import again. A fresh install uses the full database.sql
from the deployment package instead.

| File | Migration |
| --- | --- |
| 001-page-sections.sql | 2026_11_06_000001_create_page_sections_table |
| 002-industries-and-clients.sql | 2026_11_07_000001_create_industries_table, 2026_11_07_000002_create_clients_table |
| 003-certificates.sql | 2026_11_10_000001_create_certificates_table |
| 004-products.sql | (content only: the product import, sent separately, not a migration) |
| 005-clear-session-ip.sql | 2026_11_11_000001_clear_session_request_information |
