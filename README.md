# Todo-List Application

This project is a Todo-List application developed using plain PHP for the backend and VueJS for the frontend. The application is designed to manage tasks efficiently, providing features to add, edit, and delete tasks with a user-friendly interface.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Database Setup](#database-setup)


## Features

- Add, edit, and delete tasks
- RESTful API for task management
- Real-time UI updates
- Secure and optimized backend
- Minimalistic and intuitive frontend design

## Technologies Used

### Backend
- PHP (Object-Oriented Programming)
- SQL (using PDO for database interactions)

### Frontend
- VueJS

### DevOps
- Docker (for containerization)

## Prerequisites

- [Docker](https://www.docker.com/get-started) installed on your machine

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/PetruSova2004/Selftalk-TodoList.git
    cd Selftalk-TodoList
    ```

2. **Build and start the Docker containers:**
    ```bash
    docker-compose up --build
    ```

3. **Access the backend container:**
    ```bash
    docker-compose exec backend bash
    ```

## Usage

1. **Run the database migrations and seeders:**
    ```bash
    php database/migrations/2024_06_13_120000_create_tasks_table.php
    php database/seeds/TaskSeeder.php
    ```

2. **Access the application:**
    - The frontend should be accessible at `http://localhost:8080`
    - The backend API should be accessible at `http://localhost:8000`

## Database Setup

The application uses SQL for data storage, employing PHP Data Objects (PDO) to ensure secure database interactions.
