# STATE.md — econtrisac.com

## 2026-09-29 — Reestructura one-page → multi-página SEO

### Estructura
El antiguo one-page (`index.html` con 12 modales `#manometro`, `#valvulas`, etc.) se dividió en un home y una página por categoría:

| URL | Ancla original | Contenido |
|---|---|---|
| `/` | — | Home: slider, Nosotros, catálogo con enlaces a categorías, servicios, contacto |
| `/manometros/` | `#manometro` | 13 tipos de manómetros + accesorios |
| `/termometros/` | `#termometro` | 5 grupos (bimetálicos, bastón, capilar, controles, accesorios) |
| `/termometros-laboratorio/` | `#termometrolab` | Digitales, de ambiente, de vidrio |
| `/filtros-y-accesorios/` | `#filtrosvi` | Filtros tipo Y, visores, separadores, eliminadores de aire, check disco |
| `/trampas-vapor/` | `#trampasvapor` | Termostática, balde invertido, flotador, termodinámica |
| `/accesorios-vapor/` | `#accesoriovapor` | Accesorios para caldero (juntas, presostatos, columnas, válvulas) |
| `/valvulas/` | `#valvulas` | Bola, mariposa, aguja, check, globo, compuerta |
| `/valvulas-seguridad/` | `#valvulas_seg` | Válvulas de alivio y seguridad Kunkle |
| `/reductoras-presion/` | `#reductoras` | Reductoras de presión y reguladoras de temperatura |
| `/flujometros/` | `#flujometro` | Flujómetros electromagnéticos |
| `/hidrolavadoras/` | `#hidrolavadora` | Hidrolavadoras y aspiradoras Speedy Steel |
| `/calibradores/` | `#calibradores` | MPC-P+, HHP 700/1000, METCAL 40 |
| `/404.html` | — | Página de error (noindex) |

- Las especificaciones de las 9 categorías con tabla se copiaron textualmente del original (se verificaron las 303 líneas).
- Flujómetros, hidrolavadoras y calibradores solo tenían una imagen en el original; su contenido se transcribió de esas imágenes (verificar con el cliente).
- Correcciones de texto: "Dannfos/Danfos" → Danfoss, "Amstrong" → Armstrong; alt de imágenes corregidos (varios eran incorrectos).
- El home redirige por JS los enlaces antiguos con ancla (`/#manometro` → `/manometros/`).
- Todas las rutas de assets son absolutas (`/css/...`, `/images/...`): para previsualizar en local usar un servidor (`python3 -m http.server`), no `file://`.
- Navegación: en el home el menú usa `.main-menu` (scroll animado de `js/main.js`); en las páginas internas usa `.site-menu`, porque `main.js` hace `preventDefault()` en todo enlace dentro de `.main-menu`.
- El footer de todas las páginas enlaza a las 12 categorías.

### SEO
- Cada página: `title` ≤ 60 caracteres, `meta description` 150–160 con "Perú" + CTA, `canonical`, Open Graph, Twitter Cards, un solo `<h1>`, breadcrumbs visibles.
- JSON-LD (`@graph`): `Organization` + `WebSite` + `BreadcrumbList` en todas; `Product` + `CollectionPage` en categorías; `WebPage` + `ItemList` en home.
- Nueva imagen `images/og-image.jpg` (1200×630) generada a partir de `images/producto.jpg` + logo.
- Pendiente de reemplazar placeholders en todas las páginas: `VERIFICATION_CODE` (Search Console) y `G-MEASUREMENT_ID` (GA4).
- Roboto se carga por HTTPS desde el `<head>` (el `@import` de `css/main.css` usa `http://` y se bloquea en HTTPS).
- `robots.txt` y `sitemap.xml` (13 URLs, lastmod 2026-09-29) creados.

### .htaccess
- Se conserva el bloque cPanel de PHP 7.4 y `RewriteOptions inherit`.
- 301 a `https://econtrisac.com` (sin www) en un solo salto; `/.well-known/` excluido para la validación SSL.
- 301 de `/index.html` y `/categoria/index.html` a la URL limpia.
- `Options -Indexes`, `ErrorDocument 404 /404.html`, bloqueo de archivos ocultos y `.md`.
- Cabeceras: HSTS (1 año), X-Content-Type-Options, X-Frame-Options, X-XSS-Protection, Referrer-Policy, Permissions-Policy.
- gzip (mod_deflate) y caché: imágenes/fuentes 1 año, CSS/JS 1 mes, HTML sin caché.

### Pendientes conocidos (no modificados)
- Formulario de contacto: `action="http://www.econtrisac.com/sendmail.php"`; `sendmail.php` no está en el repositorio y `js/main.js` lo envía por AJAX sin datos. No se tocó por indicación expresa.
- `fonts/` contiene webfonts guardadas como `.html` por HTTrack (Font Awesome no decodifica).
