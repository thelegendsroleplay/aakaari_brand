# Complete Social Login Setup Guide

## Overview

Your Aakaari Brand theme now has fully functional Google and Facebook social login capabilities. This guide will walk you through the complete setup process.

## What's Been Implemented

✅ **Privacy Policy Template** - Comprehensive GDPR and CCPA compliant privacy policy addendum
✅ **OAuth Integration** - Secure Google and Facebook OAuth 2.0 authentication
✅ **User Management** - Automatic user creation and login handling
✅ **Admin Settings** - Easy-to-use WordPress admin panel for API credentials
✅ **Responsive UI** - Beautiful, mobile-friendly social login buttons
✅ **Security** - Nonce verification, CSRF protection, and secure token handling

## Files Modified/Created

### New Files Created:
- `inc/social-login.php` - Social login handler with OAuth integration
- `PRIVACY-POLICY-SOCIAL-LOGIN.md` - Privacy policy template
- `SOCIAL-LOGIN-COMPLETE-GUIDE.md` - This file

### Modified Files:
- `functions.php` - Added social login include
- `woocommerce/myaccount/form-login.php` - Updated with OAuth buttons
- `assets/css/account.css` - Enhanced button styles

## Setup Instructions

### Step 1: Update Privacy Policy

1. Log in to WordPress Admin
2. Go to **Settings → Privacy**
3. Edit your Privacy Policy page
4. Add the content from `PRIVACY-POLICY-SOCIAL-LOGIN.md` to your privacy policy
5. **Important:** Replace placeholder text:
   - `[your-email@domain.com]` → Your actual email
   - `[your-phone-number]` → Your actual phone
   - `[your-business-address]` → Your actual address
   - `[Current Date]` → Today's date
6. Save and publish

### Step 2: Configure Google OAuth

#### 2.1 Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click "Select a project" → "New Project"
3. Enter project name: "Aakaari Brand Social Login" (or your preference)
4. Click "Create"

#### 2.2 Enable Google+ API

1. In the navigation menu, go to **APIs & Services → Library**
2. Search for "Google+ API" or "Google Identity Services"
3. Click on it and press "Enable"

#### 2.3 Create OAuth Credentials

1. Go to **APIs & Services → Credentials**
2. Click "Create Credentials" → "OAuth client ID"
3. If prompted, configure the OAuth consent screen first:
   - User Type: **External**
   - App name: **Aakaari Brand** (or your site name)
   - User support email: Your email
   - Developer contact: Your email
   - Click "Save and Continue"
   - Scopes: Click "Add or Remove Scopes"
     - Select: `userinfo.email`
     - Select: `userinfo.profile`
   - Click "Save and Continue"
   - Test users: Add your email for testing
   - Click "Save and Continue"

4. Back to Create OAuth Client ID:
   - Application type: **Web application**
   - Name: "Aakaari Brand Website"
   - Authorized JavaScript origins:
     - `https://yourdomain.com` (replace with your actual domain)
   - Authorized redirect URIs:
     - `https://yourdomain.com/oauth-callback/google/`
     - **IMPORTANT:** Use your actual domain and ensure it ends with a trailing slash
   - Click "Create"

5. **Copy your credentials:**
   - Client ID (looks like: `123456789-abc123.apps.googleusercontent.com`)
   - Client Secret (looks like: `GOCSPX-abc123def456`)

#### 2.4 Add Credentials to WordPress

1. Go to WordPress Admin
2. Navigate to **Settings → Social Login**
3. Paste your Google Client ID
4. Paste your Google Client Secret
5. Click "Save Changes"

### Step 3: Configure Facebook OAuth

#### 3.1 Create Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click "My Apps" → "Create App"
3. Select "Consumer" as the app type
4. Click "Next"
5. Enter app details:
   - App name: "Aakaari Brand" (or your preference)
   - Contact email: Your email
   - Click "Create App"

#### 3.2 Add Facebook Login Product

1. In your app dashboard, find "Facebook Login"
2. Click "Set Up"
3. Select "Web" as the platform
4. Enter your site URL: `https://yourdomain.com`
5. Click "Save" and continue

#### 3.3 Configure OAuth Settings

1. In the left sidebar, go to **Facebook Login → Settings**
2. Under "Valid OAuth Redirect URIs", add:
   - `https://yourdomain.com/oauth-callback/facebook/`
   - **IMPORTANT:** Use your actual domain and ensure it ends with a trailing slash
3. Scroll down and make sure these settings are enabled:
   - Login with the JavaScript SDK: **Yes**
   - Web OAuth Login: **Yes**
   - Enforce HTTPS: **Yes**
4. Click "Save Changes"

#### 3.4 Get Your Credentials

1. Go to **Settings → Basic** in the left sidebar
2. You'll see:
   - **App ID** (looks like: `1234567890123456`)
   - **App Secret** (click "Show" to reveal)
3. **Copy both values**

#### 3.5 Configure App for Production

1. At the top of the page, switch the app from "Development" to "Live" mode
2. You may need to:
   - Add a privacy policy URL
   - Add app icon
   - Select a category
   - Complete business verification (for larger apps)

#### 3.6 Add Credentials to WordPress

1. Go to WordPress Admin
2. Navigate to **Settings → Social Login**
3. Paste your Facebook App ID
4. Paste your Facebook App Secret
5. Click "Save Changes"

### Step 4: Test Your Implementation

#### Pre-Testing Checklist

- [ ] Your site must be using HTTPS (SSL certificate installed)
- [ ] Privacy policy has been updated
- [ ] Google credentials are saved in WordPress admin
- [ ] Facebook credentials are saved in WordPress admin
- [ ] You're testing in an incognito/private browser window

#### Testing Google Login

1. Open an incognito/private browser window
2. Go to your website's login page (`/my-account/`)
3. You should see a "Google" button under "Or continue with"
4. Click the Google button
5. You should be redirected to Google's login page
6. Sign in with a Google account
7. You should be redirected back to your site and automatically logged in
8. Verify your account was created in **WordPress Admin → Users**

#### Testing Facebook Login

1. Open an incognito/private browser window
2. Go to your website's login page (`/my-account/`)
3. You should see a "Facebook" button under "Or continue with"
4. Click the Facebook button
5. You should be redirected to Facebook's login page
6. Sign in with a Facebook account
7. Authorize the app
8. You should be redirected back to your site and automatically logged in
9. Verify your account was created in **WordPress Admin → Users**

### Step 5: Troubleshooting

#### Google Login Issues

**"Redirect URI Mismatch" Error**
- Solution: Make sure the redirect URI in Google Cloud Console exactly matches:
  - `https://yourdomain.com/oauth-callback/google/`
- Check for:
  - Correct protocol (https://)
  - Correct domain
  - Trailing slash at the end
  - No extra spaces

**"Access Blocked: This app's request is invalid"**
- Solution: You need to configure the OAuth consent screen
- Go to Google Cloud Console → OAuth consent screen
- Fill in all required fields
- Add test users if in development mode

**"Invalid Client" Error**
- Solution: Double-check your Client ID and Client Secret
- Make sure there are no extra spaces when copying
- Re-save in WordPress admin

#### Facebook Login Issues

**"Can't Load URL: The domain of this URL isn't included in the app's domains"**
- Solution: Add your domain to Facebook App
- Go to Facebook Developers → Settings → Basic
- Add your domain under "App Domains"
- Add privacy policy URL

**"URL Blocked: This redirect failed because the redirect URI is not whitelisted"**
- Solution: Check your OAuth Redirect URI
- Go to Facebook Login → Settings
- Ensure `https://yourdomain.com/oauth-callback/facebook/` is listed
- Must include trailing slash

**App Not Showing Up**
- Solution: Make sure your app is in "Live" mode (not Development)
- Add required information in app settings
- Complete privacy policy requirements

#### General Issues

**Social login buttons don't appear**
- Check that you've saved credentials in **Settings → Social Login**
- Clear WordPress cache
- Clear browser cache
- Check browser console for JavaScript errors

**"Email already exists" error**
- This means a user with that email is already registered
- They should use regular login or password recovery
- Or they can use the same social login they used originally

**Users created but can't login**
- Check WooCommerce settings
- Make sure registration is enabled
- Verify user role is set to "Customer"

## Security Best Practices

### DO's ✅

- ✅ Always use HTTPS (SSL certificate required)
- ✅ Keep your Client Secret and App Secret private
- ✅ Never commit secrets to Git (they're stored in WordPress database)
- ✅ Regularly review and rotate API credentials
- ✅ Test in development environment first
- ✅ Keep WordPress and plugins updated
- ✅ Monitor user registrations for suspicious activity
- ✅ Implement rate limiting if needed
- ✅ Keep privacy policy up to date

### DON'Ts ❌

- ❌ Never share your Client Secret or App Secret publicly
- ❌ Don't skip the privacy policy update
- ❌ Don't use HTTP (must be HTTPS)
- ❌ Don't ignore security warnings from Google/Facebook
- ❌ Don't hardcode credentials in theme files
- ❌ Don't disable SSL verification
- ❌ Don't skip testing before going live

## Advanced Configuration

### Customizing User Data

Edit `inc/social-login.php` to customize what data is saved:

```php
// Around line 335, in process_social_login()
update_user_meta($user_id, 'aakaari_social_picture', $picture);
// Add more custom fields as needed
```

### Adding More Providers

The system is designed to be extensible. To add Twitter, LinkedIn, etc.:

1. Add credentials options in `register_settings()`
2. Create `get_twitter_auth_url()` method
3. Create `handle_twitter_callback()` method
4. Add button to `form-login.php`

### Custom Redirect After Login

Edit `inc/social-login.php` around line 365:

```php
// Change this line:
wp_redirect(wc_get_page_permalink('myaccount'));

// To redirect to a custom page:
wp_redirect(home_url('/custom-page/'));
```

### Styling Customization

Edit `assets/css/account.css` to customize button appearance:

```css
/* Google button specific styles */
.social-btn.google-login-btn {
  background: #4285f4;
  color: white;
  border-color: #4285f4;
}

/* Facebook button specific styles */
.social-btn.facebook-login-btn {
  background: #1877f2;
  color: white;
  border-color: #1877f2;
}
```

## Compliance & Legal

### GDPR Compliance

Your implementation includes:
- ✅ User consent (they choose to use social login)
- ✅ Privacy policy disclosure
- ✅ Right to access data
- ✅ Right to deletion
- ✅ Data minimization (only collect necessary data)
- ✅ Secure data storage

### CCPA Compliance

Your implementation includes:
- ✅ Privacy policy disclosure
- ✅ No sale of personal information
- ✅ Right to know what data is collected
- ✅ Right to deletion
- ✅ Non-discrimination

### Additional Recommendations

1. **Cookie Consent Banner** - Consider adding a cookie consent banner
2. **Terms of Service** - Link to ToS from registration page
3. **Data Processing Agreement** - If operating in EU, ensure proper DPA
4. **Age Verification** - Implement if required by your jurisdiction
5. **Legal Review** - Have a lawyer review your implementation

## Maintenance

### Regular Tasks

**Monthly:**
- Review user accounts created via social login
- Check for any OAuth errors in server logs
- Verify OAuth credentials are still valid

**Quarterly:**
- Review and update privacy policy if needed
- Test login flow to ensure it's working
- Update API versions if deprecated

**Annually:**
- Rotate OAuth credentials
- Review security practices
- Audit user data retention policies

### Monitoring

Watch for these in WordPress admin or server logs:
- Failed OAuth attempts
- Invalid redirect URI errors
- Rate limiting issues
- Unusual user creation patterns

## Support & Resources

### Official Documentation

- [Google OAuth 2.0 Guide](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [WooCommerce Account Pages](https://woocommerce.com/document/woocommerce-shortcodes/)

### WordPress Resources

- Settings → Social Login (your admin panel)
- Users → All Users (view social login users)
- Settings → Privacy (privacy policy page)

### Getting Help

If you encounter issues:

1. Check this guide's troubleshooting section
2. Review browser console for JavaScript errors
3. Check WordPress debug.log for PHP errors
4. Verify OAuth credentials are correct
5. Test in incognito window
6. Check provider's developer console for errors

## FAQ

**Q: Do I need both Google and Facebook?**
A: No, you can enable just one. The buttons will only appear if credentials are configured.

**Q: Will this work with existing users?**
A: Yes, if a user with the same email exists, they'll be logged in automatically.

**Q: Can users disconnect social login?**
A: Users can disconnect by changing their password or contacting you. You can enhance this by adding a disconnect button in account settings.

**Q: Is this plugin-free?**
A: Yes, this is a custom implementation built into your theme. No plugins required.

**Q: Does this work on mobile?**
A: Yes, it's fully responsive and works on all devices.

**Q: What data is collected?**
A: Only email, name, and profile picture. No passwords or other sensitive data.

**Q: Can I customize the button text?**
A: Yes, edit `form-login.php` and change the text in the button elements.

**Q: Will this slow down my site?**
A: No, the OAuth process happens off-site. There's minimal impact on performance.

## Changelog

### Version 1.0 (2025-11-23)
- ✅ Initial implementation
- ✅ Google OAuth integration
- ✅ Facebook OAuth integration
- ✅ Admin settings page
- ✅ Privacy policy template
- ✅ Comprehensive documentation

## Next Steps

Now that social login is set up:

1. ✅ Test thoroughly with both Google and Facebook
2. ✅ Update your privacy policy
3. ✅ Announce the new login option to your users
4. ✅ Monitor user registrations
5. ✅ Gather feedback and improve UX

---

**Need Help?**
Check the troubleshooting section or review the WordPress debug log at `/wp-content/debug.log`

**Want to Customize?**
All code is in `inc/social-login.php` and well-commented for easy modification.

**Ready to Launch?**
Make sure you've completed all steps in this guide and tested thoroughly!
