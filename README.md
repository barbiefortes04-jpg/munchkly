# Munchkly

A modern Twitter-like social media application built with Laravel and Tailwind CSS.

![Munchkly Logo](https://via.placeholder.com/200x60/3B82F6/FFFFFF?text=Munchkly)

## Project Description

Munchkly is a feature-rich social media platform that allows users to share their thoughts, interact with others, and build communities through short-form posts (tweets). Inspired by Twitter's simplicity and engagement model, Munchkly provides a clean, responsive interface for seamless social interaction.

### Purpose
- **Social Interaction**: Enable users to share thoughts and connect with others
- **Real-time Engagement**: Provide instant feedback through likes and comments
- **User Expression**: Allow personal expression through customizable profiles
- **Community Building**: Foster connections through shared interests and conversations

## Features Implemented

### User Authentication
- User Registration: Secure account creation with name, email, and password
- User Login/Logout: Session-based authentication with "Remember Me" option
- Protected Routes: Access control for authenticated users only
- Password Security: Bcrypt hashing and validation

### Tweet Management
- Create Tweets: Post thoughts with 280-character limit
- Real-time Character Counter: Visual feedback while typing
- Display All Tweets: Homepage feed with newest posts first
- Edit Tweets: Update own tweets with "edited" indicator
- Delete Tweets: Remove own tweets with confirmation prompt
- Timestamp Display: Show creation time and "time ago" format

### Like System
- Like/Unlike Tweets: Toggle likes on any tweet (including own)
- Like Counter: Real-time display of total likes
- Visual Indicators: Heart icon changes when liked
- Unique Constraint: One like per user per tweet
- Instant Feedback: Immediate UI updates

### User Profile
- Profile Pages: Dedicated user profile views
- User Information: Display name and join date
- User Tweets: List all tweets by specific user
- Statistics: Total tweet count and likes received
- Avatar System: Gradient-based profile pictures

### UI/UX Design
- Responsive Design: Perfect on mobile, tablet, and desktop
- Tailwind CSS: Modern, utility-first styling
- Clean Interface: Intuitive navigation and layout
- Consistent Styling: Unified design language throughout
- Accessibility: Proper contrast and keyboard navigation
- Loading States: Visual feedback for user actions

### Technical Implementation
- Laravel Framework: MVC architecture with best practices
- Database Design: Proper migrations, relationships, and constraints
- Eloquent Models: Rich model relationships and methods
- Form Validation: Client-side and server-side validation
- Security: CSRF protection, SQL injection prevention
- Code Organization: Clean, maintainable code structure

## Installation Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 16+ (optional, for asset compilation)

### Step 1: Clone the Repository
```bash
git clone https://github.com/barbiefortes04-jpg/munchkly.git
cd munchkly
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables
Edit `.env` file with your database credentials:
```env
APP_NAME=Munchkly
APP_ENV=local
APP_KEY=base64:YCWoNx9rFyr454mlLQrwOLzmDjyOC+UMhA/9TaNJCug=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=munchkly
DB_USERNAME=root
DB_PASSWORD=
```

## Database Setup Steps

### Step 1: Create Database
```sql
CREATE DATABASE munchkly;
```

### Step 2: Run Migrations
```bash
php artisan migrate
```

### Step 3: Seed Sample Data (Optional)
```bash
php artisan db:seed
```

### Database Schema

#### Users Table
```sql
- id (Primary Key)
- name (String)
- email (Unique String)
- email_verified_at (Timestamp, Nullable)
- password (Hashed String)
- remember_token (String, Nullable)
- created_at (Timestamp)
- updated_at (Timestamp)
```

#### Tweets Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- content (String, Max 280 chars)
- is_edited (Boolean, Default false)
- created_at (Timestamp)
- updated_at (Timestamp)
```

#### Likes Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- tweet_id (Foreign Key → tweets.id)
- created_at (Timestamp)
- updated_at (Timestamp)
- Unique Index: (user_id, tweet_id)
```

## Screenshots

### Homepage - Feed View
*Clean, responsive design showing all tweets with like buttons and user interactions*

### User Registration
*Simple, intuitive registration form with validation*

### User Profile
*Comprehensive profile page with user statistics and tweet history*

### Tweet Creation
*Real-time character counter and posting interface*

### Mobile Responsive
*Perfectly optimized for all device sizes*

## Running the Application

### Development Server
```bash
php artisan serve
```
Visit: `http://localhost:8000`

### Production Deployment
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`

## Testing

### Sample User Accounts (After Seeding)
- **john@munchkly.test** / password
- **jane@munchkly.test** / password
- **mike@munchkly.test** / password
- **sarah@munchkly.test** / password
- **alex@munchkly.test** / password

### Manual Testing Checklist
- [ ] User registration with validation
- [ ] User login/logout functionality
- [ ] Tweet creation with character limit
- [ ] Tweet editing with "edited" indicator
- [ ] Tweet deletion with confirmation
- [ ] Like/unlike functionality
- [ ] Profile page display
- [ ] Responsive design on multiple devices

## Project Structure

```
munchkly/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       ├── TweetController.php
│   │       ├── LikeController.php
│   │       └── UserController.php
│   └── Models/
│       ├── User.php
│       ├── Tweet.php
│       └── Like.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── auth/
│       ├── tweets/
│       └── profile/
├── routes/
│   └── web.php
└── README.md
```

## Technologies Used

### Backend
- **Laravel 10** - PHP Framework
- **MySQL** - Database
- **Eloquent ORM** - Database relationships
- **Laravel Validation** - Form validation
- **Laravel Authentication** - User management

### Frontend
- **Blade Templates** - View engine
- **Tailwind CSS** - Utility-first CSS framework
- **Font Awesome** - Icon library
- **Vanilla JavaScript** - Character counter and interactions

### Development Tools
- **Composer** - PHP dependency manager
- **Git** - Version control
- **VS Code** - Code editor

## Credits

### AI Tools Used
This project was developed with assistance from **GitHub Copilot (Claude Sonnet 4)**:

**How AI Was Used:**
- **Code Generation**: Assisted in writing Laravel controllers, models, and views
- **Database Design**: Helped create optimal migration files and relationships
- **UI/UX Design**: Generated responsive Tailwind CSS components and layouts
- **Documentation**: Assisted in creating comprehensive README and code comments
- **Best Practices**: Ensured Laravel conventions and security best practices
- **Problem Solving**: Helped debug issues and optimize code structure

**Human Contributions:**
- Project planning and requirements analysis
- Final code review and quality assurance
- Testing and bug fixes
- Deployment configuration
- Custom styling and user experience decisions

### Development Team
- **Developer**: [Jherilyn Fortes] - Primary developer and project architect
- **AI Assistant**: GitHub Copilot - Code generation and development assistance

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Live Deployment

**Deployment Platform**: Laravel Cloud  
**Repository**: `https://github.com/barbiefortes04-jpg/munchkly.git`

### Laravel Cloud Deployment Steps

1. **Connect Repository**
   ```bash
   # Repository is ready at:
   https://github.com/barbiefortes04-jpg/munchkly.git
   ```

2. **Environment Configuration**
   - Laravel Cloud will use the production environment variables
   - Database will be automatically provisioned
   - SSL certificate will be automatically configured

3. **Build Process**
   ```bash
   # Automatic build commands:
   composer install --optimize-autoloader --no-dev
   npm ci && npm run build
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Storage Setup**
   ```bash
   # Automatic storage link creation:
   php artisan storage:link
   ```

### Deployment Features
- ✅ Production-ready Laravel configuration
- ✅ Optimized asset building with Vite
- ✅ Database migrations included
- ✅ SSL certificate enabled
- ✅ CDN integration
- ✅ Automatic backups
- ✅ Performance monitoring
- ✅ Error tracking

### Post-Deployment Checklist
- [ ] Verify database connection
- [ ] Test user registration/login
- [ ] Check tweet creation functionality
- [ ] Verify file uploads work
- [ ] Test theme switching
- [ ] Confirm responsive design
- [ ] Validate SSL certificate

## Support

For support, email support@munchkly.test or create an issue in the GitHub repository.

---

**Built with Laravel, Tailwind CSS, and GitHub Copilot**