const crypto = require('crypto')
const { authenticator } = require('@otplib/preset-default');
const qrcode = require('qrcode')

const genSecret = () => {
    return authenticator.generateSecret();
}

const test = (secret) => {
    return authenticator.generate(secret);
}

const genQRcode = async (username, secret) => {
    return new Promise(async (resolve, reject) => {
        const otpauth = authenticator.keyuri(username, 'Genesis', secret);
        qrcode.toDataURL(otpauth, (err, imageUrl) => {
            if (err) reject(err);
            resolve(imageUrl);
        });
    });
}

const verifyOTP = async (otpkey, otp) => {
    return new Promise(async (resolve, reject) => {
        try {
            isValid = authenticator.check(otp, otpkey);
            if (isValid) resolve(true);
            reject(false);
        }
        catch (err) {
            reject(err);
        }
    });
}

module.exports = {
	genQRcode,
    genSecret,
    verifyOTP,
     test
}