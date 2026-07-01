// @ts-check
// GACL Intranet — Microsoft 365 Authentication
// Uses MSAL.js (Microsoft Authentication Library) for Azure AD / Entra ID.
// Docs: https://learn.microsoft.com/en-us/azure/active-directory/develop/msal-js-initializing-client-applications

/* ══════════════════════════════════════════════════════
   CONFIGURATION — fill in after Azure App Registration
   ══════════════════════════════════════════════════════ */
const MSAL_CONFIG = {
  // ← Paste your Application (client) ID from Azure Portal
  clientId: 'YOUR_CLIENT_ID_HERE',

  // ← Paste your Directory (tenant) ID from Azure Portal
  tenantId: 'YOUR_TENANT_ID_HERE',

  // ← The exact URL where this intranet is hosted (must match Azure redirect URI)
  redirectUri: window.location.origin + '/',

  // Scopes: User.Read gets name, email, department, job title
  scopes: ['User.Read'],
};

/* ══════════════════════════════════════════════════════
   DETECTION — is MSAL configured yet?
   ══════════════════════════════════════════════════════ */
const MSAL_CONFIGURED = (
  MSAL_CONFIG.clientId !== 'YOUR_CLIENT_ID_HERE' &&
  MSAL_CONFIG.tenantId !== 'YOUR_TENANT_ID_HERE'
);

/* ══════════════════════════════════════════════════════
   MSAL INSTANCE
   ══════════════════════════════════════════════════════ */
let _msalInstance = null;

/**
 * Returns the MSAL PublicClientApplication, creating it on first call.
 * @returns {import('@azure/msal-browser').PublicClientApplication|null}
 */
function getMsal() {
  if (!MSAL_CONFIGURED || typeof msal === 'undefined') return null;
  if (!_msalInstance) {
    _msalInstance = new msal.PublicClientApplication({
      auth: {
        clientId:    MSAL_CONFIG.clientId,
        authority:   'https://login.microsoftonline.com/' + MSAL_CONFIG.tenantId,
        redirectUri: MSAL_CONFIG.redirectUri,
      },
      cache: {
        cacheLocation:       'localStorage',  // persists across tabs
        storeAuthStateInCookie: false,
      },
    });
  }
  return _msalInstance;
}

/* ══════════════════════════════════════════════════════
   AUTH STATE
   ══════════════════════════════════════════════════════ */

/** @type {{ name:string, email:string, dept:string, jobTitle:string, initials:string }|null} */
let M365_USER = null;

/**
 * Returns the signed-in M365 user, or null if not authenticated.
 * @returns {typeof M365_USER}
 */
function getM365User() { return M365_USER; }

/**
 * Checks whether the user is authenticated via M365.
 * Falls back to true when MSAL is not yet configured (dev mode).
 * @returns {boolean}
 */
function isM365Authenticated() {
  if (!MSAL_CONFIGURED) return true;  // dev mode — skip auth
  const client = getMsal();
  if (!client) return false;
  return client.getAllAccounts().length > 0;
}

/* ══════════════════════════════════════════════════════
   SIGN IN
   ══════════════════════════════════════════════════════ */

/**
 * Triggers the Microsoft sign-in popup.
 * On success, populates M365_USER and updates the UI.
 * @returns {Promise<void>}
 */
async function signInWithMicrosoft() {
  const client = getMsal();
  if (!client) return;

  const btn = document.getElementById('m365-signin-btn');
  if (btn) { btn.disabled = true; btn.textContent = 'Signing in…'; }

  try {
    const response = await client.loginPopup({ scopes: MSAL_CONFIG.scopes });
    await _loadUserProfile(client, response.account);
    _onAuthSuccess();
  } catch (err) {
    console.warn('MSAL sign-in failed:', err);
    if (btn) { btn.disabled = false; btn.textContent = 'Sign in with Microsoft'; }
    const errEl = document.getElementById('m365-error');
    if (errEl) {
      errEl.textContent = err.errorCode === 'user_cancelled'
        ? 'Sign-in cancelled.'
        : 'Sign-in failed. Please try again or contact ICT Help Desk (Ext. 1090).';
      errEl.style.display = 'block';
    }
  }
}

/* ══════════════════════════════════════════════════════
   SIGN OUT
   ══════════════════════════════════════════════════════ */

/**
 * Signs the user out of the intranet and clears the MSAL token cache.
 * @returns {void}
 */
function signOutM365() {
  const client = getMsal();
  M365_USER = null;
  if (client) {
    const account = client.getAllAccounts()[0];
    if (account) {
      client.logoutPopup({ account }).catch(() => {
        // If popup fails, clear cache manually and reload
        localStorage.clear();
        location.reload();
      });
      return;
    }
  }
  localStorage.clear();
  location.reload();
}

/* ══════════════════════════════════════════════════════
   USER PROFILE (Microsoft Graph)
   ══════════════════════════════════════════════════════ */

/**
 * Fetches the user's full profile from Microsoft Graph and stores it.
 * @param {*} client  MSAL instance
 * @param {*} account MSAL account object
 * @returns {Promise<void>}
 */
async function _loadUserProfile(client, account) {
  // Basic info from the token (no extra call needed)
  const name     = account.name || account.username.split('@')[0];
  const email    = account.username;
  const parts    = name.split(' ');
  const initials = parts.map(p => p[0]).join('').slice(0, 2).toUpperCase();

  // Try to get department + job title from Microsoft Graph
  let dept = '', jobTitle = '';
  try {
    const tokenResponse = await client.acquireTokenSilent({
      scopes: MSAL_CONFIG.scopes,
      account,
    });
    const graphResponse = await fetch('https://graph.microsoft.com/v1.0/me?$select=department,jobTitle', {
      headers: { Authorization: 'Bearer ' + tokenResponse.accessToken },
    });
    if (graphResponse.ok) {
      const profile = await graphResponse.json();
      dept     = profile.department || '';
      jobTitle = profile.jobTitle   || '';
    }
  } catch (e) {
    // Graph call failed — use token claims only, not a blocking error
    console.info('Graph profile call failed (non-blocking):', e.message);
  }

  M365_USER = { name, email, dept, jobTitle, initials };
}

/* ══════════════════════════════════════════════════════
   UI UPDATES
   ══════════════════════════════════════════════════════ */

/**
 * Called after successful authentication. Hides the login screen,
 * shows the app, and populates all user-display elements.
 * @returns {void}
 */
function _onAuthSuccess() {
  const loginScreen = document.getElementById('m365-login-screen');
  if (loginScreen) loginScreen.style.display = 'none';

  const u = M365_USER;
  if (!u) return;

  // Topbar: avatar initials + name
  const av    = document.getElementById('av');
  const uname = document.getElementById('uname');
  if (av)    av.textContent    = u.initials;
  if (uname) uname.textContent = u.name + (u.jobTitle ? ' · ' + u.jobTitle : '');

  // User menu
  const menuName = document.querySelector('.user-menu-name');
  const menuRole = document.querySelector('.user-menu-role');
  if (menuName) menuName.textContent = u.name;
  if (menuRole) menuRole.textContent = (u.dept || 'GACL') + (u.jobTitle ? ' · ' + u.jobTitle : '');

  // Home page greeting name
  const staffName = document.getElementById('hp-staff-name');
  if (staffName)  staffName.textContent = u.name.split(' ')[0];

  // Forms: pre-fill "submitted by" fields
  document.querySelectorAll('[id$="-who"]').forEach(el => { el.value = u.name; });

  // Workspace page
  const wsNameEl = document.querySelector('#page-workspace h2');
  if (wsNameEl && u.name) wsNameEl.textContent = u.name;

  // Re-run home init so greeting uses the real name
  if (typeof initHomePage === 'function') initHomePage();
}

/* ══════════════════════════════════════════════════════
   INITIALISATION — runs on page load
   ══════════════════════════════════════════════════════ */

/**
 * Bootstraps M365 authentication on page load.
 * - If MSAL is not configured: dev mode, skip auth.
 * - If user is already signed in (cached token): restore session silently.
 * - Otherwise: show the M365 login screen.
 * @returns {Promise<void>}
 */
async function initM365Auth() {
  // Dev mode — MSAL not configured yet
  if (!MSAL_CONFIGURED) {
    console.info('MSAL not configured — running in dev mode (no auth).');
    return;
  }

  // MSAL library not loaded
  if (typeof msal === 'undefined') {
    console.error('MSAL library failed to load. Check CDN or network.');
    return;
  }

  const client = getMsal();
  if (!client) return;

  // Handle redirect response (if using redirect flow instead of popup)
  try { await client.handleRedirectPromise(); } catch (e) { /* ignore */ }

  const accounts = client.getAllAccounts();

  if (accounts.length > 0) {
    // Already signed in — restore session silently
    try {
      const account = accounts[0];
      await _loadUserProfile(client, account);
      _onAuthSuccess();
    } catch (e) {
      console.warn('Silent token renewal failed — showing login screen.', e);
      _showLoginScreen();
    }
  } else {
    _showLoginScreen();
  }
}

/**
 * Shows the M365 login overlay.
 * @returns {void}
 */
function _showLoginScreen() {
  const screen = document.getElementById('m365-login-screen');
  if (screen) screen.style.display = 'flex';
}

// Boot
document.addEventListener('DOMContentLoaded', initM365Auth);
