const express        = require('express');
const router         = express.Router();
const JWTHelper      = require('../helpers/JWTHelper');
const SpikyFactor    = require('../helpers/SpikyFactor');
const AuthMiddleware = require('../middleware/AuthMiddleware');

const response = data => ({ message: data });

router.get('/', (req, res) => {
	return res.render('index.html');
});

router.post('/api/login', async (req, res) => {
	const { username, password } = req.body;

	if (username && password) {
		return db.loginUser(username, password)
			.then(user => {
				let token = JWTHelper.sign({ username: user[0].username });
				res.cookie('session', token, { maxAge: 3600000 });
				return res.send(response('User authenticated successfully!'));
			})
			.catch(() => res.status(403).send(response('Invalid username or password!')));
	}
	return res.status(500).send(response('Missing required parameters!'));
});

router.get('/interface', AuthMiddleware, async (req, res) => {
	return res.render('interface.html');
});

router.post('/api/activity', AuthMiddleware, async (req, res) => {
	const { activity, health, weight, happiness } = req.body;
	if (activity && health && weight && happiness) {
		return SpikyFactor.calculate(activity, parseInt(health), parseInt(weight), parseInt(happiness))
			.then(status => {
				return res.json(status);
			})
			.catch(e => {
				res.send(response('Something went wrong!'));
			});
	}
	return res.send(response('Missing required parameters!'));
});

router.get('/logout', (req, res) => {
	res.clearCookie('session');
	return res.redirect('/');
});

module.exports = database => {
	db = database;
	return router;
};