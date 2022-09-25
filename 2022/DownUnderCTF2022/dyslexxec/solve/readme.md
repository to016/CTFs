# Dyslexxec

Bài này khá đơn giản, dễ thấy được vuln xxe từ 2 dòng code sau

```
parser = etree.XMLParser(load_dtd=True, resolve_entities=True)
tree = etree.parse(filename, parser=parser)
```

Các bước khai thác sẽ là unzip file `.xlsm` của đề, chèn payload xxe ở text của `.//{http://schemas.microsoft.com/office/spreadsheetml/2010/11/ac}absPath"` trong file `workbook.xml` sau đó zip lại úp lên server là có thể đọc được `/etc/passwd`