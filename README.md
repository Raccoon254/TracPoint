# TrackPoint - Asset Management System

TrackPoint is a modern **Asset Management System** built with Laravel, designed to help organizations efficiently manage, track, and audit their assets. With features like barcode integration, asset auditing, and detailed reporting, TrackPoint simplifies asset management for businesses of all sizes.

---

## Table of Contents
1. [Features](#features)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
5. [Testing](#testing)
6. [Contributing](#contributing)

---

## Features

- **Asset Management**: Add, update, and delete assets with ease.
- **Barcode Integration**: Scan barcodes to add or retrieve asset details.
- **Auditing**: Perform audits to track asset condition, status, and value.
- **Reporting**: Generate detailed reports for assets, audits, and more.
- **User Roles**: Admin, Auditor, and Regular User roles with role-based access control.
- **Modern UI**: Built with Livewire, Alpine.js, and Tailwind CSS for a dynamic and responsive interface.

---

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or PostgreSQL

### Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/raccoon254/TrackPoint.git
   cd TrackPoint
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**:
   ```bash
   npm install
   ```

4. **Build Assets**:
   ```bash
   npm run build
   ```

5. **Set Up Environment File**:
    - Copy `.env.example` to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Update the `.env` file with your database credentials:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=trackpoint
      DB_USERNAME=root
      DB_PASSWORD=
      ```

6. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

7. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

8. **Start the Development Server**:
   ```bash
   php artisan serve
   ```

9. **Access the Application**:
   Open your browser and navigate to `http://localhost:8000`.

---

## Configuration

### Authentication
TrackPoint uses Laravel Breeze for authentication. You can customize the authentication views in the `resources/views/auth` directory.

### Barcode Scanning
Barcode scanning is implemented using JavaScript libraries like `QuaggaJS` or `ZXing`. Configure the barcode scanner in the `resources/js/components/BarcodeScanner.vue` file.

### Reporting
TrackPoint uses `Laravel Excel` for generating reports. You can customize the reports in the `app/Exports` directory.

---

## Usage

### Admin User
- **Manage Assets**: Add, update, and delete assets.
- **Manage Users**: Add, edit, and delete users.
- **Generate Reports**: Export asset lists, audit logs, and condition reports.

### Auditor User
- **Perform Audits**: Scan barcodes and update asset condition and status.
- **Generate Reports**: Export audit logs and condition reports.

### Regular User
- **View Assets**: Search and view asset details.
- **Generate Reports**: Export asset lists and condition reports.

---

## Testing

TrackPoint uses the **Pest** testing framework for writing tests. To run the tests, use the following command:

```bash
php artisan test
```

### Writing Tests
- Tests are located in the `tests` directory.
- You can write feature tests for asset management, auditing, and reporting.

---

## Contributing

We welcome contributions! If you'd like to contribute to TrackPoint, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes.
4. Push your branch and submit a pull request.

---

## Acknowledgments

- [Laravel](https://laravel.com) for the amazing PHP framework.
- [Livewire](https://laravel-livewire.com) for dynamic frontend interactions.
- [Pest](https://pestphp.com) for the testing framework.
- [Tailwind CSS](https://tailwindcss.com) for the utility-first CSS framework.

---

## Support

For support or questions, please open an issue on the [GitHub repository](https://github.com/raccoon254/TrackPoint/issues).

---

**TrackPoint** - Simplify Asset Management. 🚀
