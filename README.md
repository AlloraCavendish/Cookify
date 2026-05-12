# 🍳 Cookify — Smart Recipe Recommendation System

Cookify is a modern web-based smart recipe recommendation platform built using the Laravel ecosystem.  
The system helps users discover recipes based on available ingredients while providing intelligent suggestions for missing items.

---

# ✨ Features

## 🥗 Smart Recipe Recommendation Engine
Cookify analyzes user-provided ingredients and categorizes recipes into:

- ✅ Ready Recipes  
  Recipes that can be cooked immediately using available ingredients.

- 💡 Suggested Recipes  
  Recipes that are close matches, including:
  - missing main ingredients
  - missing optional ingredients

### Recommendation Features
- Ingredient-based matching
- Main vs optional ingredient classification
- Cuisine filtering
- Preparation time filtering
- Smart sorting by fewest missing ingredients
- Pagination support
- Optimized caching system

---

# 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| Laravel | Backend framework |
| Filament 3 | Admin panel |
| Tailwind CSS | Frontend styling |
| Livewire | Reactive Laravel components |
| Playwright | End-to-end testing |
| MySQL | Database |
| Laragon | Local development environment |

---

# 🎛️ Admin Panel

Cookify uses **Filament 3** for rapid admin dashboard development.

Admin dashboard route:

```bash
/admin
```

### Admin Features
- Recipe CRUD
- Ingredient CRUD
- Form validation
- Table management
- Admin workflow management

---

# 📱 Responsive Frontend

The frontend has been fully migrated to **Tailwind CSS** with responsive layouts optimized for:

- Desktop
- Tablet
- Mobile devices

Inline CSS styling has been removed and replaced with utility-first Tailwind classes.

---

# 🔐 Authentication Features

Cookify includes Laravel built-in authentication features:

- User registration
- Login/logout
- Forgot password
- Password reset
- Email verification

---

# ⚡ Recommendation API Overview

The recommendation system:

1. Accepts user ingredients
2. Normalizes and validates input
3. Matches recipes against available ingredients
4. Separates recipes into:
   - ready
   - suggested
5. Sorts suggestions by closest match
6. Returns paginated API responses

---

# 🚀 Performance Optimizations

Implemented optimizations include:

- Eloquent eager loading
- Query filtering
- API pagination
- Laravel caching (`Cache::remember`)
- Ingredient normalization
- Duplicate filtering

---

# 🧪 Automated Testing

Cookify uses **Playwright** for automated browser testing.

### Current Testing Capabilities
- End-to-end testing
- UI interaction testing
- Browser automation
- Workflow validation

---

# 📂 Project Structure

```bash
app/
├── Http/
│   ├── Controllers/
│   │   ├── API/
│   │   └── Admin/
├── Models/
resources/
routes/
database/
```

---

# 📈 Current Project Status

## Completed
- Laravel backend setup
- Filament admin panel
- Ingredient CRUD
- Recipe CRUD
- Responsive Tailwind frontend
- Recommendation engine API
- Authentication system
- Email verification
- Password reset
- Playwright integration
- Caching optimization

## Planned / Future Improvements
- Personalized recommendations
- AI-powered suggestions
- Nutrition tracking
- Favorite recipes
- Shopping list generation
- Advanced search filters
- Deployment pipeline

---

<!-- # 📸 Screenshots

> Add screenshots here later

Examples:
- Homepage
- Recommendation page
- Admin dashboard
- Mobile responsive layout

--- -->

# 👨‍💻 Author

Developed by Aiman Haziq

---

# 📄 License

This project is licensed for educational and portfolio purposes.