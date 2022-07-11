from xml.etree import ElementTree, ElementInclude

xpath = "foo"

xml = """
<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
<foo xmlns:xi="http://www.w3.org/2001/XInclude">
<xi:include parse="text" href="bla.txt"/>
</foo>
</root>
"""

if len(xpath) != 0 and len(xml) != 0 and "&" not in xml:
    res = ''
    root = ElementTree.fromstring(xml.strip())
    ElementInclude.include(root)
    for elem in root.findall(xpath):
        if elem.text != "":
            res += elem.text + ", "
    print(res)