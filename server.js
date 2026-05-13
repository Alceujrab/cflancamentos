const http = require('http');
const next = require('next');

const dev = process.env.NODE_ENV !== 'production';
// Phusion Passenger will supply the PORT environment variable
const port = process.env.PORT || 3000;

const app = next({ dev });
const handle = app.getRequestHandler();

app.prepare().then(() => {
  const server = http.createServer((req, res) => {
    handle(req, res);
  });

  // DO NOT PASS HOSTNAME HERE! Passenger needs to bind to its internal socket.
  server.listen(port, (err) => {
    if (err) throw err;
    console.log(`> Ready on port ${port}`);
  });
}).catch((ex) => {
  console.error(ex.stack);
  process.exit(1);
});
