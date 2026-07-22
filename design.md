---
trigger: always_on
---

---
name: Clementine 
description: Editorial, neubrutalist e-commerce landing page for premium watches and horology.
tech_stack: Laravel, Tailwind v4, PHP, Alpine.js, Supabase, DaisyUI, Flowbite
colors:
  primary: "#000000"
  secondary: "#FFFFFF"
  background: "#FFFFFF"
  surface: "#F3F4F6"
  text-primary: "#000000"
  text-inverse: "#FFFFFF"
  text-secondary: "#6B7280"
  border: "#000000"
typography:
  h1:
    fontFamily: "Satoshi, sans-serif"
    fontSize: 4.5rem
    fontWeight: 800
    lineHeight: 1
    letterSpacing: -0.02em
    textTransform: uppercase
  h2:
    fontFamily: "Satoshi, sans-serif"
    fontSize: 3rem
    fontWeight: 800
    lineHeight: 1.1
    textTransform: uppercase
  body-md:
    fontFamily: "'IBM Plex Sans', sans-serif"
    fontSize: 0.875rem
    fontWeight: 400
    lineHeight: 1.5
  accent:
    fontFamily: "'Instrument Serif', serif"
    fontSize: 1.25rem
    fontWeight: 400
    lineHeight: 1.4
    fontStyle: italic
rounded:
  none: 0px
spacing:
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 80px
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.secondary}"
    rounded: "{rounded.none}"
    padding: "12px 32px"
    border: "1px solid {colors.primary}"
  card:
    backgroundColor: "{colors.background}"
    border: "1px solid {colors.border}"
    rounded: "{rounded.none}"
---
## Overview
Desain ini mengadopsi gaya editorial yang sangat mentah (*raw*), *edgy*, dan mengandalkan tata letak *grid* yang ketat (Neubrutalism). Target audiensnya adalah kolektor jam tangan dan *fashion enthusiast*. Pendekatan visualnya bertumpu pada ekstremitas: tipografi raksasa, garis pembatas hitam 1px yang tegas, dan ketiadaan ruang lengkung, memberikan kesan maskulin dan sangat mekanikal—cocok untuk produk jam tangan.
## Development Stack
Sistem menggunakan **Laravel** dan **PHP** di *backend* (dengan **Supabase** sebagai *database/auth*), serta **Tailwind v4**, **DaisyUI**, **Flowbite**, dan **Alpine.js** di *frontend*. Semua komponen UI dari DaisyUI dan Flowbite harus di-*override* secara agresif menggunakan *utility classes* Tailwind (`rounded-none`, `shadow-none`) agar sesuai dengan estetika *brutalist*.
## Colors
Palet monokromatik ekstrem. Hitam dan Putih digunakan untuk 95% antarmuka. Abu-abu terang (Surface) hanya digunakan untuk memisahkan latar belakang gambar jam tangan dari kanvas putih murni, sementara teks sekunder menggunakan abu-abu medium agar sedikit *muted*.
## Typography
Hierarki sangat kuat dan elegan. *Heading* menggunakan *Satoshi* dengan *weight* super tebal dan huruf kapital untuk kesan premium. *Body text* menggunakan *IBM Plex Sans* untuk memberikan nuansa teknis, presisi yang identik dengan jam tangan, dengan tingkat keterbacaan tinggi. Sentuhan *Instrument Serif* (*italic*) digunakan untuk kutipan, aksen editorial, atau slogan agar nuansanya semakin mewah dan klasik (high-end).
## Spacing & Layout
*Layout* bergantung sepenuhnya pada *grid* (terutama 4 kolom untuk produk). *Margin* antarkolom dihilangkan sepenuhnya, diganti dengan *border* 1px yang saling bersinggungan. *Whitespace* sangat lega di area teks/hero, tapi sangat padat di area navigasi dan detail *card*.
## Shapes & Elevation
**Zero tolerance for curves and shadows.** Semua sudut harus 0px (tajam). Tidak ada `box-shadow` sama sekali. Elevasi dan pemisahan konten dicapai secara murni melalui *border* 1px dan pergantian warna *background* (putih ke hitam).
## Rules to Never Break
- Tidak boleh ada `border-radius` sekecil apa pun, timpa semua *default* dari komponen Flowbite/DaisyUI.
- Tidak boleh ada `box-shadow` atau gradasi.
- Komponen *grid* (terutama *product list*) harus selalu dibatasi oleh garis hitam 1px.