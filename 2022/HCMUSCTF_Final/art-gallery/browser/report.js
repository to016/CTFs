const puppeteer = require('puppeteer');

function getRandomInt(max) {
	return Math.floor(Math.random() * max);
}

async function run(url) {
	let browser;

	try {
		module.exports.open = true;
		browser = await puppeteer.launch({
			headless: true,
			pipe: true,
			args: ['--incognito', '--no-sandbox', '--disable-setuid-sandbox'],
			slowMo: 10
		});

		let page = (await browser.pages())[0];
		let homepageURL = process.env.HOMEPAGE_URL;

		await page.goto(homepageURL + '/login.php');
		await page.type('[name="username"]', "admin");
		await page.type('[name="password"]', process.env.ADMIN_PASSWORD);

		await Promise.all([
			page.click('[type="submit"]'),
			page.waitForNavigation({ waituntil: 'domcontentloaded' })
		]);

		await page.goto(url);
		await page.waitForTimeout(7500);

		let imageID = getRandomInt(5) + 1;

		await page.goto(homepageURL);
		await page.type('#comment-' + imageID + ' [name="content"]', "After seeing this photo, my mood is very happy. So here is a gift for all my friends: " + process.env.FLAG);

		let button = await page.$('#comment-' + imageID + ' [type="submit"]');
		await Promise.all([
			button.evaluate(b => b.click()),
			page.waitForNavigation({ waituntil: 'domcontentloaded' })
		]);

		await browser.close();
	} catch (e) {
		console.error(e);
		try { await browser.close() } catch (e) { }
	}

	module.exports.open = false;
}

module.exports = { open: false, run }