# Contributing to Fashion Men E-Commerce

Thank you for your interest in contributing to Fashion Men E-Commerce! This document provides guidelines and instructions for contributing to the project.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Process](#development-process)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Project Structure](#project-structure)

---

## 📜 Code of Conduct

### Our Pledge

We are committed to providing a welcoming and inspiring community for all. Please be respectful and constructive in your interactions.

### Our Standards

**Positive behaviors include:**
- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

**Unacceptable behaviors include:**
- Trolling, insulting/derogatory comments, and personal attacks
- Public or private harassment
- Publishing others' private information without permission
- Other conduct which could reasonably be considered inappropriate

---

## 🚀 Getting Started

### Prerequisites

- Node.js 18 or higher
- npm or yarn
- Git
- Code editor (VS Code recommended)

### Setup Development Environment

```bash
# Fork the repository on GitHub

# Clone your fork
git clone https://github.com/YOUR_USERNAME/fashion-men-ecommerce.git

# Navigate to directory
cd fashion-men-ecommerce

# Install dependencies
npm install

# Start development server
npm run dev
```

### Recommended VS Code Extensions

- ESLint
- Prettier
- Tailwind CSS IntelliSense
- TypeScript and JavaScript Language Features

---

## 💻 Development Process

### 1. Choose an Issue

- Browse [open issues](https://github.com/yourusername/fashion-men-ecommerce/issues)
- Comment on the issue to let others know you're working on it
- For new features, open an issue first to discuss

### 2. Create a Branch

```bash
# Create and checkout a new branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/bug-description
```

### Branch Naming Convention

- `feature/` - New features
- `fix/` - Bug fixes
- `docs/` - Documentation updates
- `refactor/` - Code refactoring
- `test/` - Adding tests
- `style/` - Code style updates

### 3. Make Changes

- Write clean, readable code
- Follow the existing code style
- Add comments for complex logic
- Update documentation if needed
- Test your changes thoroughly

### 4. Test Your Changes

```bash
# Run the development server
npm run dev

# Test in different browsers
# Test on mobile devices
# Verify all existing features still work
```

---

## 📝 Coding Standards

### TypeScript

```typescript
// ✅ Good
interface UserProps {
  name: string;
  email: string;
  role: 'customer' | 'admin';
}

export function UserCard({ name, email, role }: UserProps) {
  return (
    <div className="p-4 border rounded-lg">
      <h3>{name}</h3>
      <p>{email}</p>
      <Badge>{role}</Badge>
    </div>
  );
}

// ❌ Bad - No types
export function UserCard({ name, email, role }) {
  return <div>...</div>;
}
```

### Component Structure

```typescript
// 1. Imports
import { useState } from 'react';
import { Button } from '../ui/button';
import { User } from '../../lib/types';

// 2. Interface/Types
interface ComponentProps {
  user: User;
  onUpdate: (user: User) => void;
}

// 3. Component
export function Component({ user, onUpdate }: ComponentProps) {
  // 3a. State
  const [isEditing, setIsEditing] = useState(false);

  // 3b. Handlers
  const handleEdit = () => {
    setIsEditing(true);
  };

  // 3c. Render
  return (
    <div>
      {/* Component JSX */}
    </div>
  );
}
```

### Styling with Tailwind

```tsx
// ✅ Good - Semantic classes
<div className="flex items-center justify-between p-4 border rounded-lg hover:shadow-md transition-shadow">

// ✅ Good - Responsive design
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

// ❌ Bad - Inline styles (avoid unless absolutely necessary)
<div style={{ padding: '16px', border: '1px solid gray' }}>

// ⚠️ Avoid - Font size/weight classes (we use defaults)
// DON'T: className="text-xl font-bold"
// DO: Use <h1>, <h2>, etc. for typography
```

### File Organization

```
/pages/[feature]/
├── [Feature]Page.tsx        # Main page component
├── [Feature]Types.ts        # Feature-specific types (optional)
├── [Feature]Utils.ts        # Feature-specific utilities (optional)
└── README.md                # Feature documentation (optional)
```

### Naming Conventions

- **Components**: PascalCase (`UserDashboard.tsx`)
- **Files**: PascalCase for components, camelCase for utilities
- **Variables**: camelCase (`userName`, `isLoading`)
- **Constants**: UPPER_SNAKE_CASE (`MAX_ITEMS`, `API_URL`)
- **Interfaces**: PascalCase with descriptive names (`UserDashboardProps`)

---

## 📝 Commit Guidelines

### Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

### Types

- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation changes
- `style` - Code style changes (formatting, etc.)
- `refactor` - Code refactoring
- `test` - Adding tests
- `chore` - Maintenance tasks

### Examples

```bash
# Good commits
git commit -m "feat(dashboard): add statistics cards to overview tab"
git commit -m "fix(cart): correct total calculation with customizations"
git commit -m "docs(readme): update installation instructions"
git commit -m "refactor(shop): extract filter logic into separate hook"

# Bad commits
git commit -m "fixed stuff"
git commit -m "updates"
git commit -m "WIP"
```

### Commit Best Practices

- Write clear, concise commit messages
- Use present tense ("add feature" not "added feature")
- Limit first line to 72 characters
- Reference issues in footer (`Fixes #123`)
- Make atomic commits (one logical change per commit)

---

## 🔄 Pull Request Process

### Before Submitting

1. ✅ Code follows project style guidelines
2. ✅ All tests pass
3. ✅ New features have documentation
4. ✅ Commit messages follow guidelines
5. ✅ Branch is up to date with main

### Submitting a PR

1. **Push your branch**
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create Pull Request on GitHub**
   - Clear, descriptive title
   - Detailed description of changes
   - Reference related issues
   - Add screenshots if UI changes

3. **PR Template**
   ```markdown
   ## Description
   Brief description of changes

   ## Type of Change
   - [ ] Bug fix
   - [ ] New feature
   - [ ] Documentation update
   - [ ] Code refactoring

   ## Testing
   Describe how you tested the changes

   ## Screenshots (if applicable)
   Add screenshots for UI changes

   ## Checklist
   - [ ] Code follows style guidelines
   - [ ] Self-review completed
   - [ ] Documentation updated
   - [ ] No new warnings
   ```

### Review Process

1. Maintainers will review your PR
2. Address any requested changes
3. Once approved, PR will be merged
4. Your contribution will be acknowledged

---

## 🏗️ Project Structure

### Adding a New Page

1. Create folder in `/pages/[feature-name]/`
   ```bash
   mkdir pages/new-feature
   ```

2. Create main component
   ```typescript
   // pages/new-feature/NewFeaturePage.tsx
   export function NewFeaturePage() {
     return <div>New Feature</div>;
   }
   ```

3. Export from index
   ```typescript
   // pages/index.ts
   export { NewFeaturePage } from './new-feature/NewFeaturePage';
   ```

4. Add route in App.tsx
   ```typescript
   case 'new-feature':
     return <NewFeaturePage />;
   ```

5. Update navigation (Header.tsx, MobileNav.tsx)

### Adding a New Component

1. Create in appropriate `/components/[domain]/` folder
2. Use TypeScript interfaces for props
3. Follow component structure guidelines
4. Export from component file

### Modifying Existing Features

1. Understand the current implementation
2. Check related components
3. Update types if needed
4. Test thoroughly
5. Update documentation

---

## 🧪 Testing Guidelines

### Manual Testing Checklist

- [ ] Feature works as expected
- [ ] No console errors
- [ ] Responsive on mobile, tablet, desktop
- [ ] Works in Chrome, Firefox, Safari
- [ ] Keyboard navigation works
- [ ] No accessibility issues
- [ ] Loading states work
- [ ] Error states work
- [ ] Edge cases handled

### Testing New Features

1. **Happy Path** - Normal user flow works
2. **Edge Cases** - Empty states, max values, etc.
3. **Error Cases** - Invalid input, network errors
4. **Responsive** - Mobile, tablet, desktop
5. **Accessibility** - Keyboard, screen readers

---

## 📚 Documentation

### When to Update Documentation

- Adding new features
- Changing existing behavior
- Adding new pages or components
- Modifying project structure

### Documentation Files

- `README.md` - Main project readme
- `QUICK_START.md` - User guide
- `FILE_STRUCTURE.md` - Architecture docs
- Component READMEs - Feature-specific docs

---

## 💡 Tips for Contributors

### General Tips

- Start with small contributions
- Read existing code to understand patterns
- Ask questions if unclear
- Be patient with review process
- Learn from feedback

### Finding Issues to Work On

- Look for `good first issue` label
- Check `help wanted` label
- Browse open issues
- Suggest improvements

### Getting Help

- Comment on the issue
- Ask in pull request
- Check documentation
- Review similar code

---

## 🎯 Priority Areas

We especially welcome contributions in these areas:

1. **Tests** - Adding unit and integration tests
2. **Accessibility** - Improving a11y features
3. **Performance** - Optimization improvements
4. **Documentation** - Better docs and examples
5. **Bug Fixes** - Resolving reported issues

---

## 📞 Questions?

- Open an issue for questions
- Tag with `question` label
- Be specific and provide context

---

## 🙏 Thank You!

Your contributions make this project better. Whether it's code, documentation, bug reports, or suggestions - every contribution is valuable and appreciated!

---

**Happy Coding! 🚀**
