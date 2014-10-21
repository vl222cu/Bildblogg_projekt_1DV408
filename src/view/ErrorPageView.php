<?php

namespace view;

class ErrorPageView {

    public function errorHTML() {

        $html = "
            <div id='maincontainer'>
                <div id='content'>
                    <h1>Vivis bildblogg</h1>
                    <div id='contentwrapper'>
                    <p><a href='?return'>Tillbaka</a></p>
                    <h2>Något har gått riktigt fel!</h2>
                    <p>Det är någon del i applikationen som strular.</p>
                    </div>
                </div>
            </div>";

        return $html;
    }
} 