# Social Login Setup Guide for Herrenn

This guide will help you set up Google, Facebook, and other social login options for your WordPress site.

## Recommended Method: Using WordPress Plugin

The easiest and most secure way to add social login is using a WordPress plugin.

### Recommended Plugin: Nextend Social Login

**Why Nextend Social Login:**
- Free and premium versions available
- Supports Google, Facebook, Twitter, LinkedIn, and more
- Easy to configure
- Secure OAuth implementation
- Regular updates
- WooCommerce compatible

### Installation Steps:

1. **Install the Plugin**
   - Go to WordPress Admin → Plugins → Add New
   - Search for "Nextend Social Login"
   - Click "Install Now" → "Activate"

2. **Configure Google Login**
   - Go to WordPress Admin → Settings → Nextend Social Login
   - Click on "Google" tab
   - Follow the wizard to create Google OAuth credentials:
     - Go to [Google Cloud Console](https://console.cloud.google.com/)
     - Create a new project or select existing
     - Enable Google+ API
     - Create OAuth 2.0 credentials
     - Set authorized redirect URIs (plugin will show you the exact URL)
     - Copy Client ID and Client Secret to the plugin settings

3. **Configure Facebook Login**
   - Click on "Facebook" tab in plugin settings
   - Create a Facebook App:
     - Go to [Facebook Developers](https://developers.facebook.com/)
     - Create new app
     - Add Facebook Login product
     - Configure OAuth redirect URIs
     - Copy App ID and App Secret to plugin settings

4. **Customize Button Appearance**
   - Go to "Settings" tab in plugin
   - Choose button style (icon only, icon + text, etc.)
   - Set button colors to match your brand
   - Position buttons (above/below login form)

### Alternative Plugin Options:

#### 1. Super Socializer
- Free version available
- 9+ social login providers
- Social sharing integration
- Easy setup

#### 2. Social Login by miniOrange
- Multiple providers
- Custom button styling
- Profile sync
- Role mapping

## Custom Code Integration (Advanced)

If you want to implement social login without a plugin, you'll need to:

### Prerequisites:
- OAuth 2.0 knowledge
- API credentials from providers
- SSL certificate (HTTPS required)

### Basic Implementation Steps:

1. **Register Your Application**
   - Google: Google Cloud Console
   - Facebook: Facebook Developers
   - Twitter: Twitter Developer Portal

2. **Add OAuth Library**
   ```php
   // Use Composer to install OAuth library
   composer require league/oauth2-client
   ```

3. **Create Login Buttons** (Already added to theme)
   - Edit `/woocommerce/myaccount/form-login.php` template
   - Add social login buttons

4. **Handle OAuth Callback**
   - Create callback endpoint
   - Verify OAuth token
   - Get user profile data
   - Create/login WordPress user
   - Sync user meta data

## WooCommerce Integration

Most social login plugins automatically integrate with WooCommerce. Make sure to:

1. **Test Login Flow**
   - Try logging in with social account
   - Verify account creation
   - Check if user can access WooCommerce account

2. **Configure Account Sync**
   - Sync email addresses
   - Map user roles correctly
   - Handle existing account conflicts

3. **GDPR Compliance**
   - Add privacy policy updates
   - Get consent for data processing
   - Allow users to disconnect social accounts

## Security Best Practices

1. **Always Use HTTPS**
   - Social login requires SSL
   - Install SSL certificate
   - Force HTTPS in WordPress settings

2. **Keep Credentials Secret**
   - Never commit API keys to git
   - Use environment variables
   - Rotate keys periodically

3. **Validate User Data**
   - Verify email addresses
   - Check for duplicate accounts
   - Implement rate limiting

## Styling Social Login Buttons

The theme already includes basic styles for login buttons. To customize:

### Add Custom CSS:
```css
/* Social login buttons */
.social-login-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 20px 0;
}

.social-login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 20px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.social-login-btn.google {
    background: #ffffff;
    color: #000000;
    border: 1px solid #dadce0;
}

.social-login-btn.google:hover {
    background: #f8f9fa;
}

.social-login-btn.facebook {
    background: #1877f2;
    color: #ffffff;
}

.social-login-btn.facebook:hover {
    background: #166fe5;
}

.social-login-btn.twitter {
    background: #1da1f2;
    color: #ffffff;
}

.social-login-btn.twitter:hover {
    background: #1a91da;
}
```

## Testing Checklist

- [ ] Google login works on desktop
- [ ] Google login works on mobile
- [ ] Facebook login works on desktop
- [ ] Facebook login works on mobile
- [ ] New users can register via social login
- [ ] Existing users can connect social accounts
- [ ] Email addresses are synced correctly
- [ ] User roles are assigned properly
- [ ] Users can access WooCommerce account after login
- [ ] Social login works on checkout page
- [ ] Privacy policy is updated

## Troubleshooting

### "Redirect URI Mismatch" Error
- Check that redirect URIs in provider settings match exactly
- Include both http:// and https:// versions if testing locally
- Remove trailing slashes

### Users Can't Login After Social Registration
- Check WooCommerce account settings
- Verify email confirmation is not required
- Check spam folder for verification emails

### Social Login Buttons Not Showing
- Clear WordPress cache
- Clear browser cache
- Check if plugin is activated
- Verify provider credentials are entered correctly

## Support

For plugin-specific issues:
- Nextend Social Login: [Support Forum](https://wordpress.org/support/plugin/nextend-facebook-connect/)
- Super Socializer: [Support Forum](https://wordpress.org/support/plugin/super-socializer/)

For custom implementation:
- Review OAuth 2.0 documentation
- Check provider-specific guidelines
- Test in development environment first

---

**Recommended Next Steps:**
1. Install Nextend Social Login plugin
2. Configure Google and Facebook providers
3. Test login flow
4. Style buttons to match Herrenn brand
5. Update privacy policy
