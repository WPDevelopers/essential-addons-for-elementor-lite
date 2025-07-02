/**
 * Essential Addons Code Snippet Frontend JavaScript
 * Handles syntax highlighting and copy-to-clipboard functionality
 */

var CodeSnippet = function ($scope, $) {
   // Global namespace
   window.EaelCodeSnippet = window.EaelCodeSnippet || {};

   // Configuration - Using local files for security
   const config = {
      highlightJsUrl: null,
      highlightJsCssLight: null,
      highlightJsCssDark: null,
      copySuccessTimeout: 2000,
      retryAttempts: 3,
      retryDelay: 1000,
   };

   // Language mapping for Highlight.js compatibility
   const languageMap = {
      html: "xml",
      jsx: "javascript",
      vue: "javascript",
      ts: "typescript",
      py: "python",
      rd: "ruby",
      yml: "yaml",
      cpp: "cpp",
      cs: "csharp",
      rs: "rust",
      kt: "kotlin",
      md: "markdown",
      sh: "bash",
      ps1: "powershell",
      dockerfile: "dockerfile",
   };

   /**
    * Get the correct language name for Highlight.js
    */
   function getHighlightLanguage(language) {
      return languageMap[language] || language;
   }

   // State management
   let highlightJsLoaded = false;
   let highlightJsLoading = false;

   // Utility functions removed - using WordPress dependency system instead

   /**
    * Load Highlight.js library and CSS
    * Files are loaded via WordPress dependency system in config.php
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

      // Check if Highlight.js is already loaded via WordPress dependency system
      if (window.hljs) {
         highlightJsLoaded = true;
         highlightJsLoading = false;
         if (callback) callback(true);
         return;
      }

      // If not loaded, wait a bit and check again (for async loading)
      let attempts = 0;
      const maxAttempts = 10; // Wait up to 1 second
      const checkInterval = setInterval(function () {
         attempts++;
         if (window.hljs) {
            highlightJsLoaded = true;
            highlightJsLoading = false;
            clearInterval(checkInterval);
            if (callback) callback(true);
         } else if (attempts >= maxAttempts) {
            highlightJsLoading = false;
            clearInterval(checkInterval);
            if (window.console && window.console.warn) {
               console.warn(
                  "Essential Addons: Syntax highlighting unavailable"
               );
            }
            if (callback) callback(false);
         }
      }, 100);
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
            if (window.console && window.console.warn) {
               console.warn("Essential Addons: Syntax highlighting error");
            }
         }
      });
   }

   /**
    * Apply syntax highlighting to a specific code block with language
    */
   function applySyntaxHighlightingToBlock(codeBlock, language) {
      if (!highlightJsLoaded || !window.hljs) {
         return false;
      }

      try {
         // Get the correct language for Highlight.js
         const highlightLanguage = getHighlightLanguage(language);

         // Remove existing highlighting classes
         codeBlock.className = codeBlock.className
            .replace(/\bhljs\b/g, "")
            .replace(/\blanguage-\w+/g, "")
            .trim();

         // Add the new language class
         if (highlightLanguage) {
            codeBlock.className += " language-" + highlightLanguage;
         }

         // Clear any existing highlighted content
         codeBlock.removeAttribute("data-highlighted");

         // Apply highlighting
         window.hljs.highlightElement(codeBlock);

         return true;
      } catch (error) {
         if (window.console && window.console.warn) {
            console.warn("Essential Addons: Syntax highlighting error");
         }
         return false;
      }
   }

   /**
    * Update language and re-highlight a specific snippet
    */
   function updateSnippetLanguage(snippet, newLanguage) {
      if (!snippet) {
         return false;
      }

      const codeBlock = snippet.querySelector(".eael-code-snippet-code");
      if (!codeBlock) {
         return false;
      }

      // Update the data attribute
      snippet.dataset.language = newLanguage;

      // Apply new syntax highlighting
      return applySyntaxHighlightingToBlock(codeBlock, newLanguage);
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
            .catch(function () {
               if (window.console && window.console.warn) {
                  console.warn("Essential Addons: Copy operation failed");
               }
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
         if (window.console && window.console.warn) {
            console.warn("Essential Addons: Copy operation failed");
         }
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

      // Try multiple selectors for the code element
      let codeElement = snippet.querySelector(".eael-code-snippet-code code");
      if (!codeElement) {
         codeElement = snippet.querySelector(".eael-code-snippet-code");
      }
      if (!codeElement) {
         codeElement = snippet.querySelector("code");
      }
      if (!codeElement) {
         codeElement = snippet.querySelector("pre");
      }

      if (!copyButton) {
         return;
      }

      if (!codeElement) {
         if (window.console && window.console.warn) {
            console.warn(
               "Essential Addons: Code element not found for copy button"
            );
         }
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
            if (window.console && window.console.warn) {
               console.warn("Essential Addons: No content to copy");
            }
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
               if (window.console && window.console.error) {
                  console.error("Essential Addons: Copy operation failed");
               }
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

      // Initialize copy buttons - always initialize regardless of data attribute
      snippets.forEach(function (snippet) {
         // Check if copy button exists in the snippet
         const copyButton = snippet.querySelector(
            ".eael-code-snippet-copy-button"
         );
         if (copyButton) {
            initCopyButton(snippet);
         }
      });
   }

   /**
    * Re-initialize code snippets (useful for dynamic content)
    */
   function reinitialize() {
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
      applySyntaxHighlightingToBlock: applySyntaxHighlightingToBlock,
      updateSnippetLanguage: updateSnippetLanguage,
      getHighlightLanguage: getHighlightLanguage,
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
}

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-code-snippet.default",
      CodeSnippet
   );
});
