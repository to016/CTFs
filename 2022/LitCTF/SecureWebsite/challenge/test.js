var p = 3217;
var q = 6451;
var e = 17;
// Hmmm, RSA calculator says to set these values
var N = p * q; // 20752867
// 4880753
var phi = (p - 1) * (q - 1);
var d = 4880753;

console.log(N)

function encryptRSA(num) {
    return modPow(num, e, N);
}

function modPow(base, exp, mod) {
    var result = 1;
    for (var i = 0; i < exp; ++i) {
        result = (result * base) % mod;
    }
    return result;
}

var pwd = "password"
var arr = [];
for (var i = 0; i < pwd.length; ++i) {
    arr.push(encryptRSA(pwd.charCodeAt(i)));
}
console.log(arr.join());