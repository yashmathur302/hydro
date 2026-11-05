# SQL updates for hosting without a terminal

Each file applies one migration to an existing database through phpMyAdmin
(select the database > Import). Import them in number order, once each; every
file is also safe to import again. A fresh install uses the full database.sql
from the deployment package instead.

| File | Migration |
| --- | --- |
| 001-page-sections.sql | 2026_11_06_000001_create_page_sections_table |
