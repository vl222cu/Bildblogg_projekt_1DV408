"use strict";

$(document).ready(function () {
            var $statusText = $(".msgstatus");
            if ($statusText.length) {
                setTimeout(function () {
                    $statusText.fadeOut();
                }, 5000);
            }
        });