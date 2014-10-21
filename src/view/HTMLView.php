<?php

namespace view;

class HTMLView {

    public function errorHTML($body) {

        if($body === NULL) {

            throw new \Exception("HTMLView::echoHTLM does not allow body to be null");
        }

        echo "
                <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
                <html xmlns='http://www.w3.org/1999/xhtml'>
                <head>
                <title>Project photoblog vl222cu</title>
                <link rel='stylesheet' href='css/mainstyle.css' media='screen'>
                <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                </head>
                <body>
                    $body
                </body>
                </html>
        ";
    }
} 