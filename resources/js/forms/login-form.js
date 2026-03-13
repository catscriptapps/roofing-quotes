// /resources/js/forms/login-form.js

/**
 * Login form HTML for modal
 */
export const loginFormHTML = `
<form id="login-form" class="space-y-4" novalidate>
  <div class="flex flex-col">
    <label for="login-email" class="mb-1 text-gray-700 dark:text-gray-300 font-sans text-sm font-semibold">Email</label>
    <input type="email" id="login-email" name="email" placeholder="you@example.com"
      class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100" required>
  </div>

  <div class="flex flex-col">
    <div class="flex justify-between items-center mb-1">
      <label for="login-password" class="text-gray-700 dark:text-gray-300 font-sans text-sm font-semibold">Password</label>
      <a href="#" id="forgot-password-link" class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">
        Forgot password?
      </a>
    </div>
    <input type="password" id="login-password" name="password" placeholder="••••••••"
      class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100" required>
  </div>

  <div id="login-api-message" class="text-sm space-y-1"></div>

  <div class="flex justify-end pt-2">
    <button type="submit"
      class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 transition-all active:scale-95">
      Sign In
    </button>
  </div>
</form>
`;