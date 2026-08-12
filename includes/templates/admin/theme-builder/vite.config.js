import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  build: {
    // Single self-contained bundle: the file is enqueued directly by
    // Admin::enqueue_assets(), there is no manifest consumer on the PHP side.
    cssCodeSplit: false,
    rollupOptions: {
      output: {
        // WordPress enqueues this as a classic script, not as a module. In an ES
        // module every top level `var` stays module scoped; in a classic script
        // the same declarations become globals — and React's minified internals
        // declare one called `$e`, which is the name Elementor's editor gives its
        // command API. Loading the module build inside the editor replaced
        // `window.$e` with a React internal string and broke the whole editor.
        // The IIFE build keeps everything inside one function scope.
        format: "iife",
        inlineDynamicImports: true,
        entryFileNames: "theme-builder.min.js",
        assetFileNames: "theme-builder.min.css",
      },
    },
  },
});
