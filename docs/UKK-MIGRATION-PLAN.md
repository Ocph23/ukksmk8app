# UKK App — Migration Plan
## AngularJS → Vue 3 + Inertia.js + shadcn-vue + Laravel 11

**Created:** 13 April 2026  
**Status:** Planning  
**Current Version:** Laravel 10 + AngularJS 1.x + Bootstrap 4  
**Target Version:** Laravel 11 + Vue 3 + Inertia.js + shadcn-vue + Tailwind CSS

---

## 1. Current System Overview

### 1.1 Technology Stack

| Layer | Current | Target |
|---|---|---|
| Framework | Laravel 10.10 | Laravel 11 |
| PHP | ^8.1 | ^8.2 |
| Frontend Framework | AngularJS 1.x | Vue 3 + Inertia.js |
| UI Framework | Bootstrap 4 + Purple Admin | Tailwind CSS + shadcn-vue |
| Build Tool | Vite 5 (minimal) | Vite 5 + Vue plugin |
| API | REST API (Sanctum ^3.3) | REST API (Sanctum ^4.0) — maintained |

### 1.2 Current Architecture

```
┌─────────────────────────────────────────────────────┐
│  Browser                                            │
│  ┌───────────────────────────────────────────────┐  │
│  │  Blade Shell (main.blade.php / admin.blade.php)│ │
│  │  AngularJS App (public/assets/js/apps/)       │  │
│  │  ├── 9 Services (API calls)                   │  │
│  │  ├── 9 Controllers (UI logic)                 │  │
│  │  └── Bootstrap 4 + SweetAlert2 + Chart.js     │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────┘
                       │ AJAX (Sanctum API)
┌──────────────────────▼──────────────────────────────┐
│  Laravel 10                                         │
│  ┌────────────────────┐  ┌────────────────────────┐ │
│  │  Web Routes        │  │  API Routes            │ │
│  │  (return Blade)    │  │  (return JSON)         │ │
│  └────────────────────┘  └────────────────────────┘ │
│  ┌────────────────────┐  ┌────────────────────────┐ │
│  │  Controllers       │  │  Models (12)           │ │
│  │  (Siswa, Jurusan,  │  │  Siswa, Jurusan,       │ │
│  │   TahunAjaran,     │  │  TahunAjaran, Aksesor, │ │
│  │   Aksesor, Paket,  │  │  Paket, Kompetensi,    │ │
│  │   Penilaian,       │  │  Penilaian, DetailPen.,│ │
│  │   Laporan)         │  │  Sertifikat, Gender,   │ │
│  └────────────────────┘  │  User, LoginRequest    │ │
│                          └────────────────────────┘ │
└─────────────────────────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│  MySQL (n1567279_ukkappdb @ 217.21.72.72)          │
└─────────────────────────────────────────────────────┘
```

### 1.3 Existing Pages (14 Blade Views)

| # | Route | Blade File | AngularJS Components | Description |
|---|---|---|---|---|
| 1 | `/` | `welcome.blade.php` | — | Landing page |
| 2 | `/auth/login` | `auths/login.blade.php` | — | Login form |
| 3 | `/auth/register` | `auths/register.blade.php` | — | Registration form |
| 4 | `/admin` | `home.blade.php` | — | Dashboard |
| 5 | `/admin/jurusan` | `jurusan.blade.php` | jurusanController | Department CRUD |
| 6 | `/admin/tahunajaran` | `tahunajaran.blade.php` | tahunajaranController | Academic year CRUD |
| 7 | `/admin/aksesor` | `aksesor.blade.php` | aksesorController | Assessor CRUD |
| 8 | `/admin/siswa` | `siswa.blade.php` | siswaController | Student listing |
| 9 | `/admin/siswa/{id}` | `siswadetail.blade.php` | siswaDetailController | Student detail |
| 10 | `/admin/paket` | `paket.blade.php` | paketController | Package listing |
| 11 | `/admin/paket/{id}` | `paketdetail.blade.php` | paketDetailController | Package detail |
| 12 | `/admin/penilaian/{id}` | `penilaian.blade.php` | — | Assessment/grading |
| 13 | `/admin/lkelulusan` | `laporanKelulusan.blade.php` | laporanController | Graduation report |
| 14 | `/admin/laksesor` | `laporanAksesor.blade.php` | laporanController | Assessor report |

### 1.4 Existing API Endpoints (routes/api.php)

| Method | Endpoint | Controller | Description |
|---|---|---|---|
| GET | `/api/user` | — | Sanctum user info |
| GET | `/api/siswa` | SiswaController | List all students |
| GET | `/api/siswa/{id}` | SiswaController | Student by ID |
| GET | `/api/siswa/bytahunajaran/{id}` | SiswaController | Students by academic year |
| GET | `/api/siswa/bynis/{nis}` | SiswaController | Student by NIS |
| POST | `/api/siswa` | SiswaController | Create student |
| PUT | `/api/siswa/{id}` | SiswaController | Update student |
| PUT | `/api/siswa/{id}/sertifikat` | SiswaController | Update certificate |
| DELETE | `/api/siswa/{id}` | SiswaController | Delete student |
| GET | `/api/jurusan` | JurusanController | List departments |
| GET | `/api/jurusan/{id}` | JurusanController | Department by ID |
| POST | `/api/jurusan` | JurusanController | Create department |
| PUT | `/api/jurusan/{id}` | JurusanController | Update department |
| DELETE | `/api/jurusan/{id}` | JurusanController | Delete department |
| GET | `/api/tahunajaran` | TahunAjaranController | List academic years |
| GET | `/api/tahunajaran/{id}` | TahunAjaranController | Year by ID |
| POST | `/api/tahunajaran` | TahunAjaranController | Create year |
| PUT | `/api/tahunajaran/{id}` | TahunAjaranController | Update year |
| DELETE | `/api/tahunajaran/{id}` | TahunAjaranController | Delete year |
| GET | `/api/aksesor` | AksesorController | List assessors |
| GET | `/api/aksesor/{id}` | AksesorController | Assessor by ID |
| POST | `/api/aksesor` | AksesorController | Create assessor |
| PUT | `/api/aksesor/{id}` | AksesorController | Update assessor |
| DELETE | `/api/aksesor/{id}` | AksesorController | Delete assessor |
| GET | `/api/paket` | PaketController | List packages |
| GET | `/api/paket/{id}` | PaketController | Package by ID |
| GET | `/api/paket/bytahunajaran/{id}` | PaketController | Packages by year |
| POST | `/api/paket` | PaketController | Create package |
| PUT | `/api/paket/{id}` | PaketController | Update package |
| PUT | `/api/paket/{id}/detail` | PaketController | Update package detail |
| DELETE | `/api/paket/{id}` | PaketController | Delete package |
| GET | `/api/penilaian` | PenilaianController | List assessments |
| GET | `/api/penilaian/{id}` | PenilaianController | Assessment by ID |
| GET | `/api/penilaian/siswa/{id}` | PenilaianController | Assessments by student |
| POST | `/api/penilaian` | PenilaianController | Create assessment |
| PUT | `/api/penilaian/{id}` | PenilaianController | Update assessment |
| DELETE | `/api/penilaian/{id}` | PenilaianController | Delete assessment |
| GET | `/api/laporan/{ta}/{jurusan}` | LaporanController | Graduation report |
| GET | `/api/laporan/{ta}` | LaporanController | Assessor report |

### 1.5 Database Schema

| Table | Columns (key) | Relations |
|---|---|---|
| `users` | id, name, username, password, email | — |
| `siswa` | id, nis, nama, tempat_lahir, tgl_lahir, jurusan_id, tahun_ajaran_id, gender_id | FK: jurusan, tahun_ajaran, gender |
| `jurusan` | id, nama, kode | — |
| `tahun_ajaran` | id, tahun, aktif | — |
| `aksesor` | id, nama, nip | — |
| `paket` | id, nama, tahun_ajaran_id, jurusan_id | FK: tahun_ajaran, jurusan |
| `kompetensi` | id, paket_id, nama | FK: paket |
| `penilaian` | id, siswa_id, paket_id, aksesor_id, nilai, status | FK: siswa, paket, aksesor |
| `detail_penilaian` | id, penilaian_id, kompetensi_id, nilai | FK: penilaian, kompetensi |
| `sertifikat` | id, siswa_id, nomor, tgl_terbit | FK: siswa |
| `genders` | id, jenis_kelamin | — |

### 1.6 Server Environments

| Environment | File | Database | Status |
|---|---|---|---|
| Local | `.env` | MySQL (localhost/ukkdb) | Development |
| Production | `.env.prod` | MySQL (217.21.72.72/n1567279_ukkappdb) | Live |

---

## 2. Migration Goals

### 2.1 Primary Goals
- [x] Upgrade from Laravel 10 to Laravel 11
- [x] Replace AngularJS 1.x with Vue 3 + Inertia.js
- [x] Replace Bootstrap 4 with Tailwind CSS + shadcn-vue
- [x] Maintain all existing functionality (CRUD, reports, auth)
- [x] Maintain API routes for potential external consumers

### 2.2 Secondary Goals
- [x] Improve developer experience (modern tooling, HMR, TypeScript-ready)
- [x] Better UX (responsive design, modern UI components)
- [x] Clean architecture (SPA-like navigation without page reloads)

### 2.3 Non-Goals
- [ ] Changing backend business logic
- [ ] Changing database schema
- [ ] Removing existing API endpoints
- [ ] Changing authentication system (still session-based auth)

---

## 3. Target Architecture

```
┌─────────────────────────────────────────────────────┐
│  Browser                                            │
│  ┌───────────────────────────────────────────────┐  │
│  │  app.blade.php (single root, @inertia)        │  │
│  │  ┌─────────────────────────────────────────┐  │  │
│  │  │  Vue 3 SPA (Inertia.js)                 │  │  │
│  │  │  ├── AdminLayout.vue (Sidebar + Nav)    │  │  │
│  │  │  ├── Pages/ (Vue SFCs, 14 pages)        │  │  │
│  │  │  ├── Components/ui/ (shadcn-vue)        │  │  │
│  │  │  └── Tailwind CSS                       │  │  │
│  │  └─────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────┘
                       │ Inertia requests (server-side rendered)
┌──────────────────────▼──────────────────────────────┐
│  Laravel 11                                         │
│  ┌────────────────────────────────────────────────┐ │
│  │  Web Routes → Inertia::render()                │ │
│  │  ┌──────────────────────────────────────────┐  │ │
│  │  │  Controllers                             │  │ │
│  │  │  (unchanged logic, return Inertia pages) │  │ │
│  │  └──────────────────────────────────────────┘  │ │
│  │  ┌──────────────────────────────────────────┐  │ │
│  │  │  API Routes (unchanged, return JSON)     │  │ │
│  │  └──────────────────────────────────────────┘  │ │
│  │  ┌──────────────────────────────────────────┐  │ │
│  │  │  Models (12, unchanged)                  │  │ │
│  │  └──────────────────────────────────────────┘  │ │
│  └────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘
```

---

## 4. Phased Migration Plan

### Phase 1: Foundation Setup (Setup + POC)
**Goal:** Upgrade Laravel, install all dependencies, prove it works with 2 pages

**Tasks:**
- [1.1] Upgrade Laravel 10 → 11
- [1.2] Install Inertia.js (server + client)
- [1.3] Install Tailwind CSS
- [1.4] Install shadcn-vue
- [1.5] Configure Vite for Vue
- [1.6] Create root Inertia layout (app.blade.php)
- [1.7] Create AdminLayout.vue (sidebar + navbar)
- [1.8] POC: Convert Login page to Vue
- [1.9] POC: Convert Dashboard page to Vue
- [1.10] Test & Verify

### Phase 2: Auth Pages
**Goal:** Convert all authentication pages

**Tasks:**
- [2.1] Login.vue (full functionality)
- [2.2] Register.vue
- [2.3] Update web routes for Inertia

### Phase 3: Core CRUD Modules
**Goal:** Convert all CRUD admin pages

**Tasks:**
- [3.1] Jurusan/Index.vue + CreateEditDialog.vue
- [3.2] TahunAjaran/Index.vue + CreateEditDialog.vue
- [3.3] Aksesor/Index.vue + CreateEditDialog.vue
- [3.4] Siswa/Index.vue + CreateEditDialog.vue
- [3.5] Siswa/Detail.vue
- [3.6] Paket/Index.vue + CreateEditDialog.vue
- [3.7] Paket/Detail.vue

### Phase 4: Assessment & Reports
**Goal:** Convert assessment and reporting pages

**Tasks:**
- [4.1] Penilaian/Index.vue (with grading interface)
- [4.2] Laporan/Kelulusan.vue (graduation report)
- [4.3] Laporan/Aksesor.vue (assessor report)

### Phase 5: Polish & Cleanup
**Goal:** Remove old code, final testing, deployment

**Tasks:**
- [5.1] Remove AngularJS files from public/assets/
- [5.2] Remove old Blade views (except app.blade.php)
- [5.3] Remove Bootstrap/jQuery CDN links
- [5.4] Final testing across all pages
- [5.5] Production deployment

---

## 5. Detailed Task Breakdown

---

### PHASE 1: Foundation Setup

#### Task 1.1: Upgrade Laravel 10 → Laravel 11

**What changes:**

| File | Action | Detail |
|---|---|---|
| `composer.json` | Modify | PHP `^8.2`, `laravel/framework: ^11.0`, `laravel/sanctum: ^4.0` |
| `bootstrap/app.php` | Rewrite | Add middleware, routing (replaces Kernel.php + RouteServiceProvider) |
| `app/Http/Kernel.php` | **DELETE** | Middleware moved to bootstrap/app.php |
| `app/Providers/RouteServiceProvider.php` | **DELETE** | Routing moved to bootstrap/app.php |
| `app/Providers/BroadcastServiceProvider.php` | **DELETE** | Not needed if no broadcasting |
| `app/Providers/EventServiceProvider.php` | **DELETE** | Only if no events used |
| `app/Providers/AuthServiceProvider.php` | **DELETE** | Simplify if no custom policies |
| `app/Providers/AppServiceProvider.php` | Modify | Update boot() signature if needed |
| `composer.lock` | Regenerate | `composer update` |

**New `bootstrap/app.php` structure:**
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // middleware from old Kernel.php
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

**Risks:**
- ⚠️ Breaking changes in Laravel 11 (middleware signature, exception handler)
- ⚠️ Sanctum 4.0 may have API changes

**Rollback:**
- Revert composer.json, run `composer update laravel/framework:^10.10`
- Restore deleted files from git

**Commands:**
```bash
# 1. Update composer.json
# 2. composer update
# 3. php artisan --version  # verify v11
# 4. php artisan serve      # verify app still works
```

---

#### Task 1.2: Install Inertia.js

**Dependencies to install:**
```bash
composer require inertiajs/inertia-laravel
npm install vue @inertiajs/vue3
```

**Files created:**
| File | Purpose |
|---|---|
| `resources/views/app.blade.php` | Root layout (the ONLY blade file needed) |
| `app/Http/Middleware/HandleInertiaRequests.php` | Inertia middleware |
| `resources/js/app.js` | Vue + Inertia app bootstrap |

**`app.blade.php` content:**
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'UKK App') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
```

**`resources/js/app.js` content:**
```js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
```

**Routes update — register Inertia middleware in `bootstrap/app.php`:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
    ]);
})
```

**Risks:**
- ⚠️ None — this is additive, doesn't break existing code

---

#### Task 1.3: Install Tailwind CSS

**Dependencies:**
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**Files created/modified:**
| File | Action | Detail |
|---|---|---|
| `tailwind.config.js` | Created | Configure content paths |
| `postcss.config.js` | Created | PostCSS pipeline |
| `resources/css/app.css` | Modified | Add @tailwind directives |

**`tailwind.config.js`:**
```js
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

**`resources/css/app.css`:**
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**Risks:**
- ⚠️ Tailwind CDN from old files may conflict — need to remove Bootstrap CDN later
- ⚠️ Tailwind preflight may reset some global styles (intentional)

---

#### Task 1.4: Install shadcn-vue

**Dependencies:**
```bash
npx shadcn-vue@latest init
```

**CLI prompts to answer:**
- Which framework? → Vue
- Which Tailwind config? → Default
- Base color? → Slate (or preference)
- CSS file? → `resources/css/app.css`
- Components path? → `resources/js/Components/ui`

**Files created:**
| File | Purpose |
|---|---|
| `components.json` | shadcn-vue configuration |
| `resources/js/Components/ui/button/` | Button component |
| `resources/js/Components/ui/card/` | Card component |
| ... | Other components as needed |

**Components needed for this project:**
| Component | Usage |
|---|---|
| Button | All action buttons |
| Input | Form inputs |
| Card | Content containers |
| Table | Data listing (Siswa, Jurusan, etc.) |
| Dialog | Create/Edit modals |
| Form + Label | Form layouts |
| Select | Dropdown (Jurusan, Tahun Ajaran) |
| Badge | Status indicators (Lulus/Belum) |
| Tabs | Tabbed interfaces |
| DataTable | Sortable/paginated tables |

**Risks:**
- ⚠️ shadcn-vue requires specific Vue/Tailwind versions — verify compatibility

---

#### Task 1.5: Configure Vite for Vue

**Modify `vite.config.js`:**
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
```

**Risks:**
- ⚠️ None — additive change

---

#### Task 1.6: Create Root Inertia Layout

**File:** `resources/views/app.blade.php`

Already described in Task 1.2. This is the single Blade template that Inertia uses.

**Register it in `routes/web.php` — update all routes to use `Inertia::render()`:**
```php
use Inertia\Inertia;

// Example:
Route::get('/auth/login', function () {
    return Inertia::render('Auth/Login');
})->name('login')->middleware('guest');
```

---

#### Task 1.7: Create AdminLayout.vue

**File:** `resources/js/Components/Layouts/AdminLayout.vue`

This replaces `admin.blade.php`. Contains:
- Top navbar (from admin.blade.php navbar)
- Sidebar navigation (from admin.blade.php sidebar)
- `<slot>` for page content
- Responsive toggle for mobile

**Structure:**
```vue
<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Top Navbar -->
    <nav class="bg-white shadow">
      <!-- ... navbar content from admin.blade.php ... -->
    </nav>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-white shadow">
      <!-- ... sidebar menu from admin.blade.php ... -->
      <!-- Use <Link> from Inertia for navigation -->
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
```

---

#### Task 1.8: POC — Login.vue

**File:** `resources/js/Pages/Auth/Login.vue`

Replaces `auths/login.blade.php`. Uses shadcn-vue Card, Input, Button.

**Structure:**
```vue
<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500">
    <Card class="w-full max-w-md">
      <CardHeader class="text-center">
        <img src="/assets/images/smk8logo.jpeg" class="w-16 h-16 mx-auto" />
        <CardTitle>SMK 8 TIK JAYAPURA</CardTitle>
        <CardDescription>APLIKASI UKK</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="submit">
          <div class="space-y-4">
            <div>
              <Label for="username">Username</Label>
              <Input id="username" v-model="form.username" type="email" placeholder="Username" />
            </div>
            <div>
              <Label for="password">Password</Label>
              <Input id="password" v-model="form.password" type="password" placeholder="Password" />
            </div>
            <Button type="submit" class="w-full" :disabled="form.processing">
              SIGN IN
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

const form = useForm({
  username: '',
  password: '',
})

const submit = () => {
  form.post('/auth/login')
}
</script>
```

---

#### Task 1.9: POC — Dashboard.vue

**File:** `resources/js/Pages/Dashboard.vue`

Replaces `home.blade.php`. Uses AdminLayout + shadcn-vue Card.

**Structure:**
```vue
<template>
  <AdminLayout>
    <div class="flex flex-col items-center justify-center py-12">
      <img src="/assets/images/smk8logo.jpeg" class="w-20 h-20" />
      <h1 class="text-3xl font-bold mt-5">SISTEM INFORMASI</h1>
      <h1 class="text-3xl font-bold">UJI KOMPETENSI KEAHLIAN</h1>
      <h2 class="text-xl mt-10">SMK NEGERI 8 TEKNOLOGI INFORMASI DAN KOMUNIKASI</h2>
      <h2 class="text-xl">JAYAPURA</h2>
      <p class="text-sm text-muted-foreground mt-8">@2024 - Ocph23</p>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Components/Layouts/AdminLayout.vue'
</script>
```

---

#### Task 1.10: Test & Verify

**Checklist:**
- [ ] `npm run dev` — Vite dev server starts, HMR works
- [ ] `php artisan serve` — Laravel dev server starts
- [ ] Navigate to `/auth/login` — renders Vue Login page
- [ ] Login with valid credentials — redirects to `/admin`
- [ ] `/admin` renders Vue Dashboard page with AdminLayout
- [ ] Sidebar navigation links work (Inertia navigation, no full page reload)
- [ ] Tailwind CSS styles are applied correctly
- [ ] shadcn-vue components render correctly
- [ ] Old AngularJS pages still work at their routes (coexistence during migration)

---

### PHASE 2-5: Summary (Detailed in separate phase documents)

| Phase | Scope | Pages | Complexity |
|---|---|---|---|
| Phase 1 | Foundation + POC | Login, Dashboard | Medium |
| Phase 2 | Auth pages | Register | Low |
| Phase 3 | Core CRUD | Jurusan, TahunAjaran, Aksesor, Siswa, Paket | High |
| Phase 4 | Assessment + Reports | Penilaian, Laporan Kelulusan, Laporan Aksesor | High |
| Phase 5 | Cleanup | — | Low |

---

## 6. Risk Assessment

| Risk | Impact | Likelihood | Mitigation |
|---|---|---|---|
| Laravel 11 breaking changes | High | Low | Test thoroughly, keep git commits small |
| Tailwind conflicts with existing styles | Medium | Medium | Scope Tailwind to Vue pages only during migration |
| shadcn-vue component incompatibility | Medium | Low | Test components before building UI |
| Inertia middleware issues | High | Low | Test auth flow first |
| Production deployment failure | Critical | Low | Test on local copy of prod DB first |
| Data loss | Critical | Very Low | No database changes planned |
| Session/auth breaking | High | Low | Keep auth logic unchanged |

---

## 7. Rollback Strategy

Each phase is designed to be independently rollback-able:

| Phase | Rollback Action |
|---|---|
| Phase 1 | `git revert` commits, restore deleted files, `composer update laravel/framework:^10.10` |
| Phase 2 | Revert route changes, restore Blade auth views |
| Phase 3 | Revert route changes, restore Blade CRUD views |
| Phase 4 | Revert route changes, restore Blade report views |
| Phase 5 | N/A — this phase removes old code |

**Golden Rule:** Commit after each task. If anything breaks, revert the last commit.

---

## 8. What Stays, What Changes

### Stays Unchanged (Throughout All Phases)
- ✅ Database schema (no migrations)
- ✅ API routes (`routes/api.php`) — kept for external consumers
- ✅ Backend controller logic (only return type changes)
- ✅ Models and relationships
- ✅ Authentication logic (session-based)
- ✅ Static assets (`public/assets/images/`, `public/assets/css/` for print CSS if needed)

### Will Change
- 🔄 Laravel 10 → 11 (framework version)
- 🔄 `bootstrap/app.php` structure (Laravel 11 style)
- 🔄 `routes/web.php` return type (Blade → Inertia)
- 🔄 All frontend views (Blade + AngularJS → Vue SFCs)
- 🔄 CSS framework (Bootstrap 4 → Tailwind CSS)
- 🔄 UI components (Bootstrap/jQuery → shadcn-vue)
- 🔄 Build tool config (Vite → Vite + Vue plugin)

### Will Be Removed (Phase 5 Only)
- 🗑️ `public/assets/js/apps/` (all AngularJS files)
- 🗑️ `public/assets/js/angular.js`
- 🗑️ All Blade views except `app.blade.php`
- 🗑️ Bootstrap 4 CDN links
- 🗑️ jQuery CDN links
- 🗑️ AngularJS module references

---

## 9. Directory Structure (After Phase 1)

```
ukkapp/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # UNCHANGED
│   │   └── Middleware/
│   │       ├── ... (existing)
│   │       └── HandleInertiaRequests.php  # NEW
│   ├── Models/                    # UNCHANGED
│   └── Providers/
│       └── AppServiceProvider.php # MODIFIED
├── bootstrap/
│   └── app.php                    # REWRITTEN (Laravel 11)
├── config/                        # UNCHANGED
├── database/                      # UNCHANGED
├── resources/
│   ├── css/
│   │   └── app.css                # MODIFIED (@tailwind directives)
│   ├── js/
│   │   ├── app.js                 # MODIFIED (Inertia bootstrap)
│   │   ├── bootstrap.js           # UNCHANGED (axios setup)
│   │   ├── Pages/                 # NEW
│   │   │   ├── Auth/
│   │   │   │   ├── Login.vue      # NEW (POC)
│   │   │   │   └── Register.vue   # NEW (Phase 2)
│   │   │   └── Dashboard.vue      # NEW (POC)
│   │   ├── Components/
│   │   │   ├── Layouts/
│   │   │   │   └── AdminLayout.vue  # NEW
│   │   │   └── ui/                  # NEW (shadcn-vue)
│   │   │       ├── button/
│   │   │       ├── card/
│   │   │       ├── input/
│   │   │       └── ...
│   │   └── composables/
│   │       └── useApi.js            # NEW (optional)
│   └── views/
│       ├── app.blade.php            # NEW