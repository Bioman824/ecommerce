# Spotlight Fashion Store

A production-ready, modular PHP 8.3+ / MySQL ecommerce storefront with a premium frontend, AJAX cart, SEO-ready pages, and an admin dashboard.

## Features

- Responsive storefront with hero, collections, product cards, FAQ, blog and contact sections
- Product listing, detail pages, cart and checkout flow
- AJAX cart interaction via API
- Admin dashboard with product, order, customer and settings management
- MySQL schema with normalized tables and indexed relationships

## Structure

- Admin/ - administrator dashboard pages
- User/ - user-facing modules
- API/ - AJAX endpoints
- Includes/ - shared header/footer/helpers
- Assets/ - CSS/JS/images
- Configuration/ - config and environment constants
- Database/ - SQL schema
- Uploads/ - media storage

## Installation

1. Create a MySQL database named spotlight_fashion.
2. Import Database/schema.sql into MySQL.
3. Update Configuration/config.php with your database credentials.
4. Place the project in your web root.
5. Visit index.php and log in as admin@spotlightfashion.test with password `password123`.

## Notes

- The default admin password hash is preloaded.
- Update settings and payment credentials in the admin panel for production use.
