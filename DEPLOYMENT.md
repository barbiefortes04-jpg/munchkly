# Munchkly Deployment Guide 🚀

## Live Deployment URL
**🌐 Production URL**: [To be deployed]

## Deployment Options

### Option 1: Laravel Cloud (Recommended)
1. Sign up at [cloud.laravel.com](https://cloud.laravel.com)
2. Create new project from GitHub repository
3. Configure environment variables
4. Deploy with automatic database setup

### Option 2: Heroku
1. Install Heroku CLI
2. Create Heroku app
3. Add MySQL addon
4. Configure buildpack and environment
5. Deploy via Git

### Option 3: DigitalOcean App Platform
1. Connect GitHub repository
2. Configure build and run commands
3. Add managed database
4. Deploy automatically

### Option 4: Vercel + PlanetScale
1. Deploy frontend to Vercel
2. Setup PlanetScale MySQL database
3. Configure environment variables
4. Automatic deployments from GitHub

## Environment Variables for Production

```env
APP_NAME=Munchkly
APP_ENV=production
APP_KEY=base64:your-production-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=munchkly_production
DB_USERNAME=your-db-username
DB_PASSWORD=your-secure-password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@munchkly.app
MAIL_FROM_NAME="Munchkly"
```

## Pre-Deployment Checklist

- [ ] All migrations created and tested
- [ ] Environment variables configured
- [ ] Database seeder ready
- [ ] Assets compiled for production
- [ ] HTTPS/SSL configured
- [ ] Domain configured
- [ ] Error monitoring setup

## Post-Deployment Tasks

1. Run migrations: `php artisan migrate`
2. Seed database: `php artisan db:seed`
3. Cache configuration: `php artisan config:cache`
4. Cache routes: `php artisan route:cache`
5. Cache views: `php artisan view:cache`
6. Test all functionality

## Monitoring

- Set up error monitoring (Sentry/Bugsnag)
- Configure logging
- Set up uptime monitoring
- Enable performance monitoring

---

**Status**: Ready for deployment ✅