import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://cookify.test/');
  await page.getByRole('textbox', { name: 'Enter ingredients (comma' }).click();
  await page.getByRole('textbox', { name: 'Enter ingredients (comma' }).fill('rice');
  await page.getByRole('textbox', { name: 'Enter ingredients (comma' }).press('Enter');
  await page.getByRole('button', { name: 'Search' }).click();
  await page.getByRole('link', { name: 'Login' }).click();
  await page.getByRole('textbox', { name: 'Email' }).click();
  await page.getByRole('textbox', { name: 'Email' }).fill('admin@gmail.com');
  await page.getByRole('textbox', { name: 'Password' }).click();
  await page.getByRole('textbox', { name: 'Password' }).fill('123456');
  await page.getByRole('button', { name: 'Login' }).click();
  await page.getByRole('link', { name: 'Manage Recipes' }).click();
  await page.getByRole('link', { name: '⬅ Back to Dashboard' }).click();
  await page.getByRole('button', { name: 'Logout' }).click();
});