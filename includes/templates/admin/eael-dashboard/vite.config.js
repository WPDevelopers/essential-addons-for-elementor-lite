import {defineConfig} from 'vite'
import react from '@vitejs/plugin-react-swc'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [react()],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: 'assets/ea-dashboard.js',
                assetFileNames: 'assets/ea-dashboard.css',
            }
        }
    }
})
