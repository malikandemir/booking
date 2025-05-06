# Car Appointment System

A web-based car booking system built with PHP and MySQL that allows employees to request and manage car appointments with an approval workflow.

## Features

- Booking system with approval workflow
- Availability calendar
- User roles and permissions (Requesters, Approvers, Admins)
- Notification system
- Usage history tracking
- Conflict management
- Mobile-friendly interface

## Docker Setup (Recommended)

1. Make sure you have Docker and Docker Compose installed on your system
2. Clone this repository
3. Run the initialization script:
   ```bash
   ./docker-compose-init.sh
   ```
4. The application will be available at http://localhost:8013

Services:
- Web: http://localhost:8013
- MySQL: localhost:3313
  - Database: carappointment
  - Username: carapp_user
  - Password: carapp_password
  - Root Password: root_password

Default login credentials:
- Admin: admin@example.com / password
- User: user@example.com / password

## Manual Setup

### Requirements

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & npm

### Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database settings
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `npm install && npm run dev`

## Usage

1. Log in using the provided credentials
2. Navigate to the booking calendar
3. Create a new booking request by specifying:
   - Date and time
   - Purpose of use
   - Expected duration
4. Admins can approve or reject booking requests
5. Users will receive notifications about their booking status

## License

MIT License
