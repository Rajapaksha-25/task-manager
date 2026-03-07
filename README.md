# TaskFlow

A task management REST API built with Laravel and a browser-based frontend.

## Features

- User registration and login with Laravel Sanctum token authentication
- Full task CRUD — create, read, update, delete
- Soft deletes with trash, restore, and permanent delete
- Filter tasks by status and priority
- Search tasks by title
- Sort by created date or due date
- Overdue task highlighting
- Task stats overview
- Responsive frontend built with plain HTML, CSS, and JavaScript

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL 8.0 or higher

## Setup

### 1. Clone the repository

git clone https://github.com/Rajapaksha-25/task-manager.git
cd task-manager

### 2. Install PHP dependencies

composer install

### 3. Install Node dependencies

npm install

### 4. Create environment file

cp .env.example .env

### 5. Generate application key

php artisan key:generate

### 6. Configure your database

Open .env and fill in your MySQL credentials:

DB_DATABASE=taskapi
DB_USERNAME=root
DB_PASSWORD=your_password

### 7. Run migrations

php artisan migrate

### 8. Start the development servers

In one terminal:
php artisan serve

In another terminal:
npm run dev

### 9. Open the app

http://127.0.0.1:8000

## API Endpoints

### Auth

Method   Endpoint            Description                   Auth required 

POST    /api/register      Register a new account         No auth required
POST    /api/login         Log in and get a token         No auth required
POST    /api/logout        Log out and revoke token       Requires token
GET     /api/user          Get the logged in user         Requires token


### Tasks

GET    /api/tasks                   Get all your tasks             Yes
POST   /api/tasks                   Create a new task              Yes
GET    /api/tasks/{id}              Get a single task              Yes
PUT    /api/tasks/{id}              Update a task                  Yes
DELETE /api/tasks/{id}              Soft delete                    Yes
GET    /api/tasks/trashed           Get all trashed tasks          Yes
PATCH  /api/tasks/{id}/restore      Restore a task from trash      Yes
DELETE /api/tasks/{id}/force        Permanently delete a task      Yes

### Query Parameters for GET /api/tasks

 Parameter    Type               Description                              
 status      string      Filter by pending, in_progress, completed
 priority    string      Filter by low, medium, high              
 search      string      Search by task title                     
 sort_by     string      created_at or due_date                   
 sort_dir    string      asc or desc                              
 per_page    int         Results per page, default 10             
 page        int         Page number                              

## Environment Variables

See .env.example for all required variables and descriptions.