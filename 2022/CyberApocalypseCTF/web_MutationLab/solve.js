const express = require('express');
const session = require('cookie-session');

const app = express();
const cookieParser = require('cookie-parser');

app.use(express.json({ limit: '2mb' }))
app.use(cookieParser())

app.use(session({
    name: 'session',
    keys: (['5921719c3037662e94250307ec5ed1db'])
}))

app.get('/', (req, res) => {
    req.session = { "username": "admin" }
    res.send('haha')
})

app.listen(3333)