<?php

class Flag implements Serializable{
    public function serialize() {
        return "foobar";
    }
    public function unserialize($str) {
        return "foobar";
    }
}


$flag = new Flag();

echo base64_encode(serialize($flag));
