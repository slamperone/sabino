import fs from 'node:fs';
import path from 'node:path';

const distDir = path.resolve('dist');
const BASE = '/sabino';
const SITE = 'https://develupme.com';

function walk(dir) {
  let files = [];
  if (!fs.existsSync(dir)) return files;
  for (const item of fs.readdirSync(dir)) {
    const fullPath = path.join(dir, item);
    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      files = files.concat(walk(fullPath));
    } else if (item.endsWith('.html')) {
      files.push(fullPath);
    } else if (item === '.DS_Store') {
      fs.unlinkSync(fullPath);
    }
  }
  return files;
}

const htmlFiles = walk(distDir);

for (const file of htmlFiles) {
  let content = fs.readFileSync(file, 'utf-8');

  // 1. Prefix local "/img/" and '/img/' (without touching external URLs like https://.../img/)
  content = content.replace(/(["'])\/img\//g, `$1${BASE}/img/`);
  content = content.replace(/url\(\/img\//g, `url(${BASE}/img/`);

  // 2. Prefix local "/js/" and '/js/' (without touching external CDNs like cloudfront.net/js/)
  content = content.replace(/(["'])\/js\//g, `$1${BASE}/js/`);

  // 3. Prefix root hash links href="/#..." -> href="/sabino/#..."
  content = content.replace(/href="\/#/g, `href="${BASE}/#`);

  // 4. Prefix root href="/" -> href="/sabino/"
  content = content.replace(/href="\/"(?=[\s>])/g, `href="${BASE}/"`);

  // 5. Prefix /works links (e.g. href="/works", href="/works/...")
  content = content.replace(/(["'])\/works(?=[/"'?#\s>]|$)/g, `$1${BASE}/works`);

  // 6. Absolute URL for OpenGraph and Twitter images
  content = content.replace(/(<meta[^>]+content=")(?:\/sabino)?\/img\/([^"]+)("[^>]+(?:og:image|twitter:image)[^>]*>)/gi, `$1${SITE}${BASE}/img/$2$3`);
  content = content.replace(/(<meta[^>]+(?:og:image|twitter:image)[^>]+content=")(?:\/sabino)?\/img\/([^"]+)("[^>]*>)/gi, `$1${SITE}${BASE}/img/$2$3`);

  fs.writeFileSync(file, content, 'utf-8');
}

// Create .htaccess for Apache shared hosting
const htaccessContent = `# Apache configuration for ${SITE}${BASE}/
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase ${BASE}/

  # If an existing file or directory is requested, serve it directly
  RewriteCond %{REQUEST_FILENAME} -f [OR]
  RewriteCond %{REQUEST_FILENAME} -d
  RewriteRule ^ - [L]

  # Allow clean URLs without trailing slash to load their index.html
  RewriteRule ^works/?$ works/index.html [L]
  RewriteRule ^works/([^/]+)/?$ works/$1/index.html [L]

  # Fallback to index.html
  ErrorDocument 404 ${BASE}/index.html
</IfModule>

<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json image/svg+xml
</IfModule>
`;

fs.writeFileSync(path.join(distDir, '.htaccess'), htaccessContent, 'utf-8');

// Also save into public/ so future Astro builds copy it automatically
const publicDir = path.resolve('public');
fs.writeFileSync(path.join(publicDir, '.htaccess'), htaccessContent, 'utf-8');

console.log(`[prepare-dist] Successfully prepared dist for ${SITE}${BASE}/ (${htmlFiles.length} HTML files updated)`);
