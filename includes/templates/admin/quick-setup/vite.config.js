import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  build: {
    rollupOptions: {
      output: {
        entryFileNames: "quick-setup.min.js",
        assetFileNames: "quick-setup.min.css",
      },
    },
  },
});
