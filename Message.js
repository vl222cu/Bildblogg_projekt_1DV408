"use strict";

$(document).ready(function () {
            var $statusText = $(".msgstatus");
            var $loginText = $(".loginmsg");
            if ($statusText.length || $loginText.length) {
                setTimeout(function () {
                    $statusText.fadeOut();
                    $loginText.fadeOut();
                }, 5000);
            }
        });