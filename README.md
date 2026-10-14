# Noteboat

A small personal task manager built with Laravel, Vue and Inertia.

Users can register, log in with a username and password, create their own lists,
and manage tasks within those lists.

## Features

- Username and password authentication, including remember-me sessions.
- User-specific task lists.
- Create and delete tasks.
- Assign low, medium or high priority when creating a task.
- Mark tasks complete or incomplete.
- Reorder tasks using up/down controls.
- Persistent SQLite storage.
- Responsive interface with validation feedback and empty states.

## Technology

- Laravel 13 and Laravel Fortify.
- Vue 3 with Inertia 3.
- Tailwind CSS 4.
- SQLite.
- Vite+ for frontend development and builds.

Built using the official Laravel Vue starter kit for authentication,
application layouts and shared UI components. The task-list functionality,
ownership checks, validation requests and task components were added for
this project.

Custom task pages and components use JavaScript. Some starter-kit files
and tooling retain TypeScript.

## Requirements

Recommended environment matching development:

- PHP 8.5 with SQLite support (`pdo_sqlite` and `sqlite3`) and the
  extensions required by Composer dependencies.
- Composer 2.
- Node.js 24 and npm.

The commands below assume a Bash terminal, such as Linux, macOS or WSL.

## Installation

Clone this repository and enter its directory:

```bash
git clone <repository-url> noteboat
cd noteboat
```

Install the PHP dependencies and create your local environment file:

```bash
composer install
cp .env.example .env
```

Set the following values in `.env`:

```dotenv
APP_NAME=Noteboat
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
SESSION_DRIVER=database
```

Leave `DB_DATABASE` unset to use Laravel's default SQLite database path.

Create the database, generate the application key and run migrations:

```bash
touch database/database.sqlite
php artisan key:generate
php artisan migrate
```

Install and build frontend dependencies:

```bash
npm ci
npm run build
```

## Running locally

Start Laravel:

```bash
php artisan serve
```

In a second terminal, start the frontend development server:

```bash
npm run dev
```

Open http://localhost:8000 in your browser. If Laravel reports a different
port, use the address printed in the terminal.

Register an account using the Sign up link. No seeded account is required.

Usernames must contain 3–30 lowercase letters, numbers or underscores.

## Using Noteboat

1. Register or log in.
2. Create a named list.
3. Open the list and add tasks with a priority.
4. Use the checkbox to mark a task complete or incomplete.
5. Use the arrows to change its position.
6. Use X to delete a task after confirmation.

Priority and display order are independent. Completing a task leaves it
in its current position.

## Implementation notes

- Users have many lists; lists have many tasks.
- List queries are scoped through the authenticated user.
- Task operations find the task through its owning list.
- Form Request classes validate incoming data.
- Task ordering is saved in a database transaction.
- A composite index on list ID and position supports ordered retrieval.
- TaskItem.vue displays each task and emits events to its parent page.
- The parent page handles requests through Inertia.

## Verification

The following flows were manually checked during development:

- Registration, login and logout.
- Creating lists and tasks.
- Completing and reopening tasks.
- Reordering and deleting tasks.
- Layout at different viewport widths.

The frontend production build was also checked with:

```bash
npm run build
```

No additional automated tests were written for the task-list functionality.
The project includes starter-kit test scaffolding, which has not been
verified against all application changes.

## Scope and limitations

- Priority is selected when creating a task; editing it afterwards is
  not included.
- Editing task titles and renaming/deleting lists are not included.
- Forgotten-password recovery is not enabled.
- Reordering is intended for normal individual use; simultaneous edits
  from multiple tabs are not explicitly coordinated.
