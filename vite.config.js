import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],

  server: {
        host: '0.0.0.0', // Terima koneksi dari mana saja di dalam jaringan Docker
        port: 5173,
        hmr: {
            host: 'localhost', // Browser akan terhubung ke sini untuk Hot Reload
        },
    },
})
