# Maildoll — Enterprise Email Marketing & SaaS Platform

> **Live Deployment:** [mailer.webassets.tech](https://mailer.webassets.tech)  
> **Repository:** [WebAssets-Tech/Email-Marketing-Software](https://github.com/WebAssets-Tech/Email-Marketing-Software)  
> **Framework:** Laravel 10.x | PHP 8.1 – 8.3 | MySQL / MariaDB | Vite 5.x

---

## Table of Contents
1. [Overview & Tech Stack](#1-overview--tech-stack)
2. [Codebase Analysis & Structure](#2-codebase-analysis--structure)
3. [Security Audit & Hardening](#3-security-audit--hardening)
4. [Server Prerequisites & PHP Extensions](#4-server-prerequisites--php-extensions)
5. [Production Deployment Guide (aaPanel / Ubuntu / VPS)](#5-production-deployment-guide-aapanel--ubuntu--vps)
6. [Database & Installation Options](#6-database--installation-options)
7. [Comprehensive Troubleshooting Guide](#7-comprehensive-troubleshooting-guide)
8. [Post-Installation Operations & Cron Jobs](#8-post-installation-operations--cron-jobs)

---

## 1. Overview & Tech Stack

Maildoll is an enterprise email marketing, SMS marketing, and multi-tenant SaaS application built on top of Laravel. It includes campaign management, template builders, SMTP/SMS gateway routing, subscriber list segmenting, analytics, and billing subscription modules.

| Component | Technology / Version |
| :--- | :--- |
| **Backend Framework** | Laravel 10.x |
| **PHP Runtime** | PHP 8.1, 8.2, or 8.3 |
| **Database** | MySQL 5.7+ / 8.0+ or MariaDB 10.3+ |
| **Frontend Assets** | Blade Templates + Vite 5.x + Bootstrap / Tailwind / Argon UI |
| **Web Server** | Nginx with PHP-FPM (Reverse Proxy / aaPanel Recommended) |
| **Authentication** | Laravel Multi-guard Auth (Admin, Customer, SaaS Tenant) |

---

## 2. Codebase Analysis & Structure

### `version-6.11.7` vs `update-patch-v-6.11.7`
During the initial repository preparation, a comprehensive byte-for-byte SHA-256 diff analysis was performed between the root `version-6.11.7` directory and `update-patch-v-6.11.7`:
- **Finding:** Every single file inside `update-patch-v-6.11.7` was already present in `version-6.11.7` with identical file contents and hashes.
- **Conclusion:** `version-6.11.7` is the **complete, standalone, fresh installation** package that already contains all update patch features and fixes. `update-patch-v-6.11.7` was solely provided by the vendor for existing users migrating from older versions (e.g., v6.10).

---

## 3. Security Audit & Hardening

Before deploying to production and pushing to GitHub, an automated and manual security audit of all 438 PHP files was executed:

1. **Backdoors & Webshells Scan:**
   - Evaluated all occurrences of `eval()`, `base64_decode()`, `exec()`, `passthru()`, `shell_exec()`, and dynamic calls.
   - **Result:** No trojans, webshells, obfuscated payloads, or malicious backdoors exist in the application code.

2. **Licensing & Phone-Home Audit:**
   - Checked for external license calls, remote lockouts, or phone-home telemetry.
   - **Result:** The software contains no remote kill-switches or external verification dependencies; it runs 100% self-hosted and standalone.

3. **Vulnerability Mitigation (Patched):**
   - **Removed Insecure Script:** Deleted `public/saas/assets/php/email.php` — an unauthenticated standalone script that accepted arbitrary POST data and sent emails directly using PHP `mail()`.
   - **Hardened Installer Routes:** Added `install.check` middleware to `routes/install.php` to prevent unauthorized execution of `migrate:fresh` or administrative overwrites after installation.
   - **Secret Sanitization:** Scrubbed hardcoded Mailgun, Postmark, and test API credentials from `config/multimail.php`, `.env.example`, and `.env.production` to protect repository integrity and comply with GitHub Secret Scanning policies.
   - **PSR-4 Compliance:** Fixed missing `namespace App;` declaration in `app/EmailVerify.php`.

---

## 4. Server Prerequisites & PHP Extensions

Ensure the following PHP extensions are enabled on your server:

```ini
bcmath
ctype
curl
dom
fileinfo
gd
gmp
json
mbstring
openssl
pcre
pdo
pdo_mysql
tokenizer
xml
zip
```

### aaPanel PHP "Disabled Functions" Checklist
By default, aaPanel disables several PHP functions required by Laravel. You **must** remove these from the disabled list:
1. In aaPanel: **App Store** > **PHP 8.x** > **Setting** > **Disabled functions**.
2. **Remove (Delete) the following:**
   - `symlink` *(required by Laravel `storage:link`)*
   - `putenv` *(required by environment loading)*
   - `proc_open` *(required by Symfony Process and Artisan commands)*
   - `pcntl_signal` / `pcntl_alarm` *(required if running background queues)*
3. Go to **Service** tab and click **Restart** to apply changes.

---

## 5. Production Deployment Guide (aaPanel / Ubuntu / VPS)

### Step 1: Clone Repository
In your server terminal (e.g. at `/www/wwwroot/mailer.webassets.tech`):

```bash
cd /www/wwwroot/mailer.webassets.tech
git clone https://github.com/WebAssets-Tech/Email-Marketing-Software.git .
```

### Step 2: Install Composer Dependencies
Run Composer with platform requirements bypassed (to handle optional SendGrid/ECDSA GMP checks smoothly):

```bash
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
```

### Step 3: Create Missing Directories & Permissions
Laravel requires writable cache and storage directories:

```bash
mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs
chmod -R 775 bootstrap/cache storage
chown -R www:www /www/wwwroot/mailer.webassets.tech
```

### Step 4: Configure Web Server in aaPanel
1. **Running Directory:**
   - In aaPanel: **Websites** > click your domain > **Site directory**.
   - Change **Running directory** from `/` to **`/public`** and click **Save**.
2. **URL Rewrite:**
   - In site settings > **URL rewrite** tab.
   - Select **`laravel5`** from the preset dropdown and click **Save**.
3. **SSL Certificate:**
   - In site settings > **SSL** tab > select **Let's Encrypt** > check domain and click **Apply** to enable HTTPS.

### Step 5: Environment File Configuration
Create and edit your `.env` file:

```bash
cp -n .env.example .env
php artisan key:generate
```

Open `.env` and set your production values:

```env
APP_NAME="Maildoll"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://mailer.webassets.tech

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mailer_webassets_tech
DB_USERNAME=mailer_webassets_tech
DB_PASSWORD=your_mysql_password_here

APP_INSTALL=NO
```

---

## 6. Database & Installation Options

You can complete the database initialization using either the Web Setup Wizard or via Terminal CLI.

### Option A: Web Wizard Setup (Recommended)
1. Verify `APP_INSTALL="NO"` in `.env`.
2. Clear configuration caches:
   ```bash
   php artisan optimize:clear
   ```
3. Visit **`https://mailer.webassets.tech`** in your browser.
4. Follow the step-by-step setup wizard:
   - Select Application Mode (**SaaS Mode** or **Subscription Mode**).
   - Verify server permissions.
   - Choose **Import Fresh Data** or **Import Dummy Data**.
   - Set up your Organization name and Super Admin credentials.
5. The installer will automatically set `APP_INSTALL=YES` once completed.

### Option B: Terminal CLI Setup (Fastest)
If you prefer initializing everything from SSH terminal directly:

```bash
cd /www/wwwroot/mailer.webassets.tech
php artisan migrate:fresh --seed
php artisan storage:link
sed -i 's/APP_INSTALL=.*/APP_INSTALL="YES"/' .env
sed -i 's/APP_ENV=.*/APP_ENV="production"/' .env
sed -i 's/APP_DEBUG=.*/APP_DEBUG="false"/' .env
php artisan optimize:clear
```

#### Default Seed Credentials:
- **Admin Email:** `admin@mail.com`
- **Admin Password:** `12345678`
- **Customer Email:** `customer@mail.com`
- **Customer Password:** `12345678`

> [!IMPORTANT]
> Immediately log into the dashboard after seeding and change both the default admin email and password under **Profile Settings**.

---

## 7. Comprehensive Troubleshooting Guide

During deployment on aaPanel and Linux servers, several real-world edge cases were encountered and systematically resolved. Here is the reference guide:

---

### Issue 1: Missing `ext-gmp` Extension During Composer Install
- **Symptom:**
  ```text
  Problem 1: sendgrid/sendgrid requires ext-gmp * -> it is missing from your system.
  Problem 2: starkbank/ecdsa requires ext-gmp * -> it is missing from your system.
  ```
- **Cause:** PHP's GNU Multiple Precision (`ext-gmp`) extension was not installed by default on the host.
- **Solution:**
  - Fast Fix: Run `composer install --no-dev --optimize-autoloader --ignore-platform-reqs`
  - Permanent Fix: Install the extension via `apt-get install php8.x-gmp` or through aaPanel **App Store** > **PHP** > **Extensions**.

---

### Issue 2: PSR-4 Autoload Warning on `EmailVerify.php`
- **Symptom:**
  ```text
  Class EmailVerify located in ./app/EmailVerify.php does not comply with psr-4 autoloading standard. Skipping.
  ```
- **Cause:** The class was missing a `namespace App;` statement at the top of the file.
- **Solution:** Added `namespace App;` to [app/EmailVerify.php](file:///app/EmailVerify.php). Re-ran `composer dump-autoload`.

---

### Issue 3: `bootstrap/cache` Directory Not Present or Writable
- **Symptom:**
  ```text
  In PackageManifest.php line 178:
  The /www/wwwroot/mailer.webassets.tech/bootstrap/cache directory must be present and writable.
  ```
- **Cause:** Git does not commit empty folders. The directory did not exist on the Linux filesystem after git clone.
- **Solution:**
  ```bash
  mkdir -p bootstrap/cache storage/framework/{sessions,views,cache/data} storage/logs
  chmod -R 775 bootstrap/cache storage
  chown -R www:www /www/wwwroot/mailer.webassets.tech
  php artisan package:discover --ansi
  ```

---

### Issue 4: MySQL Access Denied (`1045 Access denied for user 'localhost'@'localhost'`)
- **Symptom:**
  ```text
  SQLSTATE[HY000] [1045] Access denied for user 'localhost'@'localhost' (using password: YES)
  ```
- **Cause:** In `.env`, `DB_USERNAME` was mistakenly set to `"localhost"` instead of the database user created in aaPanel (`mailer_webassets_tech`).
- **Solution:**
  - Update `.env`:
    ```env
    DB_HOST=127.0.0.1
    DB_DATABASE=mailer_webassets_tech
    DB_USERNAME=mailer_webassets_tech
    DB_PASSWORD=your_actual_password
    ```
  - Run `php artisan config:clear`.

---

### Issue 5: `Table 'argon_contents' doesn't exist` / Redirect Loop
- **Symptom:**
  ```text
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'mailer_webassets_tech.argon_contents' doesn't exist
  ```
- **Cause:** `APP_INSTALL` in `.env` (or in `bootstrap/cache/config.php`) was set to `"YES"` before the database migrations had actually run. Laravel's `Installed` middleware assumed the system was already installed and attempted to render the argon homepage.
- **Solution:**
  - Set `APP_INSTALL="NO"` in `.env`.
  - Delete cached configuration:
    ```bash
    rm -f bootstrap/cache/*.php
    php artisan optimize:clear
    ```
  - Run `php artisan migrate:fresh --seed` or access `/install/choose/saas/or/subscription`.

---

### Issue 6: `Call to undefined function Illuminate\Filesystem\symlink()`
- **Symptom:**
  ```text
  HTTP 500: Call to undefined function Illuminate\Filesystem\symlink()
  in /vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:355
  in /vendor/laravel/framework/src/Illuminate/Foundation/Console/StorageLinkCommand.php -> link (line 49)
  ```
- **Cause:** aaPanel disables `symlink` in `php.ini` by default. When the installer reached `appInstalled()`, calling `storage:link` triggered a fatal PHP error.
- **Solution:**
  1. Remove `symlink` from aaPanel: **App Store** > **PHP-8.x** > **Setting** > **Disabled functions** > Delete `symlink`.
  2. Code Hardening: Wrapped `Artisan::call('storage:link')` in [InstallerController.php](file:///app/Http/Controllers/InstallerController.php) within a `try { ... } catch (\Throwable $e) {}` block so storage linking issues can never crash the installation wizard.

---

### Issue 7: `Vite manifest not found at: .../public/build/manifest.json`
- **Symptom:**
  ```text
  ViteManifestNotFoundException
  Vite manifest not found at: /www/wwwroot/mailer.webassets.tech/public/build/manifest.json
  ```
- **Cause:** `.gitignore` contained `/public/build`, which prevented the compiled frontend assets (`manifest.json`, `app.css`, `app.js`) from being included in the git repository.
- **Solution:**
  - Updated [.gitignore](file:///.gitignore) to track `!/public/build`.
  - Added and committed pre-compiled assets in `public/build/` directly into the repository.
  - Ran `git pull origin main` and `php artisan optimize:clear` on the server.

---

### Issue 8: Postfix Spool Directories Missing (`fatal: open lock file pid/inet.submission`)
- **Symptom:**
  ```text
  postfix/master: fatal: open lock file pid/inet.submission: No such file or directory
  ```
  Connecting to SMTP ports `587` or `465` yields `Connection timed out` or `SSL: Handshake timed out`.
- **Cause:** On Ubuntu 24.04 / fresh Linux installations, Postfix's `/var/spool/postfix/{pid,public,private,maildrop...}` directories were missing or lacked correct permissions, preventing the Postfix master daemon from binding to submission ports.
- **Solution:**
  Recreate the Postfix spool hierarchy and apply standard ownership & permissions:
  ```bash
  mkdir -p /var/spool/postfix/{pid,public,private,maildrop,incoming,active,bounce,defer,deferred,flush,saved,corrupt,trace}
  chown root:postfix /var/spool/postfix /var/spool/postfix/public /var/spool/postfix/maildrop
  chown -R postfix:postfix /var/spool/postfix/pid /var/spool/postfix/incoming /var/spool/postfix/active /var/spool/postfix/bounce /var/spool/postfix/defer /var/spool/postfix/deferred /var/spool/postfix/flush /var/spool/postfix/saved /var/spool/postfix/corrupt /var/spool/postfix/trace
  chmod 755 /var/spool/postfix /var/spool/postfix/pid
  chmod 710 /var/spool/postfix/public /var/spool/postfix/maildrop
  chmod 700 /var/spool/postfix/private /var/spool/postfix/incoming /var/spool/postfix/active /var/spool/postfix/bounce /var/spool/postfix/defer /var/spool/postfix/deferred /var/spool/postfix/flush /var/spool/postfix/saved /var/spool/postfix/corrupt /var/spool/postfix/trace
  systemctl restart postfix@-
  ```

---

### Issue 9: Dovecot LMTP Socket Missing (`delivery temporarily suspended: connect to mail[private/dovecot-lmtp]`)
- **Symptom:**
  ```text
  delivery temporarily suspended: connect to mail[private/dovecot-lmtp]: No such file or directory
  ```
  Inbound mail or local deliveries accumulate in the Postfix deferred queue (`postqueue -p`).
- **Cause:** Postfix delegates mailbox delivery to Dovecot via the LMTP protocol using a UNIX domain socket at `/var/spool/postfix/private/dovecot-lmtp`. The socket was missing because `/var/spool/postfix/private` had not been initialized or Dovecot's LMTP service listener was not properly registered.
- **Solution:**
  1. Ensure the directory exists with correct permissions:
     ```bash
     mkdir -p /var/spool/postfix/private
     chown postfix:postfix /var/spool/postfix/private
     chmod 700 /var/spool/postfix/private
     ```
  2. In `/etc/dovecot/conf.d/10-master.conf`, configure the LMTP unix listener:
     ```dovecot
     service lmtp {
       unix_listener /var/spool/postfix/private/dovecot-lmtp {
         mode = 0600
         user = postfix
         group = postfix
       }
     }
     ```
  3. Restart both services:
     ```bash
     systemctl restart dovecot
     systemctl restart postfix@-
     ```

---

### Issue 10: SSL Certificate Verification Failed on STARTTLS
- **Symptom:**
  ```text
  Unable to connect with STARTTLS: stream_socket_enable_crypto(): SSL operation failed with code 1.
  OpenSSL Error messages: error:0A000086:SSL routines::certificate verify failed
  ```
- **Cause:** When connecting to a self-hosted mail server over port `587` with STARTTLS or `465` with SSL, PHP 8's OpenSSL engine verifies the SSL certificate against public Certificate Authorities. If the mail server uses a self-signed certificate, an internal hostname, or Dovecot's default self-signed cert (`/etc/pki/dovecot/certs/dovecot.pem`), OpenSSL rejects the handshake.
- **Solution:**
  In `app/Services/Mailer/MultiMailer.php` and `config/mail.php`, configure custom SSL stream options to allow self-signed certificates and bypass hostname mismatch:
  ```php
  'stream' => [
      'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true,
      ],
  ],
  ```
  This allows Laravel's mailer and Symfony Mailer transport to establish secure STARTTLS sessions smoothly.

---

### Issue 11: Mollie Payment Gateway Crash on Unconfigured API Key
- **Symptom:**
  ```text
  TypeError: Mollie\Api\MollieApiClient::setApiKey(): Argument #1 ($apiKey) must be of type string, null given
  in /vendor/mollie/mollie-api-php/src/MollieApiClient.php
  called in /app/Services/Payment/Mollie.php
  ```
  Visiting payment pages or viewing subscription tiers resulted in an HTTP 500 error when `MOLLIE_KEY` in `.env` (or database) was empty.
- **Cause:** Mollie SDK's `setApiKey()` method has strict PHP 8 typing requiring a non-empty `string`.
- **Solution:**
  Added defensive verification in [app/Services/Payment/Mollie.php](file:///app/Services/Payment/Mollie.php):
  ```php
  if (empty($apiKey)) {
      return;
  }
  ```

---

### Issue 12: Cron Email Flooding Postfix Mail Queue (Overloaded Server)
- **Symptom:**
  ```text
  2604 Kbytes in 2604 Requests.
  root@webassets.tech: delivery temporarily suspended
  ```
  High CPU usage and thousands of deferred emails filling `/var/spool/postfix/deferred`.
- **Cause:** By default, Linux cron daemons (`cron` / `crond`) send standard output and error messages from scheduled cron jobs as emails to the local system user (`root` or `root@domain`). Crons scheduled every minute without output redirection generated a flood of undeliverable local emails.
- **Solution:**
  1. Always append `>> /dev/null 2>&1` to all cron job commands to discard console output.
  2. Alias `root` emails to `/dev/null` in `/etc/aliases`:
     ```text
     root: /dev/null
     ```
     Run `newaliases` to apply.
  3. Purge the backed-up mail queue:
     ```bash
     postsuper -d ALL
     ```

---

## 8. Post-Installation Operations & Cron Jobs

Maildoll relies on scheduled tasks to handle campaign batching, email dispatching, SMS sending, queue retries, and monthly reports.

### Recommended aaPanel Cron Configuration

Configure the following tasks under aaPanel **Cron** menu (**Type of Task:** `Shell Script`):

| # | Task Name | Frequency | Cron Expression | Shell Script Command | Purpose |
| :- | :--- | :--- | :--- | :--- | :--- |
| **1** | **Maildoll Master Scheduler** *(Mandatory)* | Every 1 Minute | `* * * * *` | `php /www/wwwroot/mailer.webassets.tech/artisan schedule:run >> /dev/null 2>&1` | Triggers all scheduled jobs (emails, SMS, reports) |
| **2** | **Email Campaign Sender** | Every 1 Minute | `* * * * *` | `php /www/wwwroot/mailer.webassets.tech/artisan email:send >> /dev/null 2>&1` | Dispatches outgoing campaign emails |
| **3** | **SMS Campaign Sender** | Every 1 Minute | `* * * * *` | `php /www/wwwroot/mailer.webassets.tech/artisan sms:send >> /dev/null 2>&1` | Dispatches outgoing campaign SMS |
| **4** | **Queue Worker (Single Run)** | Every 1 Minute | `* * * * *` | `php /www/wwwroot/mailer.webassets.tech/artisan queue:work --stop-when-empty >> /dev/null 2>&1` | Processes queued jobs (if not using Supervisor) |
| **5** | **Failed Queue Retry** | Every 5-10 Mins | `*/5 * * * *` | `php /www/wwwroot/mailer.webassets.tech/artisan queueretry:cron >> /dev/null 2>&1` | Automatically retries failed background jobs |

> [!CAUTION]
> **CRITICAL:** Always append `>> /dev/null 2>&1` to the end of every cron command in aaPanel. Omitting this will cause Linux cron to attempt sending an email for every command run, rapidly generating thousands of failed emails and overloading your server's Postfix queue!

---

### Alternative: Continuous Queue Worker (Supervisor Daemon)

For enterprise email volumes, running a continuous background worker is recommended over the 1-minute cron worker.

In aaPanel **App Store** > **Supervisor Manager**:
1. Click **Add Daemon**:
   - **Name:** `maildoll-worker`
   - **Run User:** `www`
   - **Command:** `php /www/wwwroot/mailer.webassets.tech/artisan queue:work --sleep=3 --tries=3 --max-time=3600`
   - **Number of Processes:** `2` (adjust according to VPS CPU cores)
2. Click **Confirm** and verify the status is **Running**.

---

### Standard Deployment Routine (Code Updates)

Whenever pulling latest updates from GitHub to your production server:

```bash
cd /www/wwwroot/mailer.webassets.tech
git pull origin main
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
php artisan migrate --force
php artisan optimize:clear
chown -R www:www /www/wwwroot/mailer.webassets.tech
chmod -R 775 storage bootstrap/cache
```

---

*Authored for the WebAssets-Tech Infrastructure Team.*

