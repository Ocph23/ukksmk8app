# UKK App — Migration Plan

## AngularJS/Blade → Vue 3 + Inertia + shadcn-vue

> **Project:** Sistem Informasi Uji Kompetensi Keahlian — SMK Negeri 8 Teknologi Informasi dan Komunikasi, Jayapura
> **Created:** 2026-04-13
> **Status:** Planning

---

## 1. Current System Overview

### Technology Stack (As-Is)

| Layer        | Technology                              |
| ------------ | --------------------------------------- |
| Framework    | Laravel 10.10                           |
| PHP          | ^8.1                                    |
| Frontend SPA | AngularJS 1.x (legacy)                  |
| Templating   | Blade (server-side, static shells)      |
| UI Framework | Bootstrap 4.4.1 + Purple Admin Template |
| Build Tool   | Vite 5 (minimal — only Axios)           |
| API          | RESTful (Laravel Sanctum auth)          |
| Database     | MySQL                                   |

### Architecture Pattern

```
┌─────────────────────────────────────────────┐
│  Browser                                    │
│                                             │
│  Blade (static shell)                       │
│  └─ AngularJS 1.x (SPA via AJAX)           │
│     ├─ 9 Services (API calls)               │
│     └─ 9 Controllers (UI logic)             │
│                                             │
│  Bootstrap 4 + Purple Admin (styling)       │
└──────────────────┬──────────────────────────┘
                   │ AJAX (Sanctum Auth)
┌──────────────────▼──────────────────────────┐
│  Laravel 10                                 │
│                                             │
│  routes/web.php  → Blade views (auth)       │
│  routes/api.php  → REST API (Sanctum)       │
│                                             │
│  Controllers: 10 (CRUD + Reports)           │
│  Models: 12                                  │
└──────────────────┬──────────────────────────┘
                   │ Eloquent
┌──────────────────▼──────────────────────────┐
│  MySQL (ukkdb)                              │
└─────────────────────────────────────────────┘
```

### Current Routes Summary

**Web Routes** (`routes/web.php`):
| Route | Purpose | Returns |
|---|---|---|
| `GET /` | Welcome page | Blade view |
| `GET /auth/login` | Login form | Blade view |
| `POST /auth/login` | Login action | Redirect |
| `GET /auth/logout` | Logout | Redirect |
| `GET /auth/register` | Registration | Blade view |
| `POST /auth/register` | Registration action | Redirect |
| `GET /admin` | Dashboard | Blade view |
| `GET /admin/jurusan` | Department page | Blade view |
| `GET /admin/tahunajaran` | Academic year page | Blade view |
| `GET /admin/aksesor` | Assessor page | Blade view |
| `GET /admin/siswa` | Student page | Blade view |
| `GET /admin/siswa/{id}` | Student detail | Blade view |
| `GET /admin/paket` | Package page | Blade view |
| `GET /admin/paket/{id}` | Package detail | Blade view |
| `GET /admin/penilaian/{id}` | Assessment page | Blade view |
| `GET /admin/lkelulusan` | Graduation report | Blade view |
| `GET /admin/laksesor` | Assessor report | Blade view |

**API Routes** (`routes/api.php`):
| Resource | Endpoints | Methods |
|---|---|---|
| Siswa | `/siswa`, `/siswa/{id}`, `/siswa/bytahunajaran/{id}`, `/siswa/bynis/{nis}` | GET, POST, PUT, DELETE |
| TahunAjaran | `/tahunajaran`, `/tahunajaran/{id}` | GET, POST, PUT, DELETE |
| Jurusan | `/jurusan`, `/jurusan/{id}` | GET, POST, PUT, DELETE |
| Aksesor | `/aksesor`, `/aksesor/{id}` | GET, POST, PUT, DELETE |
| Paket | `/paket`, `/paket/{id}`, `/paket/bytahunajaran/{id}`, `/paket/{id}/detail` | GET, POST, PUT, DELETE |
| Penilaian | `/penilaian`, `/penilaian/{id}`, `/penilaian/siswa/{id}` | GET, POST, PUT, DELETE |
| Laporan | `/laporan/{ta}/{jurusan}`, `/laporan/{ta}` | GET |

### Current Views (Blade Files)

| File                         | Layout        | Purpose                              |
| ---------------------------- | ------------- | ------------------------------------ |
| `welcome.blade.php`          | None          | Landing page                         |
| `main.blade.php`             | Master layout | Contains AngularJS app shell         |
| `admin.blade.php`            | Admin layout  | Sidebar + Navbar + AngularJS content |
| `home.blade.php`             | Extends admin | Dashboard content                    |
| `jurusan.blade.php`          | Extends admin | Department management (AngularJS)    |
| `tahunajaran.blade.php`      | Extends admin | Academic year management (AngularJS) |
| `aksesor.blade.php`          | Extends admin | Assessor management (AngularJS)      |
| `siswa.blade.php`            | Extends admin | Student listing (AngularJS)          |
| `siswadetail.blade.php`      | Extends admin | Student detail (AngularJS)           |
| `paket.blade.php`            | Extends admin | Package listing (AngularJS)          |
| `paketdetail.blade.php`      | Extends admin | Package detail (AngularJS)           |
| `penilaian.blade.php`        | Extends admin | Assessment page (AngularJS)          |
| `laporanKelulusan.blade.php` | Extends admin | Graduation report (AngularJS)        |
| `laporanAksesor.blade.php`   | Extends admin | Assessor report (AngularJS)          |
| `auths/login.blade.php`      | Extends main  | Login form                           |
| `auths/register.blade.php`   | Extends main  | Registration form                    |

### AngularJS Module Structure

**Services** (`public/assets/js/apps/services/`):

- `service.js` — Base service
- `helperService.js` — Utility functions
- `jurusanService.js` — Department API calls
- `tahunajaranService.js` — Academic year API calls
- `aksesorService.js` — Assessor API calls
- `paketService.js` — Package API calls
- `siswaService.js` — Student API calls
- `penilaianService.js` — Assessment API calls
- `laporanService.js` — Report API calls

**Controllers** (`public/assets/js/apps/controllers/`):

- `controller.js` — Base controller
- `jurusanController.js`
- `tahunajaranController.js`
- `aksesorController.js`
- `paketController.js`
- `siswaController.js`
- `siswaDetailController.js`
- `paketDetailController.js`
- `penilaianController.js`
- `laporanController.js`

### Database Models

| Model           | Table           | Purpose              |
| --------------- | --------------- | -------------------- |
| User            | users           | System users (admin) |
| Siswa           | siswa           | Students             |
| Jurusan         | jurusan         | Departments          |
| TahunAjaran     | tahunajaran     | Academic years       |
| Aksesor         | aksesor         | Assessors            |
| Paket           | paket           | Assessment packages  |
| Kompetensi      | kompetensi      | Competencies         |
| Penilaian       | penilaian       | Assessments          |
| DetailPenilaian | detailpenilaian | Assessment details   |
| Sertifikat      | sertifikat      | Certificates         |
| Gender          | gender          | Gender reference     |
| LoginRequest    | login_requests  | Login audit trail    |

### Environment Configuration

| Environment              | Database                      | Notes               |
| ------------------------ | ----------------------------- | ------------------- |
| Local (`.env`)           | Local MySQL (`ukkdb`)         | `DB_HOST=127.0.0.1` |
| Production (`.env.prod`) | Remote MySQL (`217.21.72.72`) | Hosted database     |

---

## 2. Target System Overview

### Technology Stack (To-Be)

| Layer              | Technology                | Version               |
| ------------------ | ------------------------- | --------------------- |
| Framework          | Laravel 11                | ^11.0                 |
| PHP                | ^8.2                      | (minimum requirement) |
| Frontend Framework | Vue 3                     | ^3.4                  |
| Router/Bridge      | Inertia.js                | @inertiajs/vue3 ^1.x  |
| UI Components      | shadcn-vue                | Latest                |
| CSS Framework      | Tailwind CSS              | ^3.4                  |
| Build Tool         | Vite                      | ^5.0                  |
| API                | RESTful (Laravel Sanctum) | ^4.0                  |
| Database           | MySQL                     | (unchanged)           |

### Target Architecture

```
┌─────────────────────────────────────────────┐
│  Browser                                    │
│                                             │
│  Inertia.js (SPA Router)                    │
│  └─ Vue 3 (Component-based SPA)            │
│     ├─ Pages (route-level components)       │
│     ├─ Layouts (AdminLayout, AuthLayout)    │
│     ├─ Components (shadcn-vue + custom)     │
│     └─ Composables (reusable logic)         │
│                                             │
│  Tailwind CSS + shadcn-vue (styling)        │
└──────────────────┬──────────────────────────┘
                   │ Inertia requests
┌──────────────────▼──────────────────────────┐
│  Laravel 11                                 │
│                                             │
│  routes/web.php → Inertia::render()         │
│  routes/api.php → REST API (unchanged)      │
│                                             │
│  Controllers: (unchanged logic)             │
│  Models: (unchanged)                        │
└──────────────────┬──────────────────────────┘
                   │ Eloquent
┌──────────────────▼──────────────────────────┐
│  MySQL (unchanged)                          │
└─────────────────────────────────────────────┘
```

---

## 3. Migration Strategy

### Approach: Incremental (Phased)

**Rationale:**

- 14+ Blade views + AngularJS pages need conversion
- Big-bang migration is high-risk
- Incremental allows testing at each step
- Old AngularJS pages can coexist during transition

### Phases Overview

| Phase       | Name                  | Description                                    | Risk Level |
| ----------- | --------------------- | ---------------------------------------------- | ---------- |
| **Phase 0** | Preparation           | Backup, environment audit, dependency check    | Low        |
| **Phase 1** | Laravel 11 Upgrade    | Upgrade framework, restructure bootstrap       | Medium     |
| **Phase 2** | Inertia Foundation    | Install Inertia, Vue 3, Vite config            | Low        |
| **Phase 3** | Tailwind + shadcn-vue | Install CSS framework, component library       | Low        |
| **Phase 4** | Layout Foundation     | Root Inertia layout, AdminLayout, AuthLayout   | Low        |
| **Phase 5** | Auth Pages POC        | Login, Register as Vue pages                   | Low        |
| **Phase 6** | Core CRUD Pages       | Jurusan, TahunAjaran, Aksesor                  | Medium     |
| **Phase 7** | Complex Pages         | Siswa + Detail, Paket + Detail                 | Medium     |
| **Phase 8** | Reports & Assessment  | Penilaian, Laporan Kelulusan, Laporan Aksesor  | Medium     |
| **Phase 9** | Cleanup & Polish      | Remove old files, final testing, documentation | Low        |

---

## 4. Detailed Task Breakdown

---

### Phase 0: Preparation

> **Goal:** Ensure safe starting point with backups and clear baseline.

| #   | Task                  | Details                                                                       | Files Affected | Status  |
| --- | --------------------- | ----------------------------------------------------------------------------- | -------------- | ------- |
| 0.1 | **Git branch**        | Create feature branch `feature/vue-inertia-migration`                         | —              | ✅ DONE |
| 0.2 | **Verify .env**       | Confirm local database connection, run `php artisan migrate:status`           | `.env`         | ✅ DONE |
| 0.3 | **Verify .env.prod**  | Document production config (reference only, do not modify)                    | `.env.prod`    | ✅ DONE |
| 0.4 | **Full backup**       | `git commit` current state, tag as `pre-migration`                            | —              | ✅ DONE |
| 0.5 | **Smoke test**        | Verify current app runs: `php artisan migrate:status` — all 12 migrations Ran | —              | ✅ DONE |
| 0.6 | **PHP version check** | Confirm local PHP >= 8.2 — PHP 8.3.3 ✅                                       | —              | ✅ DONE |

**Checkpoint:** ✅ All tasks pass. Ready for Phase 1.

---

### Phase 1: Laravel 10 → Laravel 11 Upgrade

> **Goal:** Upgrade to Laravel 11 with minimal breaking changes.

| #   | Task                            | Details                                                                                                                                     | Files Affected      | Status  |
| --- | ------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------- | ------------------- | ------- |
| 1.1 | Update `composer.json`          | `php: ^8.2`, `laravel/framework: ^11.0`, `laravel/sanctum: ^4.0`                                                                            | `composer.json`     | ✅ DONE |
| 1.2 | Run `composer update`           | Upgrade all Laravel packages                                                                                                                | `composer.lock`     | ✅ DONE |
| 1.3 | Restructure `bootstrap/app.php` | Move to `withRouting()`, `withMiddleware()`, `withExceptions()` style                                                                       | `bootstrap/app.php` | ✅ DONE |
| 1.4 | Update `config/app.php`         | Remove deleted providers from providers array                                                                                               | `config/app.php`    | ✅ DONE |
| 1.5 | Remove deprecated files         | `Kernel.php`, `RouteServiceProvider`, `BroadcastServiceProvider`, `EventServiceProvider`, `AuthServiceProvider`, `ConsoleKernel`, `Handler` | —                   | ✅ DONE |
| 1.6 | Verify `routes/web.php`         | Routes still resolve correctly (all web routes)                                                                                             | `routes/web.php`    | ✅ DONE |
| 1.7 | Verify `routes/api.php`         | API routes still work (62 routes total)                                                                                                     | `routes/api.php`    | ✅ DONE |
| 1.8 | Test application                | `php artisan migrate:status` clean, all routes listed                                                                                       | —                   | ✅ DONE |
| 1.9 | Fix any breaking changes        | `composer dump-autoload`, clear caches                                                                                                      | —                   | ✅ DONE |

**Result:** Laravel Framework 11.51.0 ✅ All routes functional.

**Checkpoint:** ✅ App runs normally on Laravel 11. All routes functional.

---

### Phase 2: Inertia.js Foundation

> **Goal:** Install and configure Inertia.js on both server and client side.

| #    | Task                        | Details                                                                        | Files Affected                                  |
| ---- | --------------------------- | ------------------------------------------------------------------------------ | ----------------------------------------------- |
| 2.1  | Install Inertia server      | `composer require inertiajs/inertia-laravel`                                   | `composer.json`, `composer.lock`                |
| 2.2  | Install Inertia client      | `npm install vue @inertiajs/vue3 @vitejs/plugin-vue`                           | `package.json`, `package-lock.json`             |
| 2.3  | Create root Blade layout    | `resources/views/app.blade.php` — single entry point with `@inertia` directive | **NEW** `resources/views/app.blade.php`         |
| 2.4  | Generate Inertia middleware | `php artisan inertia:middleware`                                               | `app/Http/Middleware/HandleInertiaRequests.php` |
| 2.5  | Register Inertia middleware | Add to `web` middleware group in `bootstrap/app.php`                           | `bootstrap/app.php`                             |
| 2.6  | Update Vite config          | Add Vue plugin, `@` alias                                                      | `vite.config.js`                                |
| 2.7  | Create Vue entry point      | `resources/js/app.js` — Inertia app bootstrap with `createInertiaApp()`        | `resources/js/app.js`                           |
| 2.8  | Create Pages directory      | `resources/js/Pages/` — empty for now                                          | **NEW** directory                               |
| 2.9  | Create placeholder page     | `resources/js/Pages/Welcome.vue` — simple test page                            | **NEW** `resources/js/Pages/Welcome.vue`        |
| 2.10 | Test Inertia setup          | Update `/` route to `Inertia::render('Welcome')`, verify rendering             | `routes/web.php`                                |

**Checkpoint:** Inertia renders a Vue page successfully.

---

### Phase 3: Tailwind CSS + shadcn-vue

> **Goal:** Install and configure styling foundation.

| #   | Task                      | Details                                                                                        | Files Affected                            |
| --- | ------------------------- | ---------------------------------------------------------------------------------------------- | ----------------------------------------- |
| 3.1 | Install Tailwind          | `npm install -D tailwindcss postcss autoprefixer`                                              | `package.json`                            |
| 3.2 | Init Tailwind config      | `npx tailwindcss init -p`                                                                      | `tailwind.config.js`, `postcss.config.js` |
| 3.3 | Configure content paths   | Set content to `["./resources/**/*.blade.php", "./resources/**/*.js", "./resources/**/*.vue"]` | `tailwind.config.js`                      |
| 3.4 | Add Tailwind directives   | `resources/css/app.css` — add `@tailwind base/components/utilities`                            | `resources/css/app.css`                   |
| 3.5 | Init shadcn-vue           | `npx shadcn-vue@latest init`                                                                   | `components.json`                         |
| 3.6 | Select shadcn-vue options | Theme: default, CSS vars: yes, icon library: lucide                                            | `components.json`                         |
| 3.7 | Add initial components    | `npx shadcn-vue@latest add button card input label table dialog form toast`                    | `resources/js/Components/ui/`             |

**Checkpoint:** Tailwind classes render correctly. shadcn-vue components importable.

---

### Phase 4: Layout Foundation

> **Goal:** Create reusable Inertia layouts that replace `main.blade.php` and `admin.blade.php`.

| #   | Task                     | Details                                                                      | Files Affected                                            |
| --- | ------------------------ | ---------------------------------------------------------------------------- | --------------------------------------------------------- |
| 4.1 | Create Layouts directory | `resources/js/Components/Layouts/`                                           | **NEW** directory                                         |
| 4.2 | Create `AuthLayout.vue`  | Simple centered layout for login/register (replaces `main.blade.php` shell)  | **NEW** `resources/js/Components/Layouts/AuthLayout.vue`  |
| 4.3 | Create `AdminLayout.vue` | Sidebar + Top Navbar + Footer + content slot (replaces `admin.blade.php`)    | **NEW** `resources/js/Components/Layouts/AdminLayout.vue` |
| 4.4 | Port sidebar navigation  | Convert sidebar menu items from `admin.blade.php` to Vue `<Link>` components | Part of 4.3                                               |
| 4.5 | Port top navbar          | Convert navbar (user profile dropdown, logout link) to Vue                   | Part of 4.3                                               |
| 4.6 | Port footer              | Convert footer to Vue component                                              | Part of 4.3                                               |
| 4.7 | Sidebar toggle logic     | Implement mobile responsive sidebar toggle (was `off-canvas.js`)             | Part of 4.3                                               |
| 4.8 | Test AdminLayout         | Create test page that uses AdminLayout, verify sidebar + navbar render       | Test only                                                 |

**Checkpoint:** AdminLayout renders with working sidebar, navbar, and footer. Sidebar toggle works on mobile.

---

### Phase 5: Auth Pages POC (Proof of Concept)

> **Goal:** Convert login and register pages to Vue + Inertia as proof of concept.

| #   | Task                       | Details                                                          | Files Affected                                 |
| --- | -------------------------- | ---------------------------------------------------------------- | ---------------------------------------------- |
| 5.1 | Create `Auth/Login.vue`    | Login form with shadcn-vue Card, Input, Button. Uses AuthLayout. | **NEW** `resources/js/Pages/Auth/Login.vue`    |
| 5.2 | Create `Auth/Register.vue` | Registration form with shadcn-vue components                     | **NEW** `resources/js/Pages/Auth/Register.vue` |
| 5.3 | Update login route         | `GET /auth/login` → `Inertia::render('Auth/Login')`              | `routes/web.php`                               |
| 5.4 | Update register route      | `GET /auth/register` → `Inertia::render('Auth/Register')`        | `routes/web.php`                               |
| 5.5 | Handle form submission     | Keep POST routes as-is (Laravel controllers handle auth logic)   | No change needed                               |
| 5.6 | Error handling             | Display `$errors` via Inertia `useForm` errors prop              | Part of 5.1, 5.2                               |
| 5.7 | Test login flow            | Login → redirect to `/admin`, Logout → redirect to `/auth/login` | —                                              |
| 5.8 | Test register flow         | Register → login → dashboard                                     | —                                              |

**Checkpoint:** Full auth flow works with Vue pages. Old Blade auth files kept as backup.

---

### Phase 6: Core CRUD Pages

> **Goal:** Convert Jurusan, TahunAjaran, and Aksesor pages (simplest CRUD modules).

| #   | Task                           | Details                                                                    | Files Affected                                     |
| --- | ------------------------------ | -------------------------------------------------------------------------- | -------------------------------------------------- |
| 6.1 | Create `Jurusan/Index.vue`     | Table + Create/Edit dialog + Delete confirmation                           | **NEW** `resources/js/Pages/Jurusan/Index.vue`     |
| 6.2 | Update jurusan route           | `GET /admin/jurusan` → `Inertia::render('Jurusan/Index', props)`           | `routes/web.php`                                   |
| 6.3 | Pass data via Inertia props    | JurusanController returns `Inertia::render()` with data                    | `app/Http/Controllers/JurusanController.php`       |
| 6.4 | CRUD operations                | Form submissions via Inertia `useForm` (POST/PUT/DELETE)                   | Part of 6.1                                        |
| 6.5 | Create `TahunAjaran/Index.vue` | Same pattern as Jurusan                                                    | **NEW** `resources/js/Pages/TahunAjaran/Index.vue` |
| 6.6 | Update tahunajaran route       | Same pattern as jurusan                                                    | `routes/web.php`                                   |
| 6.7 | Create `Aksesor/Index.vue`     | Same pattern as Jurusan                                                    | **NEW** `resources/js/Pages/Aksesor/Index.vue`     |
| 6.8 | Update aksesor route           | Same pattern as jurusan                                                    | `routes/web.php`                                   |
| 6.9 | Test all 3 CRUD modules        | Create, edit, delete, validation errors, SweetAlert2 → toast notifications | —                                                  |

**Pattern established:**

- Index page with data table (shadcn-vue Table)
- Create/Edit in Dialog component (shadcn-vue Dialog)
- Delete confirmation (shadcn-vue Dialog or toast)
- Inertia `useForm` for form state
- Props from controller for initial data
- API calls for mutations (or Inertia form submissions)

**Checkpoint:** Jurusan, TahunAjaran, Aksesor fully functional as Vue pages.

---

### Phase 7: Complex Pages

> **Goal:** Convert Siswa (with detail), Paket (with detail) — pages with relationships and nested data.

| #    | Task                      | Details                                                                    | Files Affected                                |
| ---- | ------------------------- | -------------------------------------------------------------------------- | --------------------------------------------- |
| 7.1  | Create `Siswa/Index.vue`  | Student listing with filter by tahunajaran, search by nis                  | **NEW** `resources/js/Pages/Siswa/Index.vue`  |
| 7.2  | Update siswa route        | `GET /admin/siswa` → `Inertia::render('Siswa/Index')`                      | `routes/web.php`                              |
| 7.3  | Create `Siswa/Detail.vue` | Student detail with personal info + penilaian list                         | **NEW** `resources/js/Pages/Siswa/Detail.vue` |
| 7.4  | Update siswa detail route | `GET /admin/siswa/{id}` → `Inertia::render('Siswa/Detail', { id })`        | `routes/web.php`                              |
| 7.5  | Create `Paket/Index.vue`  | Package listing with filter by tahunajaran                                 | **NEW** `resources/js/Pages/Paket/Index.vue`  |
| 7.6  | Update paket route        | `GET /admin/paket` → `Inertia::render('Paket/Index')`                      | `routes/web.php`                              |
| 7.7  | Create `Paket/Detail.vue` | Package detail with kompetensi list                                        | **NEW** `resources/js/Pages/Paket/Detail.vue` |
| 7.8  | Update paket detail route | `GET /admin/paket/{id}` → `Inertia::render('Paket/Detail', { id })`        | `routes/web.php`                              |
| 7.9  | Handle API calls          | These pages may need direct API calls (not Inertia forms) for dynamic data | Composables or direct axios                   |
| 7.10 | Test complex pages        | All CRUD + relationships work correctly                                    | —                                             |

**Checkpoint:** Siswa and Paket modules fully functional with detail pages.

---

### Phase 8: Reports & Assessment

> **Goal:** Convert Penilaian, Laporan Kelulusan, and Laporan Aksesor — the most complex pages.

| #   | Task                           | Details                                                          | Files Affected                                     |
| --- | ------------------------------ | ---------------------------------------------------------------- | -------------------------------------------------- |
| 8.1 | Create `Penilaian/Index.vue`   | Assessment form with student selection, kompetensi scoring       | **NEW** `resources/js/Pages/Penilaian/Index.vue`   |
| 8.2 | Update penilaian route         | `GET /admin/penilaian/{id}` → `Inertia::render()`                | `routes/web.php`                                   |
| 8.3 | Create `Laporan/Kelulusan.vue` | Graduation report with filter (ta, jurusan)                      | **NEW** `resources/js/Pages/Laporan/Kelulusan.vue` |
| 8.4 | Update lkelulusan route        | `GET /admin/lkelulusan` → `Inertia::render()`                    | `routes/web.php`                                   |
| 8.5 | Create `Laporan/Aksesor.vue`   | Assessor report with filter (ta)                                 | **NEW** `resources/js/Pages/Laporan/Aksesor.vue`   |
| 8.6 | Update laksesor route          | `GET /admin/laksesor` → `Inertia::render()`                      | `routes/web.php`                                   |
| 8.7 | Handle print functionality     | Reports likely have print/preview — implement CSS `@media print` | Part of 8.3, 8.5                                   |
| 8.8 | Test reports                   | Data accuracy, filtering, print layout                           | —                                                  |

**Checkpoint:** All assessment and reporting pages functional.

---

### Phase 9: Cleanup & Polish

> **Goal:** Remove legacy files, finalize styling, run comprehensive tests.

| #    | Task                            | Details                                                                                        | Files Affected                                                          |
| ---- | ------------------------------- | ---------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| 9.1  | Remove AngularJS files          | Delete `public/assets/js/apps/`                                                                | `public/assets/js/apps/`                                                |
| 9.2  | Remove old Blade views          | Delete `home.blade.php`, `jurusan.blade.php`, etc. (keep `app.blade.php`, `welcome.blade.php`) | `resources/views/`                                                      |
| 9.3  | Remove Bootstrap/jQuery scripts | Remove Bootstrap 4 CDN, jQuery from `admin.blade.php` (no longer used)                         | Old Blade files                                                         |
| 9.4  | Update welcome page             | Redesign landing page with Vue/Inertia (optional)                                              | `resources/views/welcome.blade.php` or `resources/js/Pages/Welcome.vue` |
| 9.5  | Comprehensive testing           | Test ALL routes, ALL CRUD operations, auth flow, reports                                       | —                                                                       |
| 9.6  | Responsive testing              | Test on mobile, tablet, desktop breakpoints                                                    | —                                                                       |
| 9.7  | Performance audit               | Check bundle size, optimize Vite build                                                         | —                                                                       |
| 9.8  | Documentation update            | Update `README.md` with new tech stack, setup instructions                                     | `README.md`                                                             |
| 9.9  | Production test                 | Deploy to staging with `.env.prod`, verify against remote DB                                   | —                                                                       |
| 9.10 | Git tag                         | Tag as `v2.0-vue-migration`                                                                    | —                                                                       |

**Checkpoint:** Clean codebase. No AngularJS remnants. All features working.

---

## 5. File Change Summary

### New Files to Create

| File                                              | Phase | Purpose                                |
| ------------------------------------------------- | ----- | -------------------------------------- |
| `resources/views/app.blade.php`                   | 2     | Root Inertia Blade entry point         |
| `app/Http/Middleware/HandleInertiaRequests.php`   | 2     | Inertia middleware                     |
| `resources/js/app.js`                             | 2     | Inertia Vue app bootstrap              |
| `resources/js/Pages/Auth/Login.vue`               | 5     | Login page                             |
| `resources/js/Pages/Auth/Register.vue`            | 5     | Registration page                      |
| `resources/js/Pages/Dashboard.vue`                | 5     | Dashboard page                         |
| `resources/js/Pages/Jurusan/Index.vue`            | 6     | Department CRUD page                   |
| `resources/js/Pages/TahunAjaran/Index.vue`        | 6     | Academic year CRUD page                |
| `resources/js/Pages/Aksesor/Index.vue`            | 6     | Assessor CRUD page                     |
| `resources/js/Pages/Siswa/Index.vue`              | 7     | Student listing page                   |
| `resources/js/Pages/Siswa/Detail.vue`             | 7     | Student detail page                    |
| `resources/js/Pages/Paket/Index.vue`              | 7     | Package listing page                   |
| `resources/js/Pages/Paket/Detail.vue`             | 7     | Package detail page                    |
| `resources/js/Pages/Penilaian/Index.vue`          | 8     | Assessment page                        |
| `resources/js/Pages/Laporan/Kelulusan.vue`        | 8     | Graduation report                      |
| `resources/js/Pages/Laporan/Aksesor.vue`          | 8     | Assessor report                        |
| `resources/js/Components/Layouts/AuthLayout.vue`  | 4     | Auth page layout                       |
| `resources/js/Components/Layouts/AdminLayout.vue` | 4     | Admin page layout                      |
| `resources/js/Components/ui/`                     | 3     | shadcn-vue components (auto-generated) |
| `tailwind.config.js`                              | 3     | Tailwind configuration                 |
| `postcss.config.js`                               | 3     | PostCSS configuration                  |
| `resources/css/app.css` (modified)                | 3     | Tailwind directives                    |

### Files to Modify

| File                                             | Phase | Change                                        |
| ------------------------------------------------ | ----- | --------------------------------------------- |
| `composer.json`                                  | 1     | Upgrade to Laravel 11, add Inertia            |
| `package.json`                                   | 2-3   | Add Vue, Inertia, Tailwind, shadcn-vue deps   |
| `vite.config.js`                                 | 2     | Add Vue plugin + `@` alias                    |
| `bootstrap/app.php`                              | 1     | Add middleware definitions (Laravel 11 style) |
| `app/Providers/AppServiceProvider.php`           | 1     | Update for Laravel 11                         |
| `routes/web.php`                                 | 5-8   | Convert `view()` → `Inertia::render()`        |
| `app/Http/Controllers/JurusanController.php`     | 6     | Return Inertia responses                      |
| `app/Http/Controllers/TahunAjaranController.php` | 6     | Return Inertia responses                      |
| `app/Http/Controllers/AksesorController.php`     | 6     | Return Inertia responses                      |
| `app/Http/Controllers/SiswaController.php`       | 7     | Return Inertia responses                      |
| `app/Http/Controllers/PaketController.php`       | 7     | Return Inertia responses                      |
| `app/Http/Controllers/PenilaianController.php`   | 8     | Return Inertia responses                      |
| `app/Http/Controllers/LaporanController.php`     | 8     | Return Inertia responses                      |

### Files to Delete

| File                                         | Phase | Reason                      |
| -------------------------------------------- | ----- | --------------------------- |
| `app/Http/Kernel.php`                        | 1     | Laravel 11 removes this     |
| `app/Providers/RouteServiceProvider.php`     | 1     | Laravel 11 removes this     |
| `app/Providers/BroadcastServiceProvider.php` | 1     | Not used                    |
| `resources/views/main.blade.php`             | 9     | Replaced by Inertia layouts |
| `resources/views/admin.blade.php`            | 9     | Replaced by Inertia layouts |
| `resources/views/home.blade.php`             | 9     | Replaced by Vue page        |
| `resources/views/jurusan.blade.php`          | 9     | Replaced by Vue page        |
| `resources/views/tahunajaran.blade.php`      | 9     | Replaced by Vue page        |
| `resources/views/aksesor.blade.php`          | 9     | Replaced by Vue page        |
| `resources/views/siswa.blade.php`            | 9     | Replaced by Vue page        |
| `resources/views/siswadetail.blade.php`      | 9     | Replaced by Vue page        |
| `resources/views/paket.blade.php`            | 9     | Replaced by Vue page        |
| `resources/views/paketdetail.blade.php`      | 9     | Replaced by Vue page        |
| `resources/views/penilaian.blade.php`        | 9     | Replaced by Vue page        |
| `resources/views/laporanKelulusan.blade.php` | 9     | Replaced by Vue page        |
| `resources/views/laporanAksesor.blade.php`   | 9     | Replaced by Vue page        |
| `resources/views/auths/login.blade.php`      | 9     | Replaced by Vue page        |
| `resources/views/auths/register.blade.php`   | 9     | Replaced by Vue page        |
| `public/assets/js/apps/` (entire dir)        | 9     | AngularJS no longer used    |

### Files NOT Touched

| File                   | Reason                                      |
| ---------------------- | ------------------------------------------- |
| `routes/api.php`       | API routes preserved for external consumers |
| All `app/Models/*.php` | No model changes needed                     |
| `database/migrations/` | No schema changes                           |
| `database/seeders/`    | No seeder changes                           |
| `config/`              | Minimal config changes (only if needed)     |
| `.env`                 | Environment variables unchanged             |
| `.env.prod`            | Production config unchanged                 |

---

## 6. Risk Assessment

| Risk                                | Likelihood | Impact   | Mitigation                                      |
| ----------------------------------- | ---------- | -------- | ----------------------------------------------- |
| Laravel 11 breaking changes         | Medium     | High     | Thorough testing after Phase 1, rollback ready  |
| PHP version < 8.2 on server         | Medium     | High     | Verify before starting, upgrade if needed       |
| Inertia middleware misconfiguration | Low        | Medium   | Follow official docs, test early                |
| Tailwind/shadcn-vue conflicts       | Low        | Low      | Isolate in Phase 3, test independently          |
| Complex page conversion (reports)   | Medium     | Medium   | Extra testing time in Phase 8                   |
| Data loss during migration          | Low        | Critical | Git commits at each checkpoint, DB backup       |
| Production deployment issues        | Low        | High     | Test with `.env.prod` in Phase 9 before go-live |

---

## 7. Rollback Strategy

At any checkpoint, if migration cannot proceed:

```bash
# Option 1: Git reset (loses all migration work)
git reset --hard pre-migration

# Option 2: Step-by-step revert (if only last phase failed)
# Revert specific files, composer install, npm install

# Option 3: Parallel deployment
# Keep old app running on separate URL while testing new version
```

---

## 8. Success Criteria

- [ ] Laravel 11 runs without errors
- [ ] All 17 routes render via Inertia (no Blade views except `app.blade.php`)
- [ ] Auth flow (login → dashboard → logout) works end-to-end
- [ ] All CRUD operations (Create, Read, Update, Delete) functional
- [ ] Reports render correctly with accurate data
- [ ] Responsive design works on mobile/tablet/desktop
- [ ] No AngularJS files remain in codebase
- [ ] No Bootstrap 4 dependencies remain
- [ ] Production deployment succeeds with `.env.prod`
- [ ] Zero data loss — all database operations work as before

---

## 9. Post-Migration Architecture (Final State)

```
resources/
├── views/
│   ├── app.blade.php              ← Single Inertia root layout
│   └── welcome.blade.php          ← Optional landing page
├── css/
│   └── app.css                    ← Tailwind directives
└── js/
    ├── app.js                     ← Inertia bootstrap
    ├── Pages/
    │   ├── Auth/
    │   │   ├── Login.vue
    │   │   └── Register.vue
    │   ├── Dashboard.vue
    │   ├── Jurusan/
    │   │   └── Index.vue
    │   ├── TahunAjaran/
    │   │   └── Index.vue
    │   ├── Aksesor/
    │   │   └── Index.vue
    │   ├── Siswa/
    │   │   ├── Index.vue
    │   │   └── Detail.vue
    │   ├── Paket/
    │   │   ├── Index.vue
    │   │   └── Detail.vue
    │   ├── Penilaian/
    │   │   └── Index.vue
    │   └── Laporan/
    │       ├── Kelulusan.vue
    │       └── Aksesor.vue
    ├── Components/
    │   ├── Layouts/
    │   │   ├── AuthLayout.vue
    │   │   └── AdminLayout.vue
    │   ├── ui/                    ← shadcn-vue components
    │   │   ├── button/
    │   │   ├── card/
    │   │   ├── input/
    │   │   ├── label/
    │   │   ├── table/
    │   │   ├── dialog/
    │   │   ├── form/
    │   │   └── toast/
    │   └── Shared/
    │       └── ...                ← Custom shared components
    └── composables/
        └── useApi.js              ← (Optional) API helper
```

---

## 10. Execution Order Summary

```
Phase 0  →  Git branch, backup, verify (PREPARE)
    ↓
Phase 1  →  Laravel 11 upgrade (BACKEND)
    ↓
Phase 2  →  Inertia.js setup (BRIDGE)
    ↓
Phase 3  →  Tailwind + shadcn-vue (STYLING)
    ↓
Phase 4  →  Layout components (FOUNDATION)
    ↓
Phase 5  →  Auth pages POC (PROVE IT WORKS)
    ↓
Phase 6  →  Simple CRUD (JURUSAN, TAHUNAJARAN, AKSESOR)
    ↓
Phase 7  →  Complex pages (SISWA, PAKET + details)
    ↓
Phase 8  →  Reports & assessment (PENILAIAN, LAPORAN)
    ↓
Phase 9  →  Cleanup, test, deploy (FINISH)
```

---

## 11. Dependencies Installation Order

```
1. composer update (Laravel 11)
2. composer require inertiajs/inertia-laravel
3. npm install vue @inertiajs/vue3 @vitejs/plugin-vue
4. npm install -D tailwindcss postcss autoprefixer
5. npx tailwindcss init -p
6. npx shadcn-vue@latest init
7. npx shadcn-vue@latest add button card input label table dialog form toast
```

---

_End of Migration Plan Document_
