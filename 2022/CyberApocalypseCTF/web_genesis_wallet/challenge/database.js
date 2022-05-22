const sqlite = require('sqlite-async');
const crypto = require('crypto');
const OTPHelper = require('./helpers/OTPHelper');

class Database {
    constructor(db_file) {
        this.db_file = db_file;
        this.db = undefined;
    }

    async connect() {
        this.db = await sqlite.open(this.db_file);
    }

    async migrate() {
        let uOTPKey = OTPHelper.genSecret();
        console.log(`[-] uOTPKey: ${uOTPKey}`)
        let uAddress = crypto.createHash('md5').update('icarus').digest("hex");
        console.log(`[-] uAddress: ${uAddress}`)
        return this.db.exec(`
			DROP TABLE IF EXISTS users;
			CREATE TABLE users (
				id         INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				username   VARCHAR(255) NOT NULL UNIQUE,
				password   VARCHAR(255) NOT NULL,
				balance    DOUBLE DEFAULT 0.100,
				otpkey     VARCHAR(255) NULL,
				address    VARCHAR(36) NOT NULL UNIQUE
			);

			INSERT OR IGNORE INTO users (username, password, balance, otpkey, address)
				VALUES ('icarus', 'FlyHighToTheSky', 1337.10, '${uOTPKey}', '${uAddress}');

			DROP TABLE IF EXISTS transactions;
			CREATE TABLE transactions (
				id               INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
				trxid            VARCHAR(36)  NOT NULL,
				sender_addr      VARCHAR(36)  NOT NULL,
				receiver_addr    VARCHAR(36)  NOT NULL,
				amount           DOUBLE       NOT NULL,
				comment          TEXT         NULL,
				verified         INTEGER      DEFAULT 0,
				created_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
			);

		`);
    }

    async registerUser(user, pass) {
        return new Promise(async(resolve, reject) => {
            try {
                let address = crypto.createHash('md5').update(user).digest("hex");
                let stmt = await this.db.prepare('INSERT INTO users (username, password, address) VALUES ( ?, ?, ?)');
                resolve(await stmt.run(user, pass, address));
            } catch (e) {
                reject(e);
            }
        });
    }

    async loginUser(user, pass) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT username, otpkey FROM users WHERE username = ? and password = ?');
                resolve(await stmt.get(user, pass));
            } catch (e) {
                reject(e);
            }
        });
    }

    async getUser(user) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT * FROM users WHERE username = ?');
                resolve(await stmt.get(user));
            } catch (e) {
                reject(e);
            }
        });
    }

    async setOTPKey(username, otpkey) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('UPDATE users SET otpkey = ? WHERE username = ?');
                resolve(await stmt.run(otpkey, username));
            } catch (e) {
                reject(e);
            }
        });
    }

    async getOTPKey(username) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT otpkey FROM users WHERE username = ?');
                resolve(await stmt.get(username));
            } catch (e) {
                reject(e);
            }
        });
    }

    async listUsers() {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT * FROM users');
                resolve(await stmt.all());
            } catch (e) {
                reject(e);
            }
        });
    }

    async addTransaction(sender, receiver, balance, amount, note) {
        return new Promise(async(resolve, reject) => {
            try {
                let trxid = crypto.createHash('md5').update(crypto.randomBytes(10).toString('hex')).digest("hex");
                let stmt = await this.db.prepare(`
					INSERT INTO transactions (trxid, sender_addr, receiver_addr, amount, comment, verified)
						VALUES (?, ?, ?, ?, ?, 0);
				`);
                resolve(await stmt.run(trxid, sender, receiver, amount, note));
            } catch (e) {
                reject(e);
            }
        });
    }

    async listTransactions(address) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT * FROM transactions where sender_addr = ? or receiver_addr = ? order by id desc');
                resolve(await stmt.all(address, address));
            } catch (e) {
                reject(e);
            }
        });
    }

    async getTransaction(trxid) {
        return new Promise(async(resolve, reject) => {
            try {
                let stmt = await this.db.prepare('SELECT * FROM transactions where trxid = ?');
                resolve(await stmt.get(trxid));
            } catch (e) {
                reject(e);
            }
        });
    }

    async verifyTransaction(trxid, sender, receiver, amount) {
        return new Promise(async(resolve, reject) => {
            try {
                // make transaction verified
                let stmt = await this.db.prepare('UPDATE transactions SET verified = 1 where trxid = ?');
                await stmt.run(trxid);

                // transfer funds
                let stmt2 = await this.db.prepare('UPDATE users SET balance = balance - ? WHERE address = ?');
                await stmt2.run(amount, sender);

                let stmt3 = await this.db.prepare('UPDATE users SET balance = balance + ? WHERE address = ?');
                await stmt3.run(amount, receiver);
                resolve();

            } catch (e) {
                reject(e);
            }
        });
    }

}

module.exports = Database;