const fastify = require('fastify')();
const report = require('./report');

fastify.register(require('fastify-formbody'));

let reportSchema = {
	body: {
		type: 'object',
		properties: {
			url: { type: 'string', maxLength: 1000 },
		},
		required: ['url']
	}
}

fastify.after(() => {
	fastify.post('/report', {
		schema: reportSchema,
	}, (req, res) => {
		let { url } = req.body;

		if (!url.startsWith('http://') && !url.startsWith('https://')) {
			return res.send('URL is not valid');
		}
		console.log(url)

		if (report.open) {
			return res.send('Only one browser can be open at a time!');
		} else {
			report.run(url);
		}

		return res.send('URL has been reported.');
	});
})

fastify.listen(8002, '0.0.0.0');
console.log("Browser started!");