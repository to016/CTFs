const express = require("express")
const app = express()
const undici = require("undici")
const hbs = require('express-handlebars')
const bodyParser = require("body-parser")

app.use(bodyParser.json())
app.use(bodyParser.urlencoded({ extended: false }))

app.engine('hbs', hbs.engine({
    extname: '.hbs'
}));


app.set('view engine', 'hbs');

app.get("/", (req, res) => {
	res.render('index')
})

app.post('/view', async (req, res) => {
	try {
		const { url } = req.body
		if(/localhost/i.test(url) || /[0-9]/.test(url)){
			throw new Error("bad hacker")
		}
		const resp = await undici.request(url)
		return res.render('view', {
			output: await resp.body.text()
		})

	} catch(e){
		res.json({
			'error': e.message
		})
	}
})

app.get("/flag", (req, res) => {
	if(req.ip != '::ffff:127.0.0.1'){
		return res.json({
			"error": "only admin can access this!"
		})
	}
	return res.json({
		"flag": "bsides{fake_flag}"
	})
})

app.listen(3000, console.log("listening on port 3000"))