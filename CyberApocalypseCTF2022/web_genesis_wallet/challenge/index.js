const express       = require('express');
const app           = express();
const path          = require('path');
const cookieParser  = require('cookie-parser');
const nunjucks      = require('nunjucks');
const routes        = require('./routes');
const Database      = require('./database');

const db = new Database('genesis.db');

app.use(express.json());
app.use(cookieParser());

nunjucks.configure('views', {
	autoescape: true,
	express: app
});

app.disable('etag');
app.set('views', './views');
app.use('/static', express.static(path.resolve('static')));

app.use(routes(db));

app.use(function(err, req, res, next) {
	res.status(500).json({message: 'Something went wrong!'});
});


(async () => {
	await db.connect();
	await db.migrate();
	app.listen(1337, '0.0.0.0', () => console.log('Listening on port 1337'));
})();
