"use strict";

// This is the modified Google Translate Script

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        layout: google.translate.TranslateElement.InlineLayout.AUTO,
        multilanguagePage: true
    }, 'google_translate_element');
}


// Load Google Translate API
function loadGoogleTranslateAPI() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.charset = "UTF-8";
    script.src =
        "https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.head.appendChild(script);
}

// Check if the Google Translate API is already loaded
if (typeof google !== "undefined" && typeof google.translate !== "undefined") {
    // Google Translate API is already loaded, initialize it
    googleTranslateElementInit();
} else {
    // Google Translate API is not loaded, load it dynamically
    loadGoogleTranslateAPI();
}
