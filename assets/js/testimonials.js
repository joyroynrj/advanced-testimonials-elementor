/**
 * Advanced Testimonials for Elementor - JavaScript
 */

(function($) {
    'use strict';

    // Debug flag - set to true to see console messages
    const DEBUG = false;
    
    const debug = function(message, data) {
        if (DEBUG) {
            console.log('[ATE Debug] ' + message, data || '');
        }
    };

    // Store initialized carousels by widget ID
    const initializedCarousels = {};

    const initTestimonials = function($scope) {
        debug('initTestimonials called');
        
        const $carousel = $scope.find('.ate-testimonials-carousel');
        
        if (!$carousel.length) {
            debug('No carousel found in scope');
            return;
        }

        debug('Carousel found', $carousel);

        // Get unique widget ID from scope
        const widgetId = $scope.data('id');
        
        // Check if Swiper is loaded
        if (typeof Swiper === 'undefined') {
            console.error('ATE: Swiper is not loaded. Please check if Swiper CDN is accessible.');
            return;
        }

        debug('Swiper is loaded');

        const swiperSettings = $carousel.data('swiper-settings');
        
        if (!swiperSettings || typeof swiperSettings !== 'object') {
            console.error('ATE: Invalid Swiper settings', swiperSettings);
            return;
        }

        debug('Swiper settings:', swiperSettings);

        // Clean up existing instance
        const cleanupSwiper = function() {
            if (initializedCarousels[widgetId]) {
                try {
                    initializedCarousels[widgetId].destroy(true, true);
                    delete initializedCarousels[widgetId];
                    debug('Existing Swiper instance destroyed for widget:', widgetId);
                } catch (e) {
                    debug('Error destroying existing instance', e);
                }
            }
            
            // Also check for swiper instance on element
            if ($carousel[0].swiper) {
                try {
                    $carousel[0].swiper.destroy(true, true);
                    delete $carousel[0].swiper;
                    debug('Swiper instance on element destroyed');
                } catch (e) {
                    debug('Error destroying element swiper', e);
                }
            }

            // Remove inline styles that may interfere
            $carousel.find('.swiper-slide').removeAttr('style');
            $carousel.find('.swiper-wrapper').removeAttr('style');
        };

        // Cleanup first
        cleanupSwiper();

        // Initialize with delay to ensure DOM is ready
        setTimeout(function() {
            try {
                // Double-check Swiper is still available
                if (typeof Swiper === 'undefined') {
                    console.error('ATE: Swiper became undefined');
                    return;
                }

                debug('Initializing Swiper...');

                // Initialize Swiper
                const swiper = new Swiper($carousel[0], swiperSettings);

                // Store instance
                $carousel[0].swiper = swiper;
                initializedCarousels[widgetId] = swiper;

                debug('Swiper initialized successfully', swiper);

                // Force update after initialization
                setTimeout(function() {
                    if (swiper && !swiper.destroyed) {
                        swiper.update();
                        debug('Swiper updated after initialization');
                    }
                }, 100);

                // Handle responsive recalculation
                let resizeTimer;
                $(window).off('resize.ate-' + widgetId).on('resize.ate-' + widgetId, function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        if (swiper && !swiper.destroyed) {
                            swiper.update();
                            debug('Swiper updated on resize');
                        }
                    }, 250);
                });

            } catch (error) {
                console.error('ATE: Swiper initialization error:', error);
            }
        }, 150);
    };

    // Check if we're in Elementor editor
    const isEditMode = function() {
        return typeof elementorFrontend !== 'undefined' && 
               elementorFrontend.isEditMode && 
               elementorFrontend.isEditMode();
    };

    // Method 1: Standard Elementor frontend initialization
    const initOnElementorReady = function() {
        debug('Trying to init on Elementor ready');
        
        if (typeof elementorFrontend !== 'undefined' && 
            elementorFrontend.hooks && 
            elementorFrontend.hooks.addAction) {
            
            debug('Elementor frontend available, adding action hook');
            
            try {
                elementorFrontend.hooks.addAction(
                    'frontend/element_ready/ate-testimonials.default', 
                    initTestimonials
                );
                debug('Hook registered successfully');
            } catch (error) {
                console.error('ATE: Failed to register hook:', error);
            }
        } else {
            debug('Elementor frontend not available yet');
        }
    };

    // Method 2: Manual initialization for all widgets on page
    const initAllWidgets = function() {
        debug('Manually initializing all widgets');
        
        const $widgets = $('.elementor-widget-ate-testimonials');
        debug('Found widgets:', $widgets.length);
        
        $widgets.each(function(index) {
            debug('Initializing widget #' + index);
            initTestimonials($(this));
        });
    };

    // Primary: Wait for Elementor to be ready
    $(window).on('elementor/frontend/init', function() {
        debug('elementor/frontend/init event fired');
        initOnElementorReady();
        
        // Special handling for Elementor editor
        if (isEditMode()) {
            debug('In Elementor editor mode');
            
            // Listen to Elementor preview loaded event
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
                if ($scope.hasClass('elementor-widget-ate-testimonials')) {
                    debug('Widget rendered in editor, re-initializing');
                    
                    // Small delay to ensure DOM is ready
                    setTimeout(function() {
                        initTestimonials($scope);
                    }, 200);
                }
            });
        }
    });

    // Fallback 1: If Elementor is already loaded
    if (typeof elementorFrontend !== 'undefined') {
        debug('Elementor already loaded on script execution');
        $(document).ready(function() {
            debug('Document ready, initializing Elementor hooks');
            setTimeout(initOnElementorReady, 100);
        });
    }

    // Fallback 2: On window load (catches everything)
    $(window).on('load', function() {
        debug('Window load event fired');
        
        setTimeout(function() {
            debug('Checking for uninitialized carousels');
            
            // Check if any carousels weren't initialized
            $('.ate-testimonials-carousel').each(function() {
                if (!this.swiper) {
                    debug('Found uninitialized carousel, initializing now');
                    initTestimonials($(this).closest('.elementor-widget-ate-testimonials'));
                }
            });
        }, 500);
    });

    // Fallback 3: For AJAX loaded content
    $(document).on('DOMContentLoaded', function() {
        debug('DOMContentLoaded event fired');
        setTimeout(function() {
            debug('Running final initialization check');
            initAllWidgets();
        }, 1000);
    });

    // Log initial state
    debug('Script loaded. Initial state:', {
        jQuery: typeof $ !== 'undefined',
        Swiper: typeof Swiper !== 'undefined',
        elementorFrontend: typeof elementorFrontend !== 'undefined',
        isEditMode: isEditMode()
    });

})(jQuery);