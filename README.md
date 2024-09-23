# NiceCRM

NiceCRM is a portfolio project showcasing basic functionality for managing content. This project includes additional features like email notifications and multi-language support.

The original idea for this project was inspired by [Laravel Daily's support ticket system project](https://laraveldaily.com/post/demo-project-laravel-support-ticket-system).

**Features:**
- Described in [Laravel Daily's support ticket system project](https://laraveldaily.com/post/demo-project-laravel-support-ticket-system).

## Testing Email Notifications

To test email notifications, follow these steps:

1. **Register and Set Up Mailtrap:**
   - Go to [Mailtrap](https://mailtrap.io) and register or log in (you can use your Gmail account for instant login).
   - In the left sidebar, go to **Email Testing** > **Inboxes**.
   - Create a new project and an inbox, then open the inbox.

2. **Configure Mailtrap Settings:**
   - In the inbox, go to the **Code Samples** section and select **PHP** > **Laravel 9+**.
   - Copy the provided code sample and update the `.env` file in the `NiceCRM` directory with the settings from Mailtrap. The configuration should look like:
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=sandbox.smtp.mailtrap.io
     MAIL_PORT=2525
     MAIL_USERNAME=<your_username>
     MAIL_PASSWORD=<your_password>
     ```
     Replace `<your_username>` and `<your_password>` with the actual values from Mailtrap.

3. **Verify Email Notification:**
   - Create a new ticket in the application and check the Mailtrap inbox for the notification email.

## Getting Started on Windows

### Prerequisites

1. **Download and Install Herd:**
   - Download the latest free version of Herd from the [Herd website](https://herd.laravel.com/windows) and install it.

2. **Download and Install MySQL:**
   - Download `mysql-installer-community-8.0.39.0.msi` from the [MySQL website](https://dev.mysql.com/downloads/installer/).
   - Choose the **Full** setup type and follow the installation wizard.
   - During the installation, set the MySQL Root Password to "root". Update the `DB_PASSWORD` in the `.env` file if you use a different password.

### Project Setup

1. **Clone the Repository:**
   - Select a directory for your Laravel projects.
   - Run:
     ```bash
     laravel new tickets
     ```
   - Select the Breeze starter kit with the following options:
     - Blade Breeze stack (Blade with Alpine)
     - No dark mode support
     - Pest testing framework
     - Do not initialize a Git repository
     - Select MySQL for the database
     - Do not run default database migrations

2. **Set Up Git:**
   - In the `tickets` directory, run:
     ```bash
     git init
     git remote add origin https://github.com/vernersmednis/tickets.git
     git fetch origin
     git checkout main -f
     git fetch
     git reset --hard origin/main
     ```

3. **Set Up the Project Server in Herd:**
   - In the PHP tab, select version 8.3 (8.3.3).
   - In the General tab, add the path to the `tickets-parent-directory`.

4. **Set Up the Database:**
   - Ensure the MySQL bin directory is in your system's `PATH` environment variable:
     - Navigate to **Search** > **Edit the system environment variables** > **Environment Variables** > **User variables for `your_username`** > **Path** > **Edit**.
     - Confirm that `C:\Program Files\MySQL\MySQL Server 8.0\bin` is listed.
   - Run `net start MySQL80` in Command Prompt (opened with **Run as administrator**).
   - Open **Local instance MYSQL80** in MySQL Workbench.
   - Run the following query to create the database:
     ```sql
     CREATE DATABASE nicecrm;
     ```
   - In the `tickets` directory, run:
     ```bash
     php artisan migrate
     ```
     or
     ```bash
     php artisan migrate:fresh
     ```
     then:
     ```bash
     php artisan db:seed
     ```

5. **Install Dependencies and Run the Application:**
   - Run:
     ```bash
     npm install
     composer require yajra/laravel-datatables-oracle
     npm run dev
     ```
   - Open your browser and navigate to `http://tickets.test` to explore the application. Ensure that Herd and MySQL80 are running.

---

Feel free to modify the `.env` file, database settings, or any configurations as needed for your environment.
