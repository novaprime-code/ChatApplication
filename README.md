# Laravel Sail Setup Guide

This guide will help you set up a Laravel project using Laravel Sail with Docker. Laravel Sail provides a simple command-line interface for interacting with Laravel's default Docker development environment.

## Prerequisites

Before starting, ensure you have the following installed on your system:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- WSL 2 (Windows only) - see instructions [here](https://docs.microsoft.com/en-us/windows/wsl/install-win10)
- [Composer](https://getcomposer.org/download/)

## Installation Steps

1. Clone the repository:

    ```bash
    git clone <repository-url>
    cd <project-directory>
    ```

2. Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

3. Install Composer dependencies:
    For Windows, use the following command:

    ```bash
    docker run --rm --interactive --tty -v %cd%:/app composer install
    ```

    For macOS and Linux, use the following command:

    ```bash
    docker run --rm --interactive --tty -v $PWD:/app composer install
    ```

    ### (Optional) Alias for Sail

    To simplify Sail commands for bash, you can create an alias

    ```bash
    alias sail='bash vendor/bin/sail'
    ```

    ### If alias is not setup than

    ```bash
    ./vendor/bin/sail artisan ..............
    ```

5. Update your `.env` file with the following configuration if not already present:

    ```dotenv
    npm install
    ```

    ```dotenv
    npm run dev
    ```

6. Start Laravel Sail:

    - If you have set up the alias:

    ```bash
        sail up -d
    ```

7. Generate Table and Seed Data:

    - If you have set up the alias:

    ```bash
    sail artisan migrate --seed

    ```

8. Open your browser and go to

- **Application**: Access Application at [http://localhost](http://localhost).

## Setting Permissions

If you encounter permission errors, follow these steps:

1. Identify your user:

    ```bash
    whoami
    ```

2. Add your user to the Docker group:

    ```bash
    usermod -a -G docker <your-username>
    ```

3. If still problem occurs than restart you device.

## Additional Details

### Access Services and Documentation Locally

- **Application**: Access application at at [http://localhost](http://localhost).
- **Mailpit**: Access Mailpit at [http://localhost:8025/](http://localhost:8025/).
- **Minio**: Access Minio at [http://localhost:8900](http://localhost:8900) (username:  `sail`, password: `password`).

## Docker/Sail Commands

### Note: if you have set up the alias, you can use `sail` otherwise use `./vendor/bin/sail` in place of `sail`

- Check running containers:

    ```bash
    docker-compose ps
    ```

- Stop Sail containers:

    ```bash
    sail down
    ```

- Restart Sail containers:

    ```bash
    sail down
    sail up
    ```

- Remove Sail containers:

    ```bash
    sail down -v
    ```

- Run Composer commands:

    ```bash
    sail composer <command>
    ```

- Run Artisan commands:

    ```bash
    sail artisan <command>
    ```

## Support

If you encounter any issues, refer to the [official Laravel Sail documentation](https://laravel.com/docs/sail) or [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel-sail).
