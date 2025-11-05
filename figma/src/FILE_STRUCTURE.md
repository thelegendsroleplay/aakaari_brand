# Project File Structure

This document explains the organized file structure of the Fashion Men e-commerce application.

## Overview

The project follows a modular architecture where each major feature/page has its own directory with related components, logic, and types.

## Directory Structure

```
├── App.tsx                          # Main application entry point
├── FILE_STRUCTURE.md               # This file - documentation
│
├── components/                     # Reusable UI components
│   ├── admin/                     # Admin-specific components
│   │   ├── AdminDashboard.tsx
│   │   ├── Analytics.tsx
│   │   ├── OrderManager.tsx
│   │   ├── ProductManager.tsx
│   │   └── UserManager.tsx
│   │
│   ├── auth/                      # Authentication components
│   │   ├── ForgotPasswordPage.tsx
│   │   ├── LoginPage.tsx
│   │   └── SignUpPage.tsx
│   │
│   ├── cart/                      # Cart-related components
│   │   └── CartPage.tsx
│   │
│   ├── checkout/                  # Checkout components
│   │   └── CheckoutPage.tsx
│   │
│   ├── home/                      # Home page components
│   │   ├── Categories.tsx
│   │   ├── FeaturedProducts.tsx
│   │   └── Hero.tsx
│   │
│   ├── layout/                    # Layout components (Header, Footer, etc.)
│   │   ├── Footer.tsx
│   │   ├── Header.tsx
│   │   └── MobileNav.tsx
│   │
│   ├── order/                     # Order-related components
│   │   ├── OrderConfirmation.tsx
│   │   └── OrderTracking.tsx
│   │
│   ├── pages/                     # Static page components
│   │   ├── AboutPage.tsx
│   │   ├── ContactPage.tsx
│   │   ├── FAQPage.tsx
│   │   ├── NotFoundPage.tsx
│   │   ├── PrivacyPage.tsx
│   │   ├── ShippingPage.tsx
│   │   └── TermsPage.tsx
│   │
│   ├── product/                   # Product-related components
│   │   ├── ProductCustomizer.tsx
│   │   ├── ProductDetail.tsx
│   │   ├── ProductReviews.tsx
│   │   ├── QuickView.tsx
│   │   ├── RecentlyViewed.tsx
│   │   ├── RelatedProducts.tsx
│   │   └── SizeGuide.tsx
│   │
│   ├── search/                    # Search functionality
│   │   └── SearchPage.tsx
│   │
│   ├── shop/                      # Shop page components
│   │   ├── ProductCard.tsx
│   │   ├── ProductFilters.tsx
│   │   └── ShopPage.tsx
│   │
│   ├── ui/                        # Shadcn UI components (reusable)
│   │   └── [various UI components]
│   │
│   ├── user/                      # User-related components (legacy)
│   │   └── UserDashboard.tsx      # ⚠️ Deprecated - use /pages/user-dashboard/
│   │
│   └── wishlist/                  # Wishlist components
│       └── WishlistPage.tsx
│
├── pages/                         # Page containers with organized structure
│   ├── home/
│   │   └── HomePage.tsx           # Home page container
│   │
│   ├── shop/
│   │   └── ShopPageContainer.tsx  # Shop page container
│   │
│   ├── product/
│   │   └── ProductDetailPage.tsx  # Product detail container
│   │
│   ├── cart/
│   │   └── CartPageContainer.tsx  # Cart page container
│   │
│   ├── checkout/
│   │   └── CheckoutPageContainer.tsx  # Checkout page container
│   │
│   ├── user-dashboard/            # User dashboard (NEW - Modern & Futuristic)
│   │   └── UserDashboardPage.tsx  # Complete redesign with modern UI
│   │
│   ├── wishlist/
│   │   └── WishlistPageContainer.tsx  # Wishlist container
│   │
│   ├── search/
│   │   └── SearchPageContainer.tsx    # Search page container
│   │
│   ├── auth/                      # Authentication page containers
│   │   ├── LoginPageContainer.tsx
│   │   └── SignUpPageContainer.tsx
│   │
│   └── static/                    # Static page containers
│       ├── AboutPageContainer.tsx
│       ├── ContactPageContainer.tsx
│       └── FAQPageContainer.tsx
│
├── lib/                           # Utilities and shared code
│   ├── mockData.ts               # Mock data for development
│   └── types.ts                  # TypeScript type definitions
│
├── styles/
│   └── globals.css               # Global styles and Tailwind config
│
└── guidelines/
    └── Guidelines.md             # Development guidelines
```

## Architecture Patterns

### Page Containers (`/pages/`)
- Each major feature has its own folder
- Contains the main page component
- Acts as a container that connects business logic with presentation
- Uses TypeScript interfaces for type safety

### Presentation Components (`/components/`)
- Reusable UI components
- Organized by feature domain (product, cart, checkout, etc.)
- Should be as stateless as possible
- Receive data and callbacks via props

### Styling Approach
- **Tailwind CSS** is used for all styling (inline utility classes)
- No separate CSS files per component
- Global styles in `/styles/globals.css`
- Design tokens and theme variables in globals.css

## Key Features by Location

### Navigation Pages (NEW)
The header now includes these navigation links:
- **Home** - Main landing page
- **Shop** - Product catalog
- **About** - Company information (`/components/pages/AboutPage.tsx`)
- **Contact** - Contact form (`/components/pages/ContactPage.tsx`)
- **FAQ** - Frequently asked questions (`/components/pages/FAQPage.tsx`)

### User Dashboard (REDESIGNED)
Location: `/pages/user-dashboard/UserDashboardPage.tsx`

Features:
- Modern, futuristic design with gradients and animations
- Tab-based navigation (Overview, Orders, Profile, Addresses, Payment, Settings)
- Statistics cards showing orders, spending, rewards, and wishlist
- Recent orders with status indicators
- Quick actions sidebar
- Rewards program integration
- Fully responsive mobile-first design
- Uses Motion (Framer Motion) for smooth animations

### Admin Features
- Product management with customization options
- Order management
- User management
- Analytics dashboard

## Development Guidelines

### Adding a New Page

1. Create a folder in `/pages/[feature-name]/`
2. Create the main page component: `[Feature]Page.tsx`
3. Add route handling in `App.tsx`
4. Update the type definitions in `/lib/types.ts` if needed
5. Add navigation link in Header and MobileNav if applicable

### Adding a New Component

1. Determine the feature domain (product, cart, user, etc.)
2. Create the component in `/components/[domain]/`
3. Use TypeScript interfaces for props
4. Keep components focused and single-responsibility
5. Use Tailwind for styling

### Styling Best Practices

- Use Tailwind utility classes inline
- Follow the design system tokens in `globals.css`
- Maintain consistent spacing (use Tailwind's spacing scale)
- Ensure mobile-first responsive design
- Use the `motion` library for animations when needed

## Future Improvements

- Add unit tests for page containers
- Implement lazy loading for route components
- Add error boundaries for better error handling
- Create custom hooks for shared logic
- Add Storybook for component documentation

## Notes

- The old UserDashboard in `/components/user/` is deprecated
- Use the new UserDashboardPage in `/pages/user-dashboard/` instead
- All new pages should follow the container pattern in `/pages/`
