const JWTHelper = require('../helpers/JWTHelper');

module.exports = async (req, res, next) => {
	try{
		if (req.cookies.session === undefined) {
			if(!req.is('application/json')) return res.redirect('/');
			return res.status(401).json({ status: 'unauthorized', message: 'Authentication expired, please login again!' });
		}
		return JWTHelper.verify(req.cookies.session)
			.then(userInfo => {
				req.user = userInfo;
				if (!userInfo.otpkey) {
					if (req.path === '/setup-2fa' || req.path.startsWith('/api/2fa/')) return next();
					return res.redirect('/setup-2fa');
				}
				if (userInfo.otpkey) {
					if (req.path.includes('/setup-2fa')) return res.redirect('/2fa');
				}
				if (!userInfo.verified) {
					if (req.path === '/2fa' || req.path.startsWith('/api/2fa/')) return next();
					return res.redirect('/2fa');
				}
				return next();
			})
			.catch((e) => {
				console.log(e);
				res.redirect('/logout');
			});
	} catch(e) {
		console.log(e);
		return res.status(500).send('Internal server error');
	}
}