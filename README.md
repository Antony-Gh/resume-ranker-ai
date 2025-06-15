# Resume Ranker AI

A Laravel-based application for ranking and analyzing resumes using AI technology.

## Features

- Resume parsing and analysis
- AI-powered ranking of candidates
- User authentication and authorization
- Subscription management
- API access for integrations
- Responsive dashboard

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL 5.7 or higher
- Laravel 11.x

## Installation

### Clone the repository

```bash
git clone https://github.com/yourusername/resume-ranker-ai.git
cd resume-ranker-ai
```

### Install PHP dependencies

```bash
composer install
```

### Install JavaScript dependencies

```bash
npm install
```

### Configure environment variables

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to set up your database connection:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=resume_ranker_ai
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Run migrations and seed the database

```bash
php artisan migrate
php artisan db:seed
```

### Build frontend assets

```bash
npm run dev
```

For production:

```bash
npm run production
```

### Start the development server

```bash
php artisan serve
```

## Docker Setup

You can also use Docker to run the application:

```bash
docker-compose up -d
```

Access the application at http://localhost:8000

## Testing

Run the tests with:

```bash
php artisan test
```

For coverage report:

```bash
php artisan test --coverage
```

## API Documentation

API documentation is available at `/api/documentation` after running:

```bash
php artisan l5-swagger:generate
```

## Directory Structure

- `app/` - Application code
  - `Http/Controllers/` - Controllers
  - `Models/` - Eloquent models
  - `Services/` - Service classes
  - `Repositories/` - Repository pattern implementations
  - `Exceptions/` - Custom exceptions
- `config/` - Configuration files
- `database/` - Migrations and seeders
- `resources/` - Views, language files, and uncompiled assets
- `routes/` - Route definitions
- `tests/` - Test cases

## Architecture

The application follows a repository pattern with service layers:

1. Controllers handle HTTP requests and delegate to services
2. Services contain business logic and use repositories for data access
3. Repositories abstract the data access layer
4. Models represent database tables

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, email support@resumeranker.com or open an issue on GitHub. 
