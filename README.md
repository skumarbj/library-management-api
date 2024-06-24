# Library Management API

The purpose of this project is to implement a Library Management System API. It contains "Book", "User", and "Borrow" resources. The system manages books, users, and borrowing records.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [API Documentation](#api-documentation)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Prerequisites

List the prerequisites required to run the project.

- PHP >= 8.2
- Composer
- Symfony CLI (optional but recommended)
- Database (MySQL)

## Installation

Step-by-step guide to install the project.

1. Clone the repository / Copy / extract zip file:
    ```bash
    git clone https://github.com/your-username/your-project.git
    cd your-project
    cd library
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Create a `.env` file based on `.env.example` and configure your environment variables:
    ```bash
    cp .env.example .env
    ```

    Mention your DB details in DATABASE_URL, like
    ```
    DATABASE_URL="mysql://root:root_123@127.0.0.1:3306/library"
    ```

4. Set up the database:
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
        and
    import data from library_data.sql file
    ```

## Configuration

Instructions on how to configure the project.

- Update `.env` file with database credentials and other environment-specific settings.
- Configure web server (Apache, Nginx, Symfony CLI) to serve the project.

## Usage

Guide on how to use the project.

1. Start the development server:
    ```bash
    symfony server:start
    ```

2. Access the application in your browser:
    ```
    http://localhost:8000
    ```

3. API Endpoints (if applicable):
    - `GET /api/users`: List all users
    - `POST /api/users`: Create a new user

## Testing

Instructions on how to run tests.

1. Set up the test database:
    ```bash
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:migrations:migrate --env=test
    ```

2. Run tests:
    ```bash
    php bin/phpunit tests/UnitTests
    ```

## API Documentation

Guide on accessing API documentation (if applicable).

- The API documentation is generated using Swagger.
- Access the documentation at:
    ```
    http://localhost:8000/api/doc
    ```
- Refer openapi.yaml
- Refer Library Management System.postman_collection.json

## Contributing

Guidelines for contributing to the project.

1. Fork the repository.
2. Create a new branch:
    ```bash
    git checkout -b feature/your-feature-name
    ```
3. Make your changes and commit them:
    ```bash
    git commit -m "Add some feature"
    ```
4. Push to the branch:
    ```bash
    git push origin feature/your-feature-name
    ```
5. Open a pull request.

## License

Specify the license under which the project is distributed.

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

- Senthilkumar B - (mailto:senthil.bj@gmail.com)
- Project Link: ZIP file
