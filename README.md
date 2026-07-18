# Spotlight Fashion Store

A single-vendor PHP 8.3+ / MySQL ecommerce storefront with a responsive, mobile-first frontend, AJAX cart, SEO-ready pages, and a separate admin dashboard.

## Features

- Responsive storefront with hero, shop-by-category, product grid, testimonials, FAQ accordion, blog and contact sections
- Mobile navigation (offcanvas menu), live search, wishlist and cart badges
- Product listing, detail pages, cart and checkout flow
- AJAX cart interaction via `API/`
- Admin dashboard with product, order, customer and settings management
- Admin login kept fully separate from customer login — different page, different session guard, admin credentials cannot be used on the storefront login and vice versa
- MySQL schema with normalized tables and indexed relationships

## Structure

- `Admin/` — administrator dashboard pages (own login at `Admin/login.php`)
- `User/` — customer-facing account modules (profile, orders, wishlist)
- `API/` — AJAX endpoints (cart, review, newsletter)
- `Includes/` — shared header/footer/helpers
- `Assets/` — CSS/JS
- `Configuration/` — config and environment constants
- `Database/` — SQL schema (`schema.sql`) and installer (`install.php`)
- `Uploads/` — media storage

## Installation

1. Create a MySQL database named `spotlight_fashion`.
2. Import `Database/schema.sql` into it (this also seeds demo products, categories, an admin account, etc.).
3. Update `Configuration/config.php` with your database credentials and `BASE_URL` if different from `http://localhost/ecommerce/`.
4. Place the project in your web root and visit `index.php` for the storefront.

## Logging in

- **Customers** sign in at `login.php`.
- **Admins** sign in separately at `Admin/login.php` — default seeded account is `admin@spotlightfashion.test` / `password123`. Change this password before deploying anywhere public.
- `logout.php` ends the session for either login and returns you to the matching login page.

## Notes

- `Database/data/` (the local MySQL server's own runtime files) and `Uploads/*` are gitignored — don't commit generated/runtime data.
- Update settings and payment credentials in the admin panel before production use.
