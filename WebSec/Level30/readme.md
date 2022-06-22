# Solution

Ta thấy để có được `$flag` thì ta cần trigger đc `__destruct` của class B và thường thì method này sẽ được trigger khi chương trình kết thúc. Nhưng ở đay có một điều đặt biệt đó là hàm `throw new Exception('Something tragic happened');` tạo ra một exception và khiến method kia không được gọi. Vậy thì điều ta cần làm đó là trigger method `__destruct` ngay sau hàm `unserialize()` - kĩ thuật này có tên là `fast destruct`.

Từ bài viết [này](https://github.com/hinemo123/WriteUp/tree/master/Deserialize) của một đàn anh. Mình sẽ contruct một array với key => value là

```
1 => new class B
2 => 2
```
Sau khi có được dạng serialized của nó thì chỉnh key của value `2` lại thành 1. Lúc này ta sẽ có thể áp dụng `fast destruct`.