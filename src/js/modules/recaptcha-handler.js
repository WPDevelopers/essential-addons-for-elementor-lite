/**
 * Unified reCAPTCHA Handler for Essential Addons Login/Register Widget
 * 
 * This module consolidates all reCAPTCHA functionality to eliminate code duplication
 * and provide consistent behavior across all forms.
 * 
 * @since 6.2.1
 */

(function($) {
    'use strict';

    /**
     * reCAPTCHA Handler Class
     */
    class RecaptchaHandler {
        constructor(scope) {
            this.scope = scope;
            this.wrapper = scope.find('.eael-login-registration-wrapper');
            this.widgetId = this.wrapper.data('widget-id');
            this.isEditMode = typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode();
            
            // Configuration
            this.config = this.extractConfig();
            
            // Form elements
            this.forms = this.detectForms();
            
            // reCAPTCHA availability
            this.isRecaptchaAvailable = typeof grecaptcha !== 'undefined' && grecaptcha !== null;
            
            // Initialize
            this.init();
        }

        /**
         * Extract reCAPTCHA configuration from DOM
         */
        extractConfig() {
            return {
                siteKey: this.wrapper.data('recaptcha-sitekey') || '',
                siteKeyV3: this.wrapper.data('recaptcha-sitekey-v3') || '',
                loginVersion: this.wrapper.data('login-recaptcha-version') || 'v2',
                registerVersion: this.wrapper.data('register-recaptcha-version') || 'v2',
                lostpasswordVersion: this.wrapper.data('lostpassword-recaptcha-version') || 'v2',
                isAjaxEnabled: this.wrapper.data('is-ajax') === 'yes'
            };
        }

        /**
         * Detect available forms and their configurations
         */
        detectForms() {
            const forms = {};
            
            // Login form
            const loginWrapper = this.scope.find('#eael-login-form-wrapper');
            if (loginWrapper.length) {
                forms.login = {
                    wrapper: loginWrapper,
                    node: document.getElementById(`login-recaptcha-node-${this.widgetId}`),
                    theme: loginWrapper.data('recaptcha-theme') || 'light',
                    size: loginWrapper.data('recaptcha-size') || 'normal',
                    version: this.config.loginVersion
                };
            }

            // Register form
            const registerWrapper = this.scope.find('#eael-register-form-wrapper');
            if (registerWrapper.length) {
                forms.register = {
                    wrapper: registerWrapper,
                    node: document.getElementById(`register-recaptcha-node-${this.widgetId}`),
                    theme: registerWrapper.data('recaptcha-theme') || 'light',
                    size: registerWrapper.data('recaptcha-size') || 'normal',
                    version: this.config.registerVersion
                };
            }

            // Lost password form
            const lostpasswordWrapper = this.scope.find('#eael-lostpassword-form-wrapper');
            if (lostpasswordWrapper.length) {
                forms.lostpassword = {
                    wrapper: lostpasswordWrapper,
                    node: document.getElementById(`lostpassword-recaptcha-node-${this.widgetId}`),
                    theme: lostpasswordWrapper.data('recaptcha-theme') || 'light',
                    size: lostpasswordWrapper.data('recaptcha-size') || 'normal',
                    version: this.config.lostpasswordVersion
                };
            }

            return forms;
        }

        /**
         * Initialize reCAPTCHA handling
         */
        init() {
            if (!this.isRecaptchaAvailable) {
                return;
            }

            // Handle v3 initialization for non-Pro AJAX
            if (!this.config.isAjaxEnabled) {
                this.initializeV3();
            }

            // Initialize v2 reCAPTCHA
            this.initializeV2();
        }

        /**
         * Initialize reCAPTCHA v3 for all applicable forms
         */
        initializeV3() {
            const hasV3Form = Object.values(this.forms).some(form => form.version === 'v3');
            
            if (!hasV3Form || !this.config.siteKeyV3) {
                return;
            }

            grecaptcha.ready(() => {
                grecaptcha.execute(this.config.siteKeyV3, {
                    action: 'eael_login_register_form'
                }).then(token => {
                    this.updateV3Token(token);
                });
            });
        }

        /**
         * Update v3 token in all forms
         */
        updateV3Token(token) {
            const forms = this.scope.find('form');
            
            forms.each((index, form) => {
                const $form = $(form);
                const existingInput = $form.find('input[name="g-recaptcha-response"]');
                
                if (existingInput.length === 0) {
                    $form.append(`<input type="hidden" name="g-recaptcha-response" value="${token}">`);
                } else {
                    existingInput.val(token);
                }
            });
        }

        /**
         * Initialize reCAPTCHA v2 for applicable forms
         */
        initializeV2() {
            if (this.isEditMode) {
                // In editor mode, initialize immediately
                this.renderV2Recaptchas();
            } else {
                // In frontend, wait for page load
                this.waitForPageLoad(() => {
                    this.renderV2Recaptchas();
                });
            }
        }

        /**
         * Wait for page load before initializing
         */
        waitForPageLoad(callback) {
            const navData = window.performance.getEntriesByType("navigation");
            
            if (navData.length > 0 && navData[0].loadEventEnd > 0) {
                // Page already loaded
                callback();
            } else {
                // Wait for load event
                $(window).on('load', callback);
            }
        }

        /**
         * Render v2 reCAPTCHA for all applicable forms
         */
        renderV2Recaptchas() {
            if (typeof grecaptcha.render !== 'function') {
                return;
            }

            Object.entries(this.forms).forEach(([formType, form]) => {
                this.renderFormRecaptcha(formType, form);
            });
        }

        /**
         * Render reCAPTCHA for a specific form
         */
        renderFormRecaptcha(formType, form) {
            // Skip if no node or if any form uses v3 (v3 applies globally)
            if (!form.node || this.hasV3Form()) {
                return;
            }

            // Skip if this specific form uses v3
            if (form.version === 'v3') {
                return;
            }

            try {
                grecaptcha.render(form.node, {
                    'sitekey': this.config.siteKey,
                    'theme': form.theme,
                    'size': form.size,
                });
            } catch (error) {
                // Handle duplicate instance error gracefully
                console.warn(`reCAPTCHA render error for ${formType} form:`, error.message);
            }
        }

        /**
         * Check if any form uses v3
         */
        hasV3Form() {
            return Object.values(this.forms).some(form => form.version === 'v3');
        }

        /**
         * Get form configuration for debugging
         */
        getDebugInfo() {
            return {
                config: this.config,
                forms: Object.keys(this.forms),
                isRecaptchaAvailable: this.isRecaptchaAvailable,
                hasV3Form: this.hasV3Form()
            };
        }
    }

    /**
     * Factory function to create RecaptchaHandler instances
     */
    window.EAELRecaptchaHandler = {
        create: function(scope) {
            return new RecaptchaHandler(scope);
        },
        
        // Utility methods for backward compatibility
        isRecaptchaAvailable: function() {
            return typeof grecaptcha !== 'undefined' && grecaptcha !== null;
        },
        
        executeV3: function(siteKey, action, callback) {
            if (!this.isRecaptchaAvailable()) {
                return;
            }
            
            grecaptcha.ready(() => {
                grecaptcha.execute(siteKey, { action: action }).then(callback);
            });
        }
    };

    /**
     * Auto-initialize for Login/Register widgets
     */
    $(document).ready(function() {
        // Initialize for existing widgets on page load
        $('.eael-login-registration-wrapper').each(function() {
            const scope = $(this).closest('.elementor-widget-eael-login-register');
            if (scope.length) {
                window.EAELRecaptchaHandler.create(scope);
            }
        });
    });

})(jQuery);
