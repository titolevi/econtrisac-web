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

## 2026-09-29 — Tarjetas de producto + galería lightbox

- En las 9 categorías con tabla (manómetros, válvulas, válvulas de seguridad, termómetros, termómetros de laboratorio, filtros, reductoras, trampas, accesorios para caldero) la tabla de productos se reemplazó por un grid de tarjetas `.product-grid > .col-sm-6.col-md-4.col-lg-3 > .product-card`: imagen a todo el ancho, `<h2 class="h4 product-title">` (se conservan los `id` de ancla) y especificaciones en texto más pequeño. 1 por fila en móvil, 2 en tablet, 3–4 en escritorio. Textos, imágenes y `alt` sin cambios (verificado por script).
- Calibradores, flujómetros e hidrolavadoras solo tienen una imagen compuesta: se mantiene su tabla de especificaciones y la imagen se enmarca en `.product-card.product-card-single` (tamaño natural, centrada).
- Cada imagen enlaza a sí misma con `rel="prettyPhoto[gallery-<categoria>]"`; el lightbox navega entre los productos de la misma categoría. No existen versiones HD: el lightbox muestra el mismo archivo (180–950 px).
- `css/main.css`: nuevos estilos `.product-grid`, `.product-card`, `.product-card-img`, `.product-info`, `.product-card-single` (flexbox para alturas iguales, hover con sombra y zoom leve de imagen, respeta `prefers-reduced-motion`). `.manometro` de `estilos.css` no se tocó (lo usa el home).
- `js/main.js`: la inicialización existente de prettyPhoto ahora usa `{social_tools: false, deeplinking: false, show_title: true, theme: 'pp_default'}`. `deeplinking` se desactivó porque HTTrack alteró esa parte de `jquery.prettyPhoto.js` (reemplazó `/` por `index.html`).

### Pendientes conocidos (no modificados)
- Formulario de contacto: `action="http://www.econtrisac.com/sendmail.php"`; `sendmail.php` no está en el repositorio y `js/main.js` lo envía por AJAX sin datos. No se tocó por indicación expresa.
- `fonts/` contiene webfonts guardadas como `.html` por HTTrack (Font Awesome no decodifica).
