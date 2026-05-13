const http = require('http');
const next = require('next');
const fs = require('fs');

const dev = process.env.NODE_ENV !== 'production';
const port = process.env.PORT || 3000;

// Configuração para capturar erros fatais e salvar no error.log
process.on('uncaughtException', (err) => {
  fs.appendFileSync('error.log', `[UNCAUGHT] ${new Date().toISOString()}: ${err.stack}\n`);
});
process.on('unhandledRejection', (reason, promise) => {
  fs.appendFileSync('error.log', `[UNHANDLED] ${new Date().toISOString()}: ${reason}\n`);
});

try {
  const app = next({ dev });
  const handle = app.getRequestHandler();

  app.prepare().then(() => {
    const server = http.createServer((req, res) => {
      handle(req, res).catch((err) => {
        fs.appendFileSync('error.log', `[REQ ERROR] ${new Date().toISOString()}: ${err.stack}\n`);
        res.statusCode = 500;
        res.end('Internal Server Error');
      });
    });

    server.listen(port, (err) => {
      if (err) throw err;
      fs.appendFileSync('error.log', `[INFO] ${new Date().toISOString()}: Server started on port ${port}\n`);
    });
  }).catch((ex) => {
    fs.appendFileSync('error.log', `[PREPARE ERROR] ${new Date().toISOString()}: ${ex.stack}\n`);
    process.exit(1);
  });
} catch (globalErr) {
  fs.appendFileSync('error.log', `[GLOBAL FATAL] ${new Date().toISOString()}: ${globalErr.stack}\n`);
}
