const https = require('https');
const http = require('http');
const fs = require('fs');
const path = require('path');

const keyPath = path.join(__dirname, 'potobooth.local+2-key.pem');
const certPath = path.join(__dirname, 'potobooth.local+2.pem');

if (!fs.existsSync(keyPath) || !fs.existsSync(certPath)) {
    console.error("Certificate files not found. Please run: ./mkcert potobooth.local localhost 127.0.0.1");
    process.exit(1);
}

const options = {
    key: fs.readFileSync(keyPath),
    cert: fs.readFileSync(certPath)
};

const proxy = https.createServer(options, (req, res) => {
    // Target is Laravel artisan serve on http://127.0.0.1:8080
    const targetOptions = {
        host: '127.0.0.1',
        port: 8080,
        path: req.url,
        method: req.method,
        headers: {
            ...req.headers,
            'x-forwarded-proto': 'https',
            'x-forwarded-host': req.headers.host
        }
    };

    const proxyReq = http.request(targetOptions, (proxyRes) => {
        // Forward response headers and status code
        res.writeHead(proxyRes.statusCode, proxyRes.headers);
        // Pipe the response body
        proxyRes.pipe(res);
    });

    proxyReq.on('error', (err) => {
        console.error('Proxy request error forwarding to Laravel:', err);
        res.writeHead(502, { 'Content-Type': 'text/plain; charset=utf-8' });
        res.end('Bad Gateway: Pastikan server Laravel (php artisan serve --port=8080) sudah berjalan.');
    });

    // Pipe the request body to target
    req.pipe(proxyReq);
});

// We listen on 8443 to avoid requiring root/sudo privileges (ports < 1024 require root)
const PORT = 8443;
proxy.listen(PORT, '0.0.0.0', () => {
    console.log(`\n==================================================`);
    console.log(`🔒 HTTPS Reverse Proxy berjalan aktif di:`);
    console.log(`👉 https://potobooth.local:${PORT}`);
    console.log(`👉 https://127.0.0.1:${PORT}`);
    console.log(`👉 https://localhost:${PORT}`);
    console.log(`==================================================`);
    console.log(`Forwarding traffic to Laravel backend at http://127.0.0.1:8080\n`);
});
