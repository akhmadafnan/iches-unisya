// Build and serve only the local fixture; no Laravel route or public/hot mutation.
import { build } from 'vite';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { resolve, join, extname, sep } from 'node:path';
import { mkdtempSync, readFileSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { createServer } from 'node:http';

const output = mkdtempSync(join(tmpdir(), 'iches-ui01c-build-'));
await build({
    configFile: false,
    resolve: { alias: { '@': resolve('resources/js') } },
    plugins: [vue(), tailwindcss()],
    build: { outDir: output, emptyOutDir: false, rollupOptions: { input: resolve('harness/evidence/ICH-UI-01C/index.html') } },
});
const types = { '.html': 'text/html', '.js': 'text/javascript', '.css': 'text/css', '.woff2': 'font/woff2' };
const server = createServer((request, response) => {
    const file = resolve(output, '.' + new URL(request.url, 'http://127.0.0.1').pathname);
    if (!file.startsWith(output + sep)) { response.writeHead(403).end(); return; }
    try {
        const target = extname(file) ? file : join(file, 'index.html');
        response.setHeader('Content-Type', types[extname(target)] || 'application/octet-stream');
        response.end(readFileSync(target));
    } catch { response.writeHead(404).end(); }
});
server.listen(8012, '127.0.0.1');
console.log('Local fixture: http://127.0.0.1:8012/harness/evidence/ICH-UI-01C/');
process.on('SIGINT', () => server.close(() => {
    if (!resolve(output).startsWith(join(resolve(tmpdir()), 'iches-ui01c-build-'))) throw new Error('Unexpected temporary build path');
    rmSync(output, { recursive: true, force: true });
    process.exit(0);
}));
