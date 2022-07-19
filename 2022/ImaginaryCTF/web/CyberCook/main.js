var a = func1;
(function(arg1, arg2) {
    var x = func1,
        y = arg1();
    while (true) {
        try {
            var temp = parseInt(x(0xc1)) / 0x1 * (-parseInt(x(0xd7)) / 0x2) + -parseInt(x(0xd2)) / 0x3 + -parseInt(x(0xc2)) / 0x4 + parseInt(x(0xcd)) / 0x5 + -parseInt(x(0xd1)) / 0x6 * (-parseInt(x(0xc7)) / 0x7) + parseInt(x(0xcb)) / 0x8 + parseInt(x(0xd4)) / 0x9;
            if (temp === arg2) break;
            else y.push(y.shift());
        } catch (_0x56eedd) {
            y.push(y.shift());
        }
    }
}(func2, 604858));

function func1(arg1, arg2) {
    var f2 = func2();
    return func1 = function(func171, _0x4b7c6b) {
        func171 = func171 - 0xc1;
        var _0x4c557d = f2[func171];
        return _0x4c557d;
    }, func1(arg1, arg2);
}

function getRequests() {
    var f1 = func1,
        arr_param = location.search(1, location.search.length).split('&'),
        obj = {},
        temp, i;
    for (i = 0; i < arr_param.length; i += 1) {
        temp = arr_param[i].split('='), obj[decodeURIComponent(temp[0]).toLowerCase()] = decodeURIComponent(temp[1]);
    }
    return obj;
};
var q = getRequests();

function htoa(arg) {
    var _0x467ca4 = func1,
        arg2str = arg.toString(),
        res = '';
    for (var i = 0; i < arg2str.length; i += 2) res += String.fromCharCode(parseInt(arg2str.substr(i, 2), 10));
    return res;
}



function func2() {
    var _0x3edd76 = ['input', '481712cZxNOP', 'substring', '2238515cgMvuB', 'innerHTML', 'toString', 'action', '6FAseem', '359316oCQtEb', 'value', '9136701kFRNNs', 'ret', '_base64_encode', '934mrQNMV', '892NtHIau', '2064796CAzimC', 'onRuntimeInitialized', 'length', 'split', 'charCodeAt', '940009oTmwww', 'search', 'getElementById'];
    func2 = function() {
        return _0x3edd76;
    };
    return func2(); // return array
}

Module["onRuntimeInitialized"] = function() {
    var _0x16a32e = a; // func1
    q.action == 'base64' && (document.getElementById('input').value = htoa(q.input), s(q.input));
};

function s(arg) {
    var _0xac08b1 = a, // func1
        _0x20f1a9 = allocate(intArrayFromString(arg), ALLOC_NORMAL),
        _0x14b0dd = document.getElementById('ret'),
        _0x586bc0 = Module["_base64_encode"](_0x20f1a9, arg.length / 2);
    _0x14b0dd.innerHTML = AsciiToString(_0x586bc0), initialized = 1;
}