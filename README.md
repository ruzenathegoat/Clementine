# Clementine

Clementine is an e-commerce landing page built for luxury horology and high-end watches. Instead of a standard e-commerce template, it uses a raw, editorial neubrutalist look.

## Design philosophy

The design language is mechanical, matching the precision of the watches it sells:
- **Zero curves**: All borders and corners are set to 0px, no rounded edges anywhere.
- **High contrast**: A monochrome palette: pure black, pure white, and a few greys to separate layers.
- **Strict grid**: A rigid 4-column layout defined by 1px black borders.
- **Editorial typography**: Bold Satoshi for headings, IBM Plex Sans for body copy, and italic Instrument Serif for accents.

## Tech stack

- **Backend**: Laravel, PHP
- **Database**: Supabase
- **Frontend**: Tailwind CSS v4, Alpine.js, DaisyUI, Flowbite

## Getting started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- Supabase account/project

### Installation
1. Clone the repository and install PHP dependencies:
```bash
   composer install
```
2. Install frontend dependencies:
```bash
   npm install
```
3. Generate an application key:
```bash
   php artisan key:generate
```
4. Run database migrations:
```bash
   php artisan migrate
```
5. Start the local development server:
```bash
   php artisan serve
```
6. In a separate terminal, compile frontend assets:
```bash
   npm run dev
```