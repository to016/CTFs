<?php

class PDFGenerator {
    // a generic PDF Generator
    public $fileName;
    public $callback;
    // callback is a function that triggers an action when a PDF is generated
    // e.g: send an email, log the PDF file name to history, sync with Dropbox
    function __destruct() {
        call_user_func($this->callback, $this->fileName);
    }
}

?>