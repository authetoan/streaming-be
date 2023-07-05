# Streaming Backend Application

This is a streaming backend application built using PHP 8.2 and several key packages. It provides a robust foundation for building a streaming service, handling routing, dependency injection, object-relational mapping, and OAuth2 authentication.

## Packages Used

The following packages are utilized in this application:

- **Slim Framework**: A lightweight PHP framework for routing and handling HTTP requests.
- **PHPDI**: A dependency injection container that manages the creation and resolution of objects and their dependencies.
- **Doctrine**: An object-relational mapping (ORM) library that simplifies database access and management.
- **OAuth2 PHP League Package**: A comprehensive PHP library for implementing OAuth2 authentication and authorization.

## Features

The application currently includes the following features:

- Routing: The Slim framework is used to define and handle routes for different endpoints.
- Dependency Injection: PHPDI is utilized for managing the creation and injection of dependencies throughout the application.
- Object-Relational Mapping: Doctrine simplifies database access by providing an ORM layer, allowing you to work with objects instead of raw SQL queries.
- OAuth2 Authentication: The OAuth2 PHP League Package is integrated to handle authentication and authorization for the streaming backend.

## Missing Features

While the application includes the core business logic for a streaming backend service, there are a few missing components:

- **Chat Room**: A chat room functionality can be implemented using PHPReact for handling socket-based communication between users.
- **Event-Driven Architecture**: Adding event-driven capabilities can enhance the application's scalability and responsiveness by allowing asynchronous processing and decoupling of components.
- **Unit Testing / Feature Testing**: Although not implemented in the skeleton, unit tests and feature tests are essential for ensuring the correctness and reliability of the application. Given more time, it is recommended to include thorough test coverage.
- **Role/Permission**: Manage and define user roles and corresponding permissions for different levels of access control within the application.
## Getting Started

To get started with the streaming backend application, follow these steps:

1. Clone the repository: `git clone <repository-url>`
2. Set the permission for `start.sh` - `chmod +x start.sh`
3. Run `start.sh` to start the application `./start.sh`

## Contributing

Contributions to the project are welcome! If you find any issues or have suggestions for improvements, feel free to open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use, modify, and distribute the code as per the terms of the license.

## Acknowledgements

This streaming backend application was developed using the following libraries and frameworks:

- [Slim Framework](https://www.slimframework.com/)
- [PHPDI](https://php-di.org/)
- [Doctrine](https://www.doctrine-project.org/)
- [OAuth2 PHP League Package](https://oauth2.thephpleague.com/)

A special thanks to the authors and maintainers of these open-source projects for their contributions to the development community.