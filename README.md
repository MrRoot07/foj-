# POS Malaysia - Secure Courier Management System

This project is a secure web-based courier management system for POS Malaysia. It integrates key security measures to ensure the confidentiality, integrity, and availability of customer data while streamlining courier services, such as pickup requests, order management, tracking, and reporting.

---

## **Features**

- **User login:** Customers can request package pickups and track their orders.
- **Employee login:** Allows employees to manage and process pickup requests.
- **Admin login:** Admins oversee system-wide operations and user roles.
- **Order management:** Users can create, update, and monitor pickup and delivery requests.
- **Tracking system:** Enables real-time status updates on package delivery.
- **Reporting and analysis:** Users can generate reports to monitor system performance.

---

## **Security Features**

This project includes essential security features to mitigate threats:
- **CAPTCHA:** Prevents bot-driven attacks.
- **Session Management:** Ensures sessions are securely maintained with automatic timeouts.
- **OTP Authentication:** Verifies user identity during registration and login.
- **Password Hashing:** Ensures passwords are stored securely using bcrypt.
- **Input Validation:** Prevents SQL injection and cross-site scripting (XSS) attacks.
- **Continuous Monitoring:** Monitors suspicious activity with system logs.

---

## **Technologies Used**

- **Frontend:** HTML, CSS, JavaScript, Bootstrap 5
- **Backend:** PHP
- **Database:** MySQL (with phpMyAdmin for management)
- **Security Standards:** OWASP guidelines for web application security

---

## **Getting Started**

To set up the project locally, follow these steps:

1. **Install a web server:** Download and install Apache or Nginx on your system.
2. **Install MySQL and PHPMyAdmin:** Ensure MySQL is installed to manage the database.
3. **Create the Database:**
   - Open PHPMyAdmin and create a new database called **`royal_express_db`**.
