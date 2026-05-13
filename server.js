const { createServer } = require('http')
const { parse } = require('url')
const next = require('next')

const dev = process.env.NODE_ENV !== 'production'
const hostname = 'localhost'
// O cPanel/Phusion Passenger injeta automaticamente a variável PORT
const port = process.env.PORT || 3000

// Inicializa o Next.js
const app = next({ dev, hostname, port })
const handle = app.getRequestHandler()

app.prepare().then(() => {
  createServer(async (req, res) => {
    try {
      const parsedUrl = parse(req.url, true)
      await handle(req, res, parsedUrl)
    } catch (err) {
      console.error('Erro ao processar rota', req.url, err)
      res.statusCode = 500
      res.end('Erro interno do servidor')
    }
  }).listen(port, (err) => {
    if (err) throw err
    console.log(`> Servidor rodando na porta ${port}`)
  })
})
