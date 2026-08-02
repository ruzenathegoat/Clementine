# Clementine

Clementine is a premium e-commerce landing page designed exclusively for luxury horology and high-end watches. It abandons traditional, safe e-commerce templates in favor of a raw, editorial neubrutalist aesthetic that demands attention.

## Design Philosophy

The design language is strictly mechanical, reflecting the precision of the watches it sells:
- **Zero Curves**: All borders and corners are set to 0px. Absolutely no rounded edges.
- **High Contrast**: A strict monochromatic palette. Pure black, pure white, and minimal greys to separate layers.
- **Strict Grid Architecture**: Driven by a rigid 4-column layout defined by 1px black borders that intersect flawlessly.
- **Editorial Typography**: Massive, ultra-bold Satoshi for headings, IBM Plex Sans for precise, technical body copy, and italicized Instrument Serif for luxurious accents.

## Tech Stack

- **Backend**: Laravel, PHP
- **Database**: Supabase
- **Frontend**: Tailwind CSS v4, Alpine.js, DaisyUI, Flowbite

Note on styling: All default DaisyUI and Flowbite components are aggressively overridden via Tailwind utility classes to enforce the zero-curve, zero-shadow brutalist rule.

## Getting Started

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
3. Copy `.env.example` to `.env` and configure your database and Supabase credentials.
4. Generate an application key:
   ```bash
   php artisan key:generate
   ```
5. Run database migrations:
   ```bash
   php artisan migrate
   ```
6. Start the local development server:
   ```bash
   php artisan serve
   ```
7. In a separate terminal, compile frontend assets:
   ```bash
   npm run dev
   ```

## License

This project is open-source and available under the MIT License.
