# Solution

Prototype polution ở 2 dòng code:

```html
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/mootools/1.6.0/mootools-core.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/mootools-more/1.6.0/mootools-more-compressed.js"></script>
```

Ta lợi dụng để set cho `accessGranted=blabla` (nếu không sẽ cần `token` cho admin's note)

Tiếp đó lợi dụng `setTimeout(() => {window.location.search = ""}, 5000);` => XS Leaks với `history.length`.

open một cửa sổ mới sau đó set URL `.../search=<FLAG>&__proto__[accessGranted]=blabla`, nếu flag bắt đầu với chuỗi đang brute force thì set window.location về lại page exploit (để không vi phạm SOP) và `history.length==3` thì kí tự đó thỏa mãn.

