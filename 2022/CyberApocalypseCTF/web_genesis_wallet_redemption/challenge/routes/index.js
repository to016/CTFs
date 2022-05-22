const fs = require('fs');
const path = require('path');
const bot = require('../bot');
const express = require('express');
const router = express.Router({ caseSensitive: true });
const JWTHelper = require('../helpers/JWTHelper');
const OTPHelper = require('../helpers/OTPHelper');
const MDHelper = require('../helpers/MDHelper');
const AuthMiddleware = require('../middleware/AuthMiddleware');

let db;
let addressExp = /^[a-f0-9]{32}$/i;
let trxLocked = false;
const response = data => ({ message: data });

router.get(/^\/(\w{2})?\/?$/, (req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';

    res.render(`${lang}/login.html`);
});

router.post('/api/register', async(req, res) => {
    const { username, password } = req.body;

    if (username && password) {
        return db.getUser(username)
            .then(user => {
                if (user) return res.status(401).send(response('Account already exists!'));
                return db.registerUser(username, password)
                    .then(() => res.send(response('Account registered successfully')))
            })
            .catch(() => res.status(500).send(response('Internal server error!')));
    }
    return res.status(401).send();
});

router.post('/api/login', async(req, res) => {
    const { username, password } = req.body;

    if (username && password) {
        return db.loginUser(username, password)
            .then(user => {
                otpkey = true;
                if (!user.otpkey) otpkey = false;
                JWTHelper.sign({ username: user.username, otpkey, verified: false })
                    .then(token => {
                        res.cookie('session', token, { maxAge: 43200000 });
                        res.send(response('User authenticated successfully!'));
                    })
            })
            .catch(() => res.status(403).send(response('Invalid username or password!')));
    }
    return res.status(500).send(response('Missing required parameters!'));
});

router.get(/^\/(\w{2})?\/?(setup|reset)-2fa/, AuthMiddleware, async(req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';
    let otpkey = OTPHelper.genSecret();

    return db.setOTPKey(req.user.username, otpkey)
        .then(() => {
            return res.render(`${lang}/setup-2fa.html`, { otpkey: otpkey, action: req.params[1] });
        })
        .catch(err => {
            console.log(err);
            return res.status(500).send(response('Something went wrong!'));
        });
});

router.post('/api/2fa/qrcode', AuthMiddleware, async(req, res) => {
    const { otpkey } = req.body;

    return OTPHelper.genQRcode(req.user.username, otpkey)
        .then(imageURI => {
            req.user['otpkey'] = true;
            JWTHelper.sign(req.user)
                .then(token => {
                    res.cookie('session', token, { maxAge: 43200000 });
                    return res.send({ qr: imageURI });
                })
                .catch(e => {
                    return res.status(500).send(response('Something went wrong!'));
                });
        })
        .catch(err => {
            console.log(err)
            return res.status(500).send(response('Something went wrong!'));
        });
});

router.get(/^\/(\w{2})?\/?2fa/, AuthMiddleware, (req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';

    res.render(`${lang}/2fa.html`);
});

router.post('/api/2fa/verify', AuthMiddleware, async(req, res) => {
    const { otp } = req.body;
    let otpSecret = await db.getOTPKey(req.user.username);

    return OTPHelper.verifyOTP(otpSecret.otpkey, otp)
        .then(verified => {
            req.user['verified'] = verified;
            JWTHelper.sign(req.user)
                .then(token => {
                    res.cookie('session', token, { maxAge: 43200000 });
                    return res.send(response('2fa verified successfully!'));
                })
                .catch(e => {
                    return res.status(401).send(response('Something went wrong!'));
                });
        })
        .catch(err => {
            console.log(err);
            return res.status(500).send(response('Invalid OTP token supplied!'));
        });
});

router.get(/^\/(\w{2})?\/?dashboard/, AuthMiddleware, async(req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';

    return db.getUser(req.user.username)
        .then(user => {
            let flag = null;
            if (user.balance > 1337 && user.username != 'icarus') flag = fs.readFileSync('/flag.txt').toString();
            res.render(`${lang}/dashboard.html`, { user, flag });
        })
        .catch(() => res.status(500).send(response('Internal server error!')));
});

router.get(/^\/(\w{2})?\/?transactions/, AuthMiddleware, async(req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';

    return db.getUser(req.user.username)
        .then(async(user) => {
            let transactions = await db.listTransactions(user.address)
            res.render(`${lang}/transactions.html`, { user, transactions });
        })
        .catch(() => res.status(500).send(response('Internal server error!')));
});

router.get(/^\/(\w{2})?\/?settings/, AuthMiddleware, async(req, res) => {
    let lang = req.params[0];
    if (!lang) lang = 'en';

    return db.getUser(req.user.username)
        .then(user => {
            res.render(`${lang}/settings.html`, { user });
        })
        .catch(() => res.status(500).send(response('Internal server error!')));
});


router.post('/api/transactions/create', AuthMiddleware, async(req, res) => {
    const { amount, receiver, note } = req.body;
    if (trxLocked) return res.status(401).send(response('Please wait for the previous transaction to process first!'));

    return db.getUser(req.user.username)
        .then(user => {
            if (parseFloat(user.balance) < parseFloat(amount)) return res.status(403).send(response('Insufficient Funds!'));
            if (parseFloat(amount) < 0) return res.status(403).send(response('Funds cannot be negative!'));
            if (!addressExp.test(receiver)) return res.status(403).send(response('Invalid receiver address format!'));
            if (receiver == user.address) return res.status(403).send(response(`You can't send to your own address!`));
            trxLocked = true;

            safeNote = MDHelper.filterHTML(note);
            db.addTransaction(user.address, receiver, user.balance, parseFloat(amount), safeNote)
                .then(() => {
                    trxLocked = false;
                    return res.send(response('Transaction created successfully!'));
                })
                .catch(e => {
                    trxLocked = false;
                    console.log(e);
                    return res.status(500).send(response('Something went wrong, please try again!'))
                })
        })
        .catch((e) => {
            console.log(e);
            trxLocked = false;
            res.status(500).send(response('Internal server error!'));
        });
});

router.post('/api/transactions/verify', AuthMiddleware, async(req, res) => {
    const { trxid, otp } = req.body;
    if (trxLocked) return res.status(401).send(response('Please wait for the previous transaction to process first!'));

    return db.getUser(req.user.username)
        .then(async(user) => {
            db.getTransaction(trxid)
                .then(async(trx) => {
                    if (parseFloat(user.balance) < parseFloat(trx.amount)) return res.status(403).send(response('Insufficient Funds!'));
                    if (parseFloat(trx.amount) < 0) return res.status(403).send(response('Funds cannot be negative!'));
                    if (trx.sender_addr !== user.address) return res.status(403).send(response('Access denied!'));
                    trxLocked = true;

                    let otpSecret = await db.getOTPKey(req.user.username);
                    try {
                        validOTP = await OTPHelper.verifyOTP(otpSecret.otpkey, otp);
                    } catch (e) {
                        trxLocked = false;
                        return res.status(403).send(response('Invalid OTP supplied!'));
                    }

                    db.verifyTransaction(trx.trxid, trx.sender_addr, trx.receiver_addr, trx.amount)
                        .then(async() => {
                            await bot.viewTransactions();
                            trxLocked = false;
                            return res.send(response('Transaction processed successfully!'));
                        })
                        .catch(e => {
                            trxLocked = false;
                            console.log(e);
                            return res.status(500).send(response('Something went wrong, please try again!'))
                        })
                })
        })
        .catch((e) => {
            console.log(e);
            trxLocked = false;
            res.status(500).send(response('Internal server error!'));
        });
});

router.get('/logout', (req, res) => {
    res.clearCookie('session');
    return res.redirect('/');
});

module.exports = database => {
    db = database;
    return router;
};