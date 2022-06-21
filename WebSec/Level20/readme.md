# Solution

Từ [docs](https://www.phpinternalsbook.com/php5/classes_objects/serialization.html#:~:text=method%20of%20the-,Serializable%20interface,-and%20uses%20the) này ta nhận ra php còn cung cấp một interface là `Serializable` dùng riêng cho việc user muốn tự custom cách ser/der

Và sau khi được serialize thì sẽ có dạng `C` type specifier (thường thấy là `O` đối với object)


Payload:
`curl 'https://websec.fr/level20/index.php' -H 'Cookie: data=Qzo0OiJGbGFnIjo2Ontmb29iYXJ9'`