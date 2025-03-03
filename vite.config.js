import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'public/build',  // Génère les fichiers dans public/build/
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: './assets/app.js', // Assure-toi que app.js est bien dans assets/
            },
        },
    },
});
