# Real Estate Agency Portal (PHP + MySQL)

A role-based web application where real estate agents list properties and buyers or renters browse listings and send inquiries. Built for CIS344 (Database Design) at Lehman College, Spring 2026.

**Screenshots of the app and the database (tables, view, stored procedures) are in the project report: [`CIS344_Finale_Report.pdf`](CIS344_Finale_Report.pdf).**

## Features
- **Registration and login:** passwords are hashed with `password_hash()` and verified with `password_verify()`; logged-in users are tracked with PHP sessions.
- **Role-based access control:** `requireRole()` restricts pages by user type.
  - **Agent:** add property listings.
  - **Buyer / Renter:** browse listings, view property details, and send inquiries about a property.
- **Property listings:** a list page and a detail page, each showing the listing agent.
- **Relational database:** five tables (Users, Properties, Inquiries, Favorites, Transactions) linked by foreign keys, plus stored procedures, a view, and a trigger (see the report).
- **Prepared statements (PDO)** for database queries, and `htmlspecialchars()` on displayed data.

## Tech stack
PHP 8, MySQL / MariaDB, PDO, HTML, CSS, XAMPP for local development.

## Project structure
```
config/      database settings and session start
classes/     Database (PDO connection) and RealEstateDatabase (all queries)
includes/    shared header and footer, plus auth helpers (requireLogin, requireRole)
sql/         database setup scripts
assets/      stylesheet
*.php        pages: index, register, login, dashboard, properties,
             property_details, add_property, submit_inquiry, logout
```

## Run it locally
1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this project folder into `C:\xampp\htdocs\`.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`, choose **Import**, and import the SQL file from the `sql/` folder.
4. Check `config/config.php` (default: host `localhost`, user `root`, empty password, database `real_estate_portal_db`).
5. Open `http://localhost/<your-folder-name>/`, register an agent and a buyer, and try both roles.

## Challenges and what I learned
- **Port conflicts in XAMPP:** Apache and MySQL would not start because other programs were using ports 80 and 3306. I ended the tasks in Task Manager, and stopped an older MySQL Windows service with `net stop MySQL`.
- **Foreign key order:** MySQL rejected a property with an `agentId` that did not exist, which taught me to insert parent rows (Users) before child rows (Properties).
- **Browser caching:** PHP changes did not appear until a hard refresh (Ctrl+F5).

## Known limitations and next steps
- Agents can add listings but cannot yet edit or delete them.
- Users choose their own role at registration; a real system would require agent approval.
- The Favorites and Transactions tables exist, but no pages use them yet.
- Possible security improvements: CSRF tokens and regenerating the session ID at login.

## Documentation
The full write-up is in [`CIS344_Finale_Report.pdf`](CIS344_Finale_Report.pdf).

## Documentation
The full write-up is in [`CIS344_Finale_Report.pdf`](CIS344_Finale_Report.pdf).
