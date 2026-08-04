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
        entryFileNames: "theme-builder.min.js",
        assetFileNames: "theme-builder.min.css",
      },
    },
  },
});
