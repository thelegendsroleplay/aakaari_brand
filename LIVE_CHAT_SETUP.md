# Live Chat Setup Instructions

Your website now has live chat support integrated! Follow these simple steps to activate it:

## Step 1: Create Free Tawk.to Account

1. Go to https://www.tawk.to
2. Click "Sign Up Free"
3. Create your account (100% free, no credit card required)

## Step 2: Get Your Widget Code

1. After signing up, you'll be in the dashboard
2. Go to **Administration** → **Chat Widget**
3. You'll see your widget code that looks like:
   ```
   https://embed.tawk.to/XXXXX/YYYYY
   ```
4. Copy the two IDs:
   - **Property ID**: The first long code (XXXXX)
   - **Widget ID**: The second code (YYYYY)

## Step 3: Add IDs to Your Website

1. Open `footer.php` in your theme
2. Find the line: `s1.src='https://embed.tawk.to/YOUR_PROPERTY_ID/YOUR_WIDGET_ID';`
3. Replace:
   - `YOUR_PROPERTY_ID` with your Property ID
   - `YOUR_WIDGET_ID` with your Widget ID

## Step 4: Customize Your Chat Widget (Optional)

In your Tawk.to dashboard, you can customize:

- **Widget Appearance**: Colors, position, bubble text
- **Pre-chat Form**: Collect visitor info before chat
- **Offline Messages**: Handle messages when you're away
- **Automated Triggers**: Send proactive messages to visitors
- **Agent Profiles**: Add team members and their photos
- **Mobile Apps**: iOS and Android apps to chat on-the-go

## Features You Get with Tawk.to

✅ **Unlimited Chats** - No limits on conversations
✅ **Unlimited Agents** - Add your whole team
✅ **Mobile Apps** - Chat from anywhere
✅ **Visitor Monitoring** - See who's on your site
✅ **Chat History** - All conversations saved
✅ **File Sharing** - Send images/documents
✅ **Screen Sharing** - See customer's screen
✅ **Shortcuts** - Quick responses for common questions
✅ **Departments** - Route chats to different teams
✅ **Triggers** - Auto-send messages based on behavior
✅ **Knowledge Base** - Self-service help articles
✅ **Ticketing System** - Convert chats to tickets
✅ **Analytics** - Track chat performance

## Alternative Live Chat Solutions

If you prefer a different service, here are other options:

### 1. **Crisp** (https://crisp.chat)
- Free plan available
- Similar features to Tawk.to
- Nice UI

### 2. **LiveChat** (https://www.livechat.com)
- Premium service ($20/month)
- More advanced features
- Better for enterprise

### 3. **Tidio** (https://www.tidio.com)
- Free plan for up to 3 agents
- AI chatbot included
- Good for small teams

### 4. **Facebook Messenger Chat**
- Completely free
- Uses your Facebook page
- Good if you're active on Facebook

## How to Remove Live Chat

If you want to remove the live chat:

1. Open `footer.php`
2. Delete the entire Tawk.to script block (between the comments)
3. Save the file

---

## Support

The live chat widget will appear as a small bubble in the bottom-right corner of your website. Visitors can click it to start a conversation with you in real-time!

You'll receive notifications in:
- Your Tawk.to dashboard
- Email (if enabled)
- Mobile app (iOS/Android)
- Desktop app (Windows/Mac/Linux)
