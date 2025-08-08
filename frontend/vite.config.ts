import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: {
    host: true,
    allowedHosts: [
      'a465fe239c88.ngrok-free.app',
      '.ngrok-free.app'
    ],
    watch: {
      usePolling: true
    }
  },
  define: {
    __TELEGRAM_WEBAPP__: JSON.stringify(true)
  }
});