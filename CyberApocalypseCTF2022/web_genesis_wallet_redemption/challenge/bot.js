const puppeteer = require('puppeteer');
const JWTHelper = require('./helpers/JWTHelper');

const browser_options = {
    headless: true,
    args: [
        '--no-sandbox',
        '--disable-background-networking',
        '--disable-default-apps',
        '--disable-extensions',
        '--disable-gpu',
        '--disable-sync',
        '--disable-translate',
        '--hide-scrollbars',
        '--metrics-recording-only',
        '--mute-audio',
        '--no-first-run',
        '--safebrowsing-disable-auto-update',
        '--js-flags=--noexpose_wasm,--jitless'
    ]
};

const viewTransactions = async() => {
    try {
        const browser = await puppeteer.launch(browser_options);
        let context = await browser.createIncognitoBrowserContext();
        let page = await context.newPage();

        let token = await JWTHelper.sign({ username: 'icarus', otpkey: true, verified: true });
        console.log(`[-] token: ${token}`);
        await page.setCookie({
            name: "session",
            'value': token,
            domain: "127.0.0.1"
        });
        await page.goto('http://127.0.0.1/transactions', {
            waitUntil: 'networkidle2',
            timeout: 5000
        });
        await browser.close();
    } catch (e) {
        console.log(e);
    }
};

module.exports = { viewTransactions };