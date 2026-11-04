# Product Showcase Website

A product showcase website with an admin panel. Visitors browse products and
send enquiries (form or WhatsApp). There is no cart, no payment and no visitor
login. Only the single admin logs in, to manage content.

## Stack

- Laravel 12, PHP 8.3+, MySQL
- Blade templates with hand-written CSS and vanilla JavaScript (no CSS/JS framework)
- Vite to minify and fingerprint assets; fonts are self-hosted

## Built so far (step 1: foundation)

- **Admin login** at `/admin/login`: login, logout, forgot/reset password, change password, edit profile
- **Site settings**: site name, tagline, logo, favicon, contact details, WhatsApp number,
  enquiry email, social links, default SEO, GA4 Measurement ID, Meta Pixel ID
- **Tracking**: the admin enters only the IDs; the official GA4 and Pixel code is generated
- **SEO base**: title, description, keywords, canonical, Open Graph and Twitter tags on every page
- **Security**: see below
- **Friendly error pages**: 403, 404, 419, 429, 500, 503

## Local setup

```bash
composer install
npm install
cp .env.example .env        # then set APP_ENV=local, APP_DEBUG=true and your DB details
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan admin:create    # creates the one admin account
npm run dev                 # or: npm run build
php artisan serve
```

Run the tests with `php artisan test`.

## Deploying to shared hosting (cPanel)

1. Run `npm run build` on your computer. Shared hosting usually has no Node.js, so upload
   the generated `public/build` folder together with the code.
2. Upload the project **outside** `public_html` (for example `~/hydro`) and point the
   domain's document root at `~/hydro/public`. If the document root cannot be changed,
   copy the contents of `public` into `public_html` and update the two paths in `index.php`.
3. Create a MySQL database and user in cPanel and put the details in `.env`.
4. In `.env` keep `APP_ENV=production`, `APP_DEBUG=false` and `SESSION_SECURE_COOKIE=true`
   (the site must run on HTTPS), and fill in the SMTP mail settings.
5. Using the cPanel Terminal:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan admin:create
   php artisan optimize
   ```
6. Make sure `storage/` and `bootstrap/cache/` are writable by PHP.

After every later deployment run `php artisan migrate --force` and `php artisan optimize`.

## Security measures

- Admin login is rate limited (5 failed attempts per email + IP, then a 5-minute lockout,
  plus 10 requests per minute per IP). Password reset is limited too.
- Passwords: at least 12 characters with mixed case, a number and a symbol; in production
  they are also checked against known data breaches.
- Sessions are encrypted, HttpOnly, SameSite=Lax and Secure on HTTPS; the session is
  regenerated on login and invalidated on logout; changing the password signs out other browsers.
- CSRF protection on every form. All input is validated server-side in Form Request classes.
- Content Security Policy with a per-request nonce: only our own files, plus the Google
  Analytics and Meta Pixel hosts, can load scripts.
- Other headers: X-Frame-Options, X-Content-Type-Options, Referrer-Policy,
  Permissions-Policy, and HSTS on HTTPS. Admin pages are sent with `no-store` and `noindex`.
- Uploads: only PNG/JPG/WebP logos and PNG/ICO favicons, size-limited, checked by real file
  type, saved under random names. SVG is not accepted (it can carry scripts).
- Logins, failed logins, lockouts, logouts, password changes and settings changes are written to
  `storage/logs/security.log`. Passwords and tokens are never logged.

## Code layout

| Path | Purpose |
| --- | --- |
| `app/Enums/SiteSettingKey.php` | Every allowed setting key |
| `app/Services/SiteSettingService.php` | Cached reading and writing of settings |
| `app/Support/Seo/` | `SeoMeta` value object and the `SeoMetaFactory` that fills in defaults |
| `app/Http/Middleware/SecurityHeaders.php` | CSP and the other security headers |
| `app/Listeners/LogSecurityEvents.php` | Security log for authentication events |
| `resources/views/components/admin/` | Reusable admin components (layout, card, form fields, buttons) |
| `resources/views/components/site/` | Public layout, SEO tags and tracking code |
| `resources/css/admin.css`, `resources/js/admin/` | Admin styles and scripts |
| `resources/css/site.css`, `resources/js/site.js` | Public website styles and scripts (built page by page) |
