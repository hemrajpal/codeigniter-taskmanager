# TaskManager

A task management application built with **CodeIgniter 4, PHP, and MySQL**. It provides separate admin and member areas for managing tasks, assigning work, and tracking task progress.

> **Status:** Work in progress.

## Features

### Authentication and Security
- User registration and login
- Secure password hashing
- Session-based authentication
- Custom CAPTCHA to help prevent automated form submissions
- Honeypot protection on login and signup forms
- Input validation and role-based access filters
- Login and logout functionality
- Email verification workflow is partially implemented and requires completion

### Admin Dashboard
- Dashboard with user and task statistics
- Create, edit, and delete tasks
- Assign tasks to multiple members
- View task assignees
- Filter tasks by status
- Paginated task lists

### Member Dashboard
- View assigned task statistics
- View upcoming tasks
- View tasks assigned to the logged-in member
- Filter tasks by status
- Update assigned task status

### Task Management
- Task title and description
- Due dates
- Task statuses:
  - Pending
  - In Progress
  - Completed
- Overdue task statistics
- Database transactions for task creation and assignment updates

## Tech Stack

- **Backend:** PHP 8.2+, CodeIgniter 4
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap 5
- **Architecture:** MVC with a repository layer for task operations
- **Authentication:** Sessions and custom filters
- **Validation:** CodeIgniter validation rules

## Requirements

Before installing, ensure you have:

- PHP 8.2 or later
- Composer
- MySQL or MariaDB
- Apache, XAMPP, or another compatible PHP development environment

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/hemrajpal/codeignitor-taskmanager.git
cd codeignitor-taskmanager
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure the environment

Copy the environment file.

**Windows:**
```bat
copy env .env
```

**Linux/macOS:**
```bash
cp env .env
```

Open `.env` and configure your database settings, including the database name, username, and password. Configure email settings if you are testing the email-verification workflow.

### 4. Create the database

Create a MySQL database for the application, then update `.env` with the matching database configuration.

### 5. Run migrations

```bash
php spark migrate
```

### 6. Create the admin account

```bash
php spark db:seed AdminSeeder
```

The current seeder creates these default credentials:

- **Email:** `admin@example.com`
- **Password:** `12345678`

Change the default password and remove or secure the development seeder before deploying the application publicly.

### 7. Start the application

```bash
php spark serve
```

Open [http://localhost:8080](http://localhost:8080) in your browser.

## Project Structure

```text
app/
├── Controllers/    # Authentication, admin and member controllers
├── Filters/        # Authentication, admin, guest and CAPTCHA filters
├── Models/         # User, task and assignment models
├── Repositories/   # Task-related database operations
├── Libraries/      # Custom CAPTCHA and supporting services
├── Database/
│   ├── Migrations/ # Database schema
│   └── Seeds/      # Initial admin account
└── Views/          # Authentication, dashboard and task pages
```

## Current Limitations

- Email verification and its email-sending workflow need to be fully enabled.
- Admin approval and account-status handling need further implementation.
- Review dashboard user-status statistics to ensure they match the application's current status values.

## Author

**Hemraj Pal**

- GitHub: [github.com/hemrajpal](https://github.com/hemrajpal)
- Project: [CodeIgniter TaskManager](https://github.com/hemrajpal/codeignitor-taskmanager)

