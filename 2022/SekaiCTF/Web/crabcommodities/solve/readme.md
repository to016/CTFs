# Crabcommomdities

Lại một bài được viết bằng rust của tác giả Strellic, lúc làm mình chỉ hi vọng là bug logic hoặc bug nào đấy không liên quan nhiều về gốc rễ của rust (tại mình bị thọt ngôn ngữ này 🥺) và cuối cùng nó là integer overflow 😘.

Sau khi register account thì sẽ được dẫn đến `/game`

![over_view](https://user-images.githubusercontent.com/77546253/193606696-ca2d4d48-8b4f-47b0-8e04-e4e9c1addd8b.png)

Về mặt tính năng, khởi điểm số tiền ta được cấp là 30000$ và có thể mua các sản phẩm ở góc dưới bên phải, mượn nợ (`Loan`), upgrade `Storage` ... và quan trọng là cần tới tận `2000000000$` để lấy được flag.

Check http request + kết hợp với source, ta chỉ gửi tên của item và quantity, giá của item được lưu ở phía server

![burp_req_1](https://user-images.githubusercontent.com/77546253/193606714-b49333ed-e287-480c-bbf8-d22cb4ab2826.png)

Vuln bài này xuất hiện ở hai chỗ và ta sẽ cần khai thác theo đúng thứ tự thì mới đủ tiền để mua flag, đầu tiên là `/upgrade`:

![vuln_1](https://user-images.githubusercontent.com/77546253/193606735-4a9ba7be-6e1d-4c72-9827-f5dd6f6934e2.png)

Theo những gì mình hiểu, biến `price` được khởi tạo bằng `let mut price = item.price;` sẽ mang kiểu i32 (<https://stackoverflow.com/questions/55903243/what-is-the-default-integer-type-in-rust>) và nhìn xuống dưới `price *= body.quantity;` => có khả năng bị integer overflow

Check `price` của item `Storage Upgrade` là `100000` và max `quantity` ta có thể gửi là `32767` đồng thời MAX của i32 là `2147483647` (<https://doc.rust-lang.org/std/primitive.i32.html>)

```bash
PS C:\Users\ASUS> python
Python 3.9.0 (tags/v3.9.0:9cf6752, Oct  5 2020, 15:34:40) [MSC v.1927 64 bit (AMD64)] on win32
Type "help", "copyright", "credits" or "license" for more information.
>>> 32767*100000 - 2147483647
1129216353
>>>
```

=> có thể khai thác integer overflow khiến cho price bị set thành giá trị âm và cuối cùng là `user.game.money.set(user.game.money.get() - price as i64);` giúp cộng thêm tiền

Khi làm mình không gửi quantity là `32767` lên server vì sau khi gửi và muốn upgrade thêm các item khác sẽ bị lỗi  `Too many upgrades purchased`:

![test_2](https://user-images.githubusercontent.com/77546253/193606766-a5279a83-e8b7-4927-abb0-25f1a0124e74.png)

-> tăng tiền lần 1 thành công

Overflow lần thứ hai là ở `/buy`, `let price = item.price * body.quantity;` và `user.game.money.set(user.game.money.get() - price as i64);` nhưng trước đó ta sẽ cần upgrade `More Commodities` để có thể mua được nhiều item hơn (để khi nhân vào thì số to -> dễ overflow)

Upgrade `More Commodities`:

![more_items](https://user-images.githubusercontent.com/77546253/193606786-c7b5ff63-e364-4602-87a6-22e506561b1a.png)

Sau một hồi mua rồi lại bán bán rồi mua 😅 thì money của mình cũng dư để mua flag 

![burp_req_2](https://user-images.githubusercontent.com/77546253/193606813-cf8ae174-0aec-4385-b254-5997acfdc5eb.png)

![temp2](https://user-images.githubusercontent.com/77546253/193606836-291b944a-43da-45e5-9400-a2f1aa11dfc5.png)

và flag:

![flag](https://user-images.githubusercontent.com/77546253/193606862-4e1a8184-85fd-40d9-94f6-16311dd306bf.png)
