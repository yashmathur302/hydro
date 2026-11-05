# Lucky Instru Hydro — Product Showcase Website

A product showcase website with an admin panel. Visitors browse products and
send enquiries (form or WhatsApp). There is no cart, no payment and no visitor
login. Only the single admin logs in, to manage content.

## Stack

- Laravel 12, PHP 8.3+, MySQL
- Blade templates with hand-written CSS and vanilla JavaScript (no CSS/JS framework)
- Vite to minify and fingerprint assets; fonts are self-hosted

## Built so far

- **Admin login** at `/admin/login`: login, logout, forgot/reset password, change password, edit profile
- **Products**: categories (one level), products with gallery images (auto-converted to WebP,
  large + thumbnail), specification table, brochure PDF, featured/active flags, sort order, SEO fields
- **Enquiries**: product enquiries and Contact Us messages in two inboxes, with unread counts,
  status (new / in progress / closed), private notes, call / WhatsApp / email shortcuts. Every new
  enquiry is saved and emailed to the address in Site settings
- **Static pages**: privacy policy, terms and conditions (created as drafts) and any others,
  written with simple Markdown formatting
- **Public form endpoints**: `POST /enquiries` and `POST /contact`, rate limited (3 per minute,
  30 per day per IP) with a hidden spam trap. The forms themselves are added with the page designs
- **Site settings**: site name, tagline, logo, favicon, contact details, WhatsApp number,
  enquiry email, social links, default SEO, GA4 Measurement ID, Meta Pixel ID, Google Search Console verification
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

## Deploying with cPanel Git Version Control (current staging: public_html/hydro)

`.cpanel.yml` copies the application from the git checkout into the live folder
(`DEPLOYPATH`) when you click **Manage → Pull or Deploy → Update from Remote**, then
**Deploy HEAD Commit**. It uses the single-folder layout from `deploy/subfolder`.

- The cPanel repository path must be **outside** `public_html` (e.g. `/home2/USER/repositories/hydro`).
- A deploy never touches `.env`, `vendor/`, `uploads/` or `storage/`. It refuses to run if the
  target has no `.env` or `vendor/`, so a wrong `DEPLOYPATH` cannot create a half-installed site.
- `vendor/` is not in git: when `composer.lock` changes, a new `vendor` upload is provided.
- New database tables are delivered as SQL files to import in phpMyAdmin.

## Deploying to shared hosting (cPanel) manually

1. The compiled CSS/JS in `public/build` is committed, because the hosting has no Node.js.
   After changing anything in `resources/css` or `resources/js`, run `npm run build` locally
   and commit the updated `public/build` folder.
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
- Product and category images are re-encoded to WebP, which also strips EXIF data and anything
  hidden in the file. Brochures must have a real PDF signature, not just a .pdf name.
- Page and product text uses Markdown with raw HTML escaped and `javascript:` links removed.
- Enquiry emails are plain HTML with every visitor value escaped. Visitors can never set an
  enquiry's status, notes or type.
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
| `app/Services/ProductService.php` | Saves products with images, specifications and brochure in one transaction |
| `app/Services/Images/WebpImageProcessor.php` | Resizes and converts uploads to WebP with GD |
| `app/Services/EnquiryService.php` | Saves website enquiries and emails the site owner |
| `app/Support/Content/MarkdownRenderer.php` | Safe Markdown to HTML for pages and product descriptions |
| `app/Http/Middleware/SecurityHeaders.php` | CSP and the other security headers |
| `app/Listeners/LogSecurityEvents.php` | Security log for authentication events |
| `resources/views/components/admin/` | Reusable admin components (layout, card, form fields, buttons) |
| `resources/views/components/site/` | Public layout, SEO tags and tracking code |
| `resources/css/admin.css`, `resources/js/admin/` | Admin styles and scripts |
| `resources/css/site.css`, `resources/js/site.js` | Public website styles and scripts (built page by page) |
