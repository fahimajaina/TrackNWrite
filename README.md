# TrackNWrite

## Purpose
TrackNWrite is a web-based application designed to help users efficiently manage their daily tasks, notes, and expenses. The platform offers a seamless experience for users to track their productivity and financial activities while ensuring data security through user authentication. The system supports multiple users, allowing them to register, log in, and recover their accounts when needed.

## Technologies Used
### Frontend:
- HTML
- CSS
- Bootstrap

### Backend:
- PHP
- MySQL

## Features
### To-Do List
- Add new tasks with descriptions.
- Mark tasks as completed.
- Delete tasks when no longer needed.

### Notes Management
- Create and save notes with titles.
- Edit notes to update information.
- Delete notes when no longer needed.

### Expense Tracking
- Add expenses with a name, amount, and date.
- Edit or delete recorded expenses.
- View an expense overview displaying:
  - Total yearly expenses
  - Total monthly expenses
  - Total weekly expenses
  - Breakdown by month and year

### User Authentication
- **Registration**: Users can sign up with their credentials.
- **Login**: Secure login to access personal data.
- **Forgot Password**: Recover accounts via a password reset feature.

## Project Structure

/TrackNWrite
├── /frontend               # Frontend files (HTML, CSS, JavaScript)
├── /backend                # Backend files (PHP, MySQL)
├── /database               # MySQL database setup files
├── index.php               # Main entry point (Homepage)
└── README.md               # Project documentation


## How to Use
### Frontend Setup
1. Clone the repository:
   
   git clone https://github.com/fahimajaina/TrackNWrite.git
   
2. Navigate to the frontend directory:
   
   cd frontend

3. Open the index.html file in a web browser to start using the system.

### Backend Setup
1. Navigate to the backend directory:
   
   cd backend
  
2. Import the database:
   - Locate the MySQL database file (`user_dashboard.sql`).
   - Import it into your MySQL server.
3. Configure the database connection:
   - Update the `db.php` file with your database credentials (host, username, password, and database name).
4. Run the PHP backend:
   
   php -S localhost:8000
  
5. Access the system via `http://localhost:8000` in your browser.

## How It Works
### User Flow
1. **Register/Login**: Users create an account or log in to access their personal dashboard.
2. **Manage Tasks**: Users add, mark, and delete tasks from the to-do list.
3. **Take Notes**: Users create, edit, and delete notes for personal use.
4. **Track Expenses**: Users add financial records and monitor their expenses with a breakdown by time period.
5. **User Authentication**: Secure access to personal data with login and password recovery options.

## Future Enhancements
- Implement categories for tasks and expenses.
- Add reminders and notifications.
- Introduce data export (CSV, PDF) for expense reports.
- Improve UI with additional themes and dark mode support.

TrackNWrite is designed to simplify productivity and financial tracking in one convenient platform. Users can manage their tasks, notes, and expenses efficiently while ensuring data security and easy access. 

