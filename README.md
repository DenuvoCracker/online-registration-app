# Online Registration Application

A responsive online application/registration form built using **HTML, CSS, JavaScript, jQuery and PHP**. After a valid submission, PHP displays a formatted application summary in the browser.

## Features

- Responsive registration form
- Client-side validation using JavaScript and jQuery
- Server-side validation using PHP
- HTML escaping to safely display submitted values
- Session-based CSRF token validation
- Success page with submitted details
- Print-friendly application summary
- Dockerfile for cloud deployment on Render

> **Demo limitation:** This project displays the submitted information but does not store it in a database or email it. Add a database and appropriate privacy/security controls before using it for real registrations.

## Form fields

- Full name
- Email address
- Phone number
- Date of birth
- Gender
- Course applied for
- Residential address
- Declaration checkbox

## Run locally

### Requirements
- PHP 8.1 or later
- A modern web browser
- Internet connection for the jQuery CDN and Google Fonts

### Steps

1. Extract the project ZIP.
2. Open a terminal in the `online-registration-app` folder.
3. Start PHP's development server:

   ```bash
   php -S localhost:8000
   ```

4. Open `http://localhost:8000` in your browser.
5. Fill in the form and click **Submit application**. A successful submission displays the formatted summary.

Do not open `index.php` by double-clicking it; PHP must run through a PHP-enabled server.

## Deploy on Render (Docker)

1. Create a GitHub repository and upload all files in this project. Keep `Dockerfile` in the repository root.
2. Sign in to [Render](https://render.com/).
3. Select **New + → Web Service** and connect your GitHub repository.
4. Choose **Docker** as the runtime. Leave the Dockerfile path as `./Dockerfile` if it is in the repository root.
5. Choose an available plan and click **Create Web Service**.
6. Wait for the Docker build and deployment to finish. Render will provide a public `onrender.com` URL.
7. Open the URL and test a sample registration.

Render's free web services may spin down after inactivity, so the first request after a quiet period can take longer to load.

## Project structure

```text
online-registration-app/
├── index.php
├── assets/
│   ├── style.css
│   └── script.js
├── Dockerfile
├── .dockerignore
└── README.md
```

## Technologies used

| Technology | Purpose |
|---|---|
| HTML5 | Form structure and page content |
| CSS3 | Responsive layout and styling |
| JavaScript | Browser-side form validation |
| jQuery | DOM handling and validation interactions |
| PHP | Server-side validation and success response |
| Docker | Packaging the PHP/Apache application for cloud hosting |
| Render | Cloud deployment platform |
