# Homepage CSS Debug Checklist

## Quick Fixes to Try First

### 1. Hard Refresh Browser (CRITICAL)
- **Windows/Linux**: Press `Ctrl + Shift + R`
- **Mac**: Press `Cmd + Shift + R`
- This clears cached CSS files

### 2. Clear WordPress Cache
If you're using a caching plugin (WP Super Cache, W3 Total Cache, etc.):
- Go to WordPress Admin → Plugins
- Find your caching plugin
- Click "Clear Cache" or "Purge Cache"

### 3. Verify Page Template Selection
- WordPress Admin → Pages → Edit your homepage
- Right sidebar → Page Attributes → Template
- **Must be set to**: "Homepage"
- Save the page

---

## Browser Developer Tools Check

### Step 1: Open Developer Tools
- Press `F12` or right-click → "Inspect"

### Step 2: Check Network Tab
1. Click "Network" tab
2. Reload the page (`Ctrl + R`)
3. Look for `homepage.css` in the list
4. **Expected**: Status 200 (green)
5. **Problem**: Status 404 (red) = file not found

### Step 3: Check Console Tab
1. Click "Console" tab
2. Look for any red error messages
3. **Common issues**:
   - "Failed to load resource: 404" = file path wrong
   - CSS syntax errors = CSS file has issues

### Step 4: Check Elements Tab
1. Click "Elements" (or "Inspector") tab
2. Find `<head>` section
3. Look for these lines:
```html
<link rel='stylesheet' id='fashionmen-style-css' href='.../style.css?ver=1.0.1' />
<link rel='stylesheet' id='fashionmen-homepage-css' href='.../assets/css/homepage.css?ver=1.0.1' />
```
4. **If missing**: CSS not being enqueued (check template selection)

### Step 5: Verify CSS is Applied
1. In Elements tab, click on any homepage element (hero, category card, etc.)
2. Right sidebar → "Styles" panel
3. Should see styles from `homepage.css`
4. **If crossed out**: Styles are being overridden
5. **If not showing**: CSS not loading

---

## What You Should See vs Common Issues

### ✅ Expected Design (Figma Match)

**Hero Section:**
- Full-width section, 500px mobile / 600px desktop height
- Background image with dark overlay (40% black)
- White centered text: Large uppercase title
- Two buttons: White solid + White outline

**Categories Section:**
- White background
- "Shop by Category" heading
- 4 square cards in 2 columns (mobile) / 4 columns (desktop)
- Images with dark overlay, white text
- Cards lift up on hover, images zoom

**Featured Products Section:**
- Light gray background (#f9fafb)
- "Featured Collection" heading
- Product grid: 2 (mobile) / 3 (tablet) / 4 (desktop) columns
- Product images with 3:4 aspect ratio
- "View All Products" button at bottom

---

### ❌ Common Issues

**Issue 1: No Styling At All**
- **Symptoms**: Plain text, no colors, images stacked vertically
- **Cause**: CSS file not loading
- **Fix**:
  1. Verify template is set to "Homepage"
  2. Hard refresh browser
  3. Check Network tab for 404 errors

**Issue 2: Partial Styling**
- **Symptoms**: Some sections look good, others don't
- **Cause**: CSS partially applied or overridden
- **Fix**: Check Elements → Styles for crossed-out rules

**Issue 3: Wrong Colors/Fonts**
- **Symptoms**: Design loads but looks different
- **Cause**: Old Tailwind CSS still cached
- **Fix**:
  1. Hard refresh: `Ctrl + Shift + R`
  2. Clear browser cache completely
  3. Check Network tab - should NOT see Tailwind CDN

**Issue 4: Layout Broken on Mobile**
- **Symptoms**: Looks good on desktop, broken on mobile
- **Cause**: Viewport meta tag missing
- **Fix**: Check `<head>` for: `<meta name="viewport" content="width=device-width, initial-scale=1">`

---

## File Paths to Verify

These files must exist:
```
/wp-content/themes/fashionmen/
├── homepage.php                    ← Template file
├── inc/homepage.php                ← Functions
├── assets/
│   ├── css/
│   │   └── homepage.css            ← Styles (8.6 KB)
│   └── js/
│       └── homepage.js             ← Scripts
├── functions.php                   ← Enqueues CSS
├── style.css                       ← WordPress required
├── header.php                      ← Contains wp_head()
└── footer.php                      ← Contains wp_footer()
```

---

## Manual CSS Load Test

If everything else fails, try manually loading CSS:

1. Edit `header.php`
2. **Before** `<?php wp_head(); ?>` add:
```php
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/homepage.css">
```
3. Save and refresh

**If this works**: Problem is with enqueue system
**If this doesn't work**: Problem is with CSS file itself

---

## Still Not Working?

Please provide:
1. Screenshot of the homepage showing the issue
2. Screenshot of F12 → Network tab showing loaded files
3. Screenshot of F12 → Console tab showing any errors
4. What you changed from default WordPress installation (plugins, server config, etc.)
