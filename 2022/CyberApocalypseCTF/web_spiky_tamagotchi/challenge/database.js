let mysql = require('mysql')

class Database {

    constructor() {
        this.connection = mysql.createConnection({
            host: 'localhost',
            user: 'rh0x01',
            password: 'r4yh4nb34t5b1gm4c',
            database: 'spiky_tamagotchi'
        });
    }

    async registerUser(user, pass) {
        return new Promise(async(resolve, reject) => {
            let stmt = 'INSERT INTO users (username, password) VALUES (?, ?)';
            this.connection.query(stmt, [user, pass], (err, result) => {
                if (err)
                    reject(err)
                resolve(result)
            })
        });
    }

    async loginUser(user, pass) {
        return new Promise(async(resolve, reject) => {
            let stmt = 'SELECT username FROM users WHERE username = ? AND password = ?';
            this.connection.query(stmt, [user, pass], (err, result) => {
                if (err || result.length == 0)
                    reject(err)
                resolve(result)
            })
        });
    }

}

module.exports = Database;