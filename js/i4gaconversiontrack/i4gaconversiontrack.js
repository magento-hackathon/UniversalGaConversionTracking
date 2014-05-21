
document.observe("dom:loaded", function() {

    if ( $('onestepcheckout-form') ) { /* Onestepcheckout */
        i4gaconversiontrackInsertFields($('onestepcheckout-form'));
    } else if ( $('gcheckout-onepage-form') ) { /* Gomagecheckout */
        i4gaconversiontrackInsertFields($('gcheckout-onepage-form'));
    }

    function i4gaconversiontrackInsertFields(form) {
        var fields = [];
        fields.push( new Element('input', { type: 'hidden', name: 'i4gaconversiontrack_user_agent', id: 'i4gaconversiontrack_user_agent', value: navigator.userAgent }) );
        fields.push( new Element('input', { type: 'hidden', name: 'i4gaconversiontrack_screen_resolution', id: 'i4gaconversiontrack_screen_resolution', value: screen.width + 'x' + screen.height }) );
        fields.push( new Element('input', { type: 'hidden', name: 'i4gaconversiontrack_screen_color_depth', id: 'i4gaconversiontrack_screen_resolution', value: screen.colorDepth + '-bit' }) );
        fields.push( new Element('input', { type: 'hidden', name: 'i4gaconversiontrack_browser_language', id: 'i4gaconversiontrack_browser_language', value: (navigator.language) ? navigator.language.toLowerCase() : navigator.userLanguage.toLowerCase() }) );
        fields.push( new Element('input', { type: 'hidden', name: 'i4gaconversiontrack_browser_java_enabled', id: 'i4gaconversiontrack_browser_java_enabled', value: navigator.javaEnabled() ? 1 : 0 }) );
        for ( i in fields) {
            fields[i].removeAttribute('disabled');
            form.insert(fields[i]);
        }
    }
});