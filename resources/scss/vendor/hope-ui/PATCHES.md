# Changes to the vendored Hope UI source

Source: https://github.com/iqonicdesignofficial/hope-ui-html-admin-dashboard
(`src/assets/scss/bootstrap`, `src/assets/scss/hope-ui-design-system` and
`src/assets/scss/custom/auth/_authentication.scss`),
MIT licence (see LICENSE). Copied unchanged except:

1. Every literal `!important` was removed (project rule: no `!important`).
   Bootstrap's switchable form `if($enable-important-utilities, !important, null)`
   is kept and switched off in `resources/scss/admin.scss`.
2. `resources/scss/admin.scss` imports only the parts the admin uses and does
   not import `hope-ui-design-system/helper/fonts` (Google Fonts); the Inter
   font is self-hosted through `@fontsource-variable/inter`.
