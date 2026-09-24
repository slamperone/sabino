# Contexto del Proyecto: Puenter Agency (Sabino)

Este documento centraliza el estado actual, antecedentes técnicos y lineamientos de trabajo para continuar el desarrollo sin requerir inducción previa.

---

## 1. Resumen Ejecutivo
- **Nombre del proyecto**: Puenter Agency (`sabino`).
- **Objetivo principal**: Transformar un sitio web original exportado desde Webflow (plantilla *Aries*) en una aplicación web moderna (SPA / Frontend Framework) desacoplada por completo del ecosistema Webflow.
- **Repositorio remoto**: [https://github.com/slamperone/sabino.git](https://github.com/slamperone/sabino.git)
- **Rama activa**: `feat/spa`
- **Ubicación local activa**: `/Users/migueladriantrejodominguez/Projects/sabino-final-orasi`

---

## 2. Configuración de Git e Identidad (CRÍTICO)
> [!IMPORTANT]
> Este proyecto es de carácter **personal**. **NO utilizar ni alterar las credenciales globales** de la máquina (asociadas a Elektra).

- **Configuración local (`.git/config`)**:
  - `user.name`: `slamperone`
  - `user.email`: `miguel.adrian.trejo@gmail.com`
  - `remote.origin.url`: `https://slamperone@github.com/slamperone/sabino.git`
- **Regla**: Toda operación Git en este repositorio debe respetar exclusivamente la configuración local.

---

## 3. Arquitectura de Carpetas y Flujo de Trabajo
Existen dos ubicaciones relevantes en la máquina:

1. **`sabino-final-orasi` (Directorio Activo y Fuente de la Verdad)**:
   - Contiene únicamente el código depurado y necesario para el funcionamiento del index / SPA.
   - Todo cambio de código, nuevo componente, refactorización y commit se realiza exclusivamente aquí.
2. **`sabino2/puenter-agency-wip.webflow.io` (Cantera / Legacy / Solo Lectura)**:
   - Contiene la exportación masiva original de Webflow.
   - Incluye páginas secundarias aún no migradas (`about/`, `contact/`, `blog/`, `team/`, `template/`, `works/`).
   - Contiene el documento `Feedback web puenter.pdf`.
   - **No se edita código aquí**: se utiliza únicamente para consultar o extraer recursos/HTML cuando se requiera migrar una nueva página.

---

## 4. Estado de Avance y Trabajo Realizado
1. **Desacoplamiento de Webflow**:
   - Eliminación de scripts remotos de Webflow y CDNs externos.
   - Preservación y adaptación de scripts locales de animación (`js/app.chunk.1.js`, `js/app.chunk.2.js`, `js/app.main.js`).
2. **Migración a Astro (^4.16.19)**:
   - Configuración de Astro con servidor de desarrollo (`npm run dev`) y compilación estática (`npm run build`).
   - Modularización de componentes reutilizables:
     - `src/layouts/BaseLayout.astro`: Shell base con `<head>`, metatags, Typekit y estilos unificados.
     - `src/components/Navbar.astro`: Header responsive con logo SVG vectorial y menú animado.
     - `src/components/Footer.astro`: Footer unificado de Puenter Agency.
     - `src/components/BottomNavbar.astro`: Dock flotante inferior de proyectos y navegación.
     - `src/components/WorkCard.astro`: Tarjeta de proyecto con seguimiento hover y marquesina.
     - `src/components/LastCta.astro`: Sección CTA final con imágenes flotantes.
   - Páginas migradas en Astro:
     - `src/pages/index.astro`: Home modularizado y dinámico.
     - `src/pages/works/steeeezy.astro`: Primer caso de estudio migrado con assets locales.

---

## 5. Estructura de Archivos del Repositorio Actual
```text
sabino-final-orasi/
├── astro.config.mjs         # Configuración del framework Astro
├── package.json             # Dependencias y scripts (dev, build, preview)
├── PROJECT_CONTEXT.md       # Contexto técnico del proyecto
├── public/                  # Assets estáticos servidos directamente en la raíz
│   ├── img/                 # Fondos, iconos SVGs, imágenes de proyectos y clientes
│   └── js/                  # Scripts locales de interacción y animación
└── src/
    ├── components/          # Componentes modulares reutilizables
    │   ├── BottomNavbar.astro
    │   ├── Footer.astro
    │   ├── LastCta.astro
    │   ├── Navbar.astro
    │   └── WorkCard.astro
    ├── layouts/             # Plantilla y Head común
    │   └── BaseLayout.astro
    ├── pages/               # Enrutamiento basado en archivos
    │   ├── index.astro      # Página principal (/)
    │   └── works/
    │       └── steeeezy.astro # Caso de estudio (/works/steeeezy)
    └── styles/
        └── styles.css       # Hoja de estilos global complementada
```

---

## 6. Comandos de Trabajo
- `npm run dev`: Inicia el servidor de desarrollo local en `http://localhost:3000`.
- `npm run build`: Genera el build de producción estático en `dist/` (100% optimizado).
- `npm run preview`: Previsualiza localmente el build de producción.

---

## 7. Siguientes Pasos (Roadmap)
1. **Migración de Casos de Estudio Restantes**: Migrar los proyectos adicionales (`mercury`, `straps`, etc.) reutilizando `BaseLayout` y `WorkCard`.
2. **Migración de Páginas Secundarias**: Extraer progresivamente desde `sabino2` las secciones requeridas (`about`, `contact`, `blog`), transformándolas a páginas `.astro`.
3. **Optimización Adicional**: Posible transición de datos de proyectos a colecciones de contenido (`src/content/works/`).

