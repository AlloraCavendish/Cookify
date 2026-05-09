import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: './tests',

  timeout: 30000,

  use: {
    baseURL: 'http://cookify.test',
    headless: false,
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
});