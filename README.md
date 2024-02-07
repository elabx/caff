# CAF Website

## Introduction
This README provides guidelines on setting up and running the development environment for CAF Website using DDEV. Ensure you have Docker (or Colima if you're using a Mac) installed before proceeding.

## Prerequisites
- Docker: [Install Docker](https://www.docker.com/products/docker-desktop)
  - For Mac users, Colima can be a better alternative to Docker. [Install Colima](https://github.com/abiosoft/colima)
- DDEV: Make sure DDEV is installed in your system. [Install DDEV](https://ddev.readthedocs.io/en/stable/)

## Setup

### Step 1: Verify Docker/Colima Installation
Ensure that Docker or Colima is running on your machine. You can verify this by running:

For Docker:
```bash
docker --version
```

For Colima:
```bash
colima version
```

## Step 2: Step 2: Start DDEV


Importing the database dump from the live site

```
ddev import-db --src=/somer/path/your-dump-file.sql.gz
```

## Usage
After completing the setup, your development environment is ready. You can now access your project and start development.

## Additional Commands
- `ddev stop`: Stops the DDEV environment.
- `ddev restart`: Restarts the DDEV environment.
- `ddev describe`: Shows information about the running DDEV environment.
