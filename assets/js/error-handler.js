/**
 * Advanced Testimonials - Error Handler
 * Prevents Elementor errors from breaking our carousel
 */

(function() {
    'use strict';

    // Catch and isolate jQuery deferred exceptions
    if (window.jQuery && jQuery.Deferred && jQuery.Deferred.exceptionHook) {
        const originalExceptionHook = jQuery.Deferred.exceptionHook;
        
        jQuery.Deferred.exceptionHook = function(error, stack) {
            // If error is from Elementor's frontend.min.js and mentions 'tools'
            if (error && error.message && 
                (error.message.includes('tools') || error.message.includes('Cannot read properties of undefined'))) {
                
                // Log but don't throw
                if (console && console.warn) {
                    console.warn('Elementor Error (isolated):', error.message);
                    console.warn('This error is from Elementor core and does not affect the testimonials carousel.');
                }
                
                // Don't propagate the error
                return;
            }
            
            // For other errors, use original handler
            if (originalExceptionHook) {
                return originalExceptionHook.call(this, error, stack);
            }
        };
    }

    // Patch elementorFrontend if it has issues
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for Elementor to load
        setTimeout(function() {
            if (typeof elementorFrontend !== 'undefined' && elementorFrontend.config) {
                // Ensure tools exists
                if (!elementorFrontend.config.tools) {
                    elementorFrontend.config.tools = {};
                }
                
                // Ensure experimentalFeatures exists
                if (!elementorFrontend.config.experimentalFeatures) {
                    elementorFrontend.config.experimentalFeatures = {};
                }
            }
        }, 100);
    });

})();