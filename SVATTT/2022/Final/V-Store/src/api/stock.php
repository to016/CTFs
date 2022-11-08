<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    echo "Available in stock";
} else {
    echo "Id invalid";
}