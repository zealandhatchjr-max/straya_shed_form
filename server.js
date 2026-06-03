const http = require("http");
const fs = require("fs");
const path = require("path");

const host = "127.0.0.1";
const port = Number(process.env.PORT || 4173);
const root = __dirname;
const envPath = path.join(root, ".env");

if (fs.existsSync(envPath)) {
  const envFile = fs.readFileSync(envPath, "utf8");
  for (const line of envFile.split(/\r?\n/)) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith("#")) continue;

    const separatorIndex = trimmed.indexOf("=");
    if (separatorIndex === -1) continue;

    const key = trimmed.slice(0, separatorIndex).trim();
    const value = trimmed.slice(separatorIndex + 1).trim().replace(/^['"]|['"]$/g, "");
    if (key && !process.env[key]) {
      process.env[key] = value;
    }
  }
}

const mimeTypes = {
  ".html": "text/html; charset=utf-8",
  ".css": "text/css; charset=utf-8",
  ".js": "text/javascript; charset=utf-8",
  ".json": "application/json; charset=utf-8",
  ".svg": "image/svg+xml",
  ".png": "image/png",
  ".webp": "image/webp",
};

function isAllowedStaticPath(normalizedPath) {
  if (["/index.html", "/app.js", "/styles.css"].includes(normalizedPath)) {
    return true;
  }

  return normalizedPath.startsWith("/assets/") && !normalizedPath.includes("/.");
}

const server = http.createServer((request, response) => {
  const requestPath = new URL(request.url, `http://${host}:${port}`).pathname;

  if (requestPath === "/config.js") {
    const publicConfig = {
      googleMapsApiKey: process.env.STRAYA_SHEDS_GOOGLE_MAPS_API_KEY || "",
      addressCountry: "au",
    };

    response.writeHead(200, { "Content-Type": "text/javascript; charset=utf-8" });
    response.end(`window.SHED_QUOTE_CONFIG = ${JSON.stringify(publicConfig)};`);
    return;
  }

  const normalizedPath = requestPath === "/" ? "/index.html" : requestPath;
  if (!isAllowedStaticPath(normalizedPath)) {
    response.writeHead(404);
    response.end("Not found");
    return;
  }

  const filePath = path.join(root, normalizedPath);
  const resolvedPath = path.resolve(filePath);

  if (!resolvedPath.startsWith(root)) {
    response.writeHead(403);
    response.end("Forbidden");
    return;
  }

  fs.readFile(resolvedPath, (error, content) => {
    if (error) {
      response.writeHead(404);
      response.end("Not found");
      return;
    }

    const contentType = mimeTypes[path.extname(resolvedPath)] || "application/octet-stream";
    response.writeHead(200, { "Content-Type": contentType });
    response.end(content);
  });
});

server.listen(port, host, () => {
  console.log(`Shed quote app running at http://localhost:${port}`);
});
