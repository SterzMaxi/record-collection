# Record Digging Project

## Overview

This project is a web application that allows users to log in using Keycloak, search for records, and perform other authenticated actions. The backend is built with Symfony, running in a Docker container, while the frontend uses Vue.js with Vite for a modern and fast development experience.

## Features

- **User Authentication**: Integrated with Keycloak for secure login and logout.
- **Responsive Design**: Built with BootstrapVue to ensure a seamless experience on all devices.
- **Modern Frontend**: Developed using Vue 3 with Vite for fast hot module replacement (HMR) and a smooth development workflow.
- **Backend**: Powered by Symfony, providing a robust and scalable backend solution.

## Technologies Used

- **Frontend**:
  - Vue 3
  - Vite
  - BootstrapVue
- **Backend**:
  - Symfony
  - Docker
- **Authentication**:
  - Keycloak

## Getting Started

### Prerequisites

- Docker and Docker Compose installed on your machine.
- Node.js and npm (or yarn) installed for frontend development.

### Setup

#### Backend (Symfony in Docker)

1. Clone the repository:

   git clone https://github.com/yourusername/record-digging.git
   cd record-digging


Build and start the Docker containers:


docker-compose up --build
The Symfony backend should now be running inside a Docker container.

Frontend (Vue with Vite)
Navigate to the frontend directory:


cd frontend

npm run dev
# or
yarn dev
The frontend should now be running at http://localhost:3000.

Configuration
Keycloak
Ensure that Keycloak is set up and running.

Update the KeycloakPlugin.js file with your Keycloak configuration:

javascript

const keycloakInitOptions = {
    url: 'YOUR_KEYCLOAK_URL',
    clientId: 'YOUR_CLIENT_ID',
    realm: 'YOUR_REALM',
    onLoad: 'check-sso'
}
Environment Variables
Create a .env file in the root directory of your project.
Add the necessary environment variables for Symfony and Vite.
Example .env file:
makefile

# Symfony
APP_ENV=dev
APP_SECRET=your_secret
DATABASE_URL=mysql://user:password@db:3306/database

# Vite
VITE_KEYCLOAK_URL=http://localhost:8080/auth
VITE_KEYCLOAK_CLIENT_ID=your_client_id
VITE_KEYCLOAK_REALM=your_realm
Usage
Development
Run docker-compose up to start the backend in a Docker container.
Run npm run dev or yarn dev in the frontend directory to start the frontend development server.
Production
Build the frontend for production:



npm run build
# or
yarn build
Serve the built frontend files and configure your server to serve the Symfony backend.

License
This project is licensed under the MIT License - see the LICENSE file for details.

Acknowledgements
Vue.js
Vite
BootstrapVue
Symfony
Keycloak
Docker
