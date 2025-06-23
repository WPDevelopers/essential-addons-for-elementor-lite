/**
 * Essential Addons Code Snippet Frontend JavaScript
 * Handles syntax highlighting and copy-to-clipboard functionality
 */

(function () {
   "use strict";

   // Global namespace
   window.EaelCodeSnippet = window.EaelCodeSnippet || {};

   // Configuration
   const config = {
      highlightJsUrl:
         "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js",
      highlightJsCssLight:
         "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css",
      highlightJsCssDark:
         "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css",
      copySuccessTimeout: 2000,
      retryAttempts: 3,
      retryDelay: 1000,
   };

   // State management
   let highlightJsLoaded = false;
   let highlightJsLoading = false;
   let copyButtonsInitialized = false;

   /**
    * Utility function to load external scripts
    */
   function loadScript(src, callback) {
      const script = document.createElement("script");
      script.src = src;
      script.onload = callback;
      script.onerror = function () {
         console.warn(
            "Essential Addons Code Snippet: Failed to load script:",
            src
         );
         if (callback) callback(false);
      };
      document.head.appendChild(script);
   }

   /**
    * Utility function to load external stylesheets
    */
   function loadStylesheet(href) {
      // Check if stylesheet is already loaded
      const existingLink = document.querySelector(`link[href="${href}"]`);
      if (existingLink) return;

      const link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = href;
      link.onerror = function () {
         console.warn(
            "Essential Addons Code Snippet: Failed to load stylesheet:",
            href
         );
      };
      document.head.appendChild(link);
   }

   /**
    * Load Highlight.js library and CSS
    */
   function loadHighlightJs(callback) {
      if (highlightJsLoaded) {
         if (callback) callback(true);
         return;
      }

      if (highlightJsLoading) {
         // Wait for current loading to complete
         const checkLoaded = setInterval(function () {
            if (highlightJsLoaded || !highlightJsLoading) {
               clearInterval(checkLoaded);
               if (callback) callback(highlightJsLoaded);
            }
         }, 100);
         return;
      }

      highlightJsLoading = true;

      // Load CSS based on theme
      const darkThemeSnippets = document.querySelectorAll(
         ".eael-code-snippet-wrapper.theme-dark"
      );
      const lightThemeSnippets = document.querySelectorAll(
         ".eael-code-snippet-wrapper.theme-light"
      );

      if (darkThemeSnippets.length > 0) {
         loadStylesheet(config.highlightJsCssDark);
      }
      if (lightThemeSnippets.length > 0) {
         loadStylesheet(config.highlightJsCssLight);
      }

      // Load Highlight.js
      loadScript(config.highlightJsUrl, function (success) {
         highlightJsLoading = false;
         if (success && window.hljs) {
            highlightJsLoaded = true;
            console.log(
               "Essential Addons Code Snippet: Highlight.js loaded successfully"
            );
         } else {
            console.warn(
               "Essential Addons Code Snippet: Failed to load Highlight.js"
            );
         }
         if (callback) callback(highlightJsLoaded);
      });
   }

   /**
    * Apply syntax highlighting to code blocks
    */
   function applySyntaxHighlighting() {
      if (!highlightJsLoaded || !window.hljs) {
         return;
      }

      const codeBlocks = document.querySelectorAll(
         ".eael-code-snippet-code:not(.hljs)"
      );

      codeBlocks.forEach(function (block) {
         try {
            window.hljs.highlightElement(block);
         } catch (error) {
            console.warn(
               "Essential Addons Code Snippet: Error highlighting code block:",
               error
            );
         }
      });
   }

   /**
    * Copy text to clipboard using modern Clipboard API with fallback
    */
   function copyToClipboard(text, callback) {
      // Modern Clipboard API
      if (navigator.clipboard && window.isSecureContext) {
         navigator.clipboard
            .writeText(text)
            .then(function () {
               if (callback) callback(true);
            })
            .catch(function (error) {
               console.warn(
                  "Essential Addons Code Snippet: Clipboard API failed:",
                  error
               );
               fallbackCopyToClipboard(text, callback);
            });
      } else {
         fallbackCopyToClipboard(text, callback);
      }
   }

   /**
    * Fallback copy method for older browsers
    */
   function fallbackCopyToClipboard(text, callback) {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      textArea.style.position = "fixed";
      textArea.style.left = "-999999px";
      textArea.style.top = "-999999px";
      document.body.appendChild(textArea);

      try {
         textArea.focus();
         textArea.select();
         const successful = document.execCommand("copy");
         if (callback) callback(successful);
      } catch (error) {
         console.warn(
            "Essential Addons Code Snippet: Fallback copy failed:",
            error
         );
         if (callback) callback(false);
      } finally {
         document.body.removeChild(textArea);
      }
   }

   /**
    * Show copy success feedback
    */
   function showCopySuccess(button) {
      const container = button.closest(".eael-code-snippet-copy-container");
      const tooltip = container
         ? container.querySelector(".eael-code-snippet-tooltip")
         : null;
      const svg = button.querySelector("svg");

      // Change icon to tick with animation
      if (svg) {
         const originalSVG = svg.outerHTML;

         // Add transition class
         svg.style.transition = "all 0.3s ease";
         svg.style.transform = "scale(0.8)";

         setTimeout(function () {
            // Replace with tick icon
            svg.innerHTML =
               '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/>';
            svg.setAttribute("viewBox", "0 0 24 24");
            svg.style.transform = "scale(1)";
            //svg.style.color = '#22c55e'; // Green color for success
         }, 150);

         // Restore original icon after 1 second
         setTimeout(function () {
            svg.style.transform = "scale(0.8)";
            setTimeout(function () {
               svg.outerHTML = originalSVG;
               // Re-get the svg element after replacement
               const newSvg = button.querySelector("svg");
               if (newSvg) {
                  newSvg.style.transition = "all 0.3s ease";
                  newSvg.style.transform = "scale(1)";
               }
            }, 150);
         }, 1000);
      }

      // Handle tooltip
      if (tooltip) {
         const originalText = tooltip.textContent;
         tooltip.textContent = "Copied!";

         // Reposition tooltip after text change
         if (tooltip.classList.contains("show")) {
            positionTooltip(button, tooltip);
         }

         setTimeout(function () {
            tooltip.textContent = originalText;
            // Reposition tooltip back to original size
            if (tooltip.classList.contains("show")) {
               positionTooltip(button, tooltip);
            }
         }, config.copySuccessTimeout);
      }
   }

   /**
    * Show copy error feedback
    */
   function showCopyError(button) {
      const container = button.closest(".eael-code-snippet-copy-container");
      const tooltip = container
         ? container.querySelector(".eael-code-snippet-tooltip")
         : null;

      if (tooltip) {
         const originalText = tooltip.textContent;
         tooltip.textContent = "Copy Failed";

         // Reposition tooltip after text change
         if (tooltip.classList.contains("show")) {
            positionTooltip(button, tooltip);
         }

         setTimeout(function () {
            tooltip.textContent = originalText;
            // Reposition tooltip back to original size
            if (tooltip.classList.contains("show")) {
               positionTooltip(button, tooltip);
            }
         }, 2000);
      }
   }

   /**
    * Position tooltip relative to button
    */
   function positionTooltip(button, tooltip) {
      const buttonRect = button.getBoundingClientRect();
      const tooltipRect = tooltip.getBoundingClientRect();

      // Position above the button, centered
      const left =
         buttonRect.left + buttonRect.width / 2 - tooltipRect.width / 2;
      const top = buttonRect.top - tooltipRect.height - 8;

      tooltip.style.left = Math.max(8, left) + "px";
      tooltip.style.top = Math.max(8, top) + "px";
   }

   /**
    * Initialize tooltip functionality
    */
   function initTooltip(button) {
      const container = button.closest(".eael-code-snippet-copy-container");
      const tooltip = container
         ? container.querySelector(".eael-code-snippet-tooltip")
         : null;

      if (!tooltip) return;

      // Show tooltip on hover
      button.addEventListener("mouseenter", function () {
         tooltip.classList.add("show");
         positionTooltip(button, tooltip);
      });

      // Hide tooltip on leave
      button.addEventListener("mouseleave", function () {
         tooltip.classList.remove("show");
      });

      // Hide tooltip on focus out
      button.addEventListener("blur", function () {
         tooltip.classList.remove("show");
      });

      // Reposition on scroll/resize
      window.addEventListener("scroll", function () {
         if (tooltip.classList.contains("show")) {
            positionTooltip(button, tooltip);
         }
      });

      window.addEventListener("resize", function () {
         if (tooltip.classList.contains("show")) {
            positionTooltip(button, tooltip);
         }
      });
   }

   /**
    * Initialize copy button for a specific snippet
    */
   function initCopyButton(snippet) {
      const copyButton = snippet.querySelector(
         ".eael-code-snippet-copy-button"
      );
      const codeElement = snippet.querySelector(".eael-code-snippet-code code");

      if (!copyButton || !codeElement) {
         return;
      }

      // Initialize tooltip functionality
      initTooltip(copyButton);

      // Remove any existing event listeners
      copyButton.removeEventListener("click", copyButton._eaelClickHandler);

      // Create new click handler
      copyButton._eaelClickHandler = function (event) {
         event.preventDefault();

         const codeText =
            codeElement.textContent || codeElement.innerText || "";

         if (!codeText.trim()) {
            console.warn(
               "Essential Addons Code Snippet: No Code snippet to copy"
            );
            return;
         }

         copyToClipboard(codeText, function (success) {
            if (success) {
               showCopySuccess(copyButton);

               // Fire custom event
               const customEvent = new CustomEvent("eael-code-copied", {
                  detail: {
                     snippet: snippet,
                     code: codeText,
                     language: snippet.dataset.language,
                  },
               });
               document.dispatchEvent(customEvent);
            } else {
               console.error(
                  "Essential Addons Code Snippet: Failed to copy code to clipboard"
               );
               showCopyError(copyButton);
            }
         });
      };

      // Add event listener
      copyButton.addEventListener("click", copyButton._eaelClickHandler);
   }

   /**
    * Initialize all code snippets on the page
    */
   function initializeCodeSnippets() {
      const snippets = document.querySelectorAll(".eael-code-snippet-wrapper");

      if (snippets.length === 0) {
         return;
      }

      // Load Highlight.js if needed
      loadHighlightJs(function (loaded) {
         if (loaded) {
            applySyntaxHighlighting();
         }
      });

      // Initialize copy buttons
      snippets.forEach(function (snippet) {
         const hasCopyButton = snippet.dataset.copyButton === "true";
         if (hasCopyButton) {
            initCopyButton(snippet);
         }
      });

      copyButtonsInitialized = true;
   }

   /**
    * Re-initialize code snippets (useful for dynamic content)
    */
   function reinitialize() {
      copyButtonsInitialized = false;
      initializeCodeSnippets();
   }

   /**
    * Public API
    */
   window.EaelCodeSnippet = {
      init: initializeCodeSnippets,
      reinit: reinitialize,
      initCopyButton: initCopyButton,
      applySyntaxHighlighting: applySyntaxHighlighting,
      loadHighlightJs: loadHighlightJs,
   };

   // Auto-initialize when DOM is ready
   if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initializeCodeSnippets);
   } else {
      initializeCodeSnippets();
   }

   // Re-initialize on dynamic content changes (for AJAX-loaded content)
   if (window.MutationObserver) {
      const observer = new MutationObserver(function (mutations) {
         let shouldReinit = false;

         mutations.forEach(function (mutation) {
            if (mutation.type === "childList") {
               mutation.addedNodes.forEach(function (node) {
                  if (node.nodeType === 1) {
                     // Element node
                     if (
                        node.classList &&
                        node.classList.contains("eael-code-snippet-wrapper")
                     ) {
                        shouldReinit = true;
                     } else if (
                        node.querySelector &&
                        node.querySelector(".eael-code-snippet-wrapper")
                     ) {
                        shouldReinit = true;
                     }
                  }
               });
            }
         });

         if (shouldReinit) {
            setTimeout(reinitialize, 100); // Small delay to ensure DOM is stable
         }
      });

      observer.observe(document.body, {
         childList: true,
         subtree: true,
      });
   }
})();
