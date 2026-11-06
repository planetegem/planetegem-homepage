import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    publicDir: false,
    build: {
        // Output files into a folder named 'dist'
        outDir: 'public/dist',
        emptyOutDir: true,
        rollupOptions: {
            // Point this to your main JavaScript entry file that imports your web component(s)
            input: resolve(__dirname, 'public/js/main.js'),
            output: {
                // Name the final output bundle predictably
                entryFileNames: 'main.js',
                assetFileNames: (assetInfo) => {
                    const fileName = assetInfo.names?.[0] || '';
                    if (fileName.endsWith('.css')) {
                        return 'main.css';
                    }
                    return '[name].[ext]';
                },
                chunkFileNames: '[name].js',
            },
        },
    },
});