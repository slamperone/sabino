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
Se ha limpiado y optimizado la página principal (`index.html`):

1. **Desacoplamiento de Webflow**:
   - Eliminación de scripts, chunks y dependencias directas no utilizadas.
   - Preservación y adaptación de scripts necesarios de animación (`js/app.chunk.1.js`, `js/app.chunk.2.js`, `js/app.main.js`).
2. **Estructura y Assets**:
   - Migración de la sección de tarjetas de proyectos (`works-cards`) integradas desde la variante `home-3`.
   - Reemplazo y limpieza de iconos de servicios (*Branding*, *Digital*, *Motion*) a assets locales/inline.
   - Adición y optimización de logotipos de clientes en formato SVG (`clients-bcdme.svg`, `clients-everpure.svg`, `clients-seicym.svg`, `clients-serex.svg`).
   - Corrección del menú responsive móvil (botón hamburguesa y transiciones).
3. **Efectos e Interactividad**:
   - Corrección de comportamiento en el escalado hover (`image-background-hover`).
   - Sincronización de animaciones en marquesinas (`marquee-works`).

---

## 5. Estructura de Archivos del Repositorio Actual
```text
sabino-final-orasi/
├── .gitignore               # Ignora .DS_Store, logs y configs de editores
├── PROJECT_CONTEXT.md       # Este archivo de contexto
├── index.html               # Home funcional desacoplado
├── css/
│   └── styles.css           # Estilos principales del proyecto
├── js/
│   ├── app.chunk.1.js
│   ├── app.chunk.2.js
│   └── app.main.js
└── img/                     # Recursos visuales del Home (fondos, SVGs, tarjetas)
```

---

## 6. Siguientes Pasos (Roadmap)
1. **Definición de Framework**: Determinar si se continuará con Vanilla modular o se migrará a Vite / Astro / React / Vue.
2. **Migración de Páginas Secundarias**: Extraer progresivamente desde `sabino2` las secciones requeridas (`about`, `contact`, `works`), adaptándolas a componentes reutilizables.
3. **Atención a Feedback**: Revisar las observaciones registradas en `Feedback web puenter.pdf` según prioridades.
