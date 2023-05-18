<br/>
<p align="center">
  <h3 align="center">Ai Phrases - Backend (API)</h3>

  <p align="center">
    This is a Api Website to generate random Quote
    <br/>
    <br/>
    <a href="https://iaphrases.live/">View Demo</a>
    .
    <a href="https://github.com/yosbp/ai-phrases">Frontend Code</a>
    .
    <a href="https://iaphrases.live/docs">Api Docs</a>
  </p>
</p>

## About The Project

This project consists of the backend of a phrase API using Laravel. It provides an interface to manage and access a database of phrases from external applications and services. Provide:

- **RESTful Endpoints**: The API is built using a RESTful architecture and follows HTTP standards to provide a set of endpoints for creating, reading, updating, and deleting (CRUD) phrases.
- **Authentication and Authorization**: The API uses authentication and authorization mechanisms to secure the endpoints and ensure that only authorized users can access them. Laravel's token-based authentication system is used to authenticate requests.
- **Data Validation**: Data sent to the API is validated to ensure it meets the specified rules. Laravel's form validation capabilities are utilized to perform this validation and return appropriate errors if the data is invalid.
- **Database**: The API utilizes a database Mysql, to store and manage the phrases.
- **Connect with Third Api**: In this case, use Azure Translation api to fetch, modify data and generate response.

## Technologies Used

- **Laravel**
- **PHP**
- **Mysql**
- **ThunderClient - VSCODE**

## Installation and Configuration

To set up the backend project locally, follow these steps:

1. Clone this repository to your local machine.
2. Ensure you have PHP and Composer installed on your system.
3. Navigate to the project folder.
4. Create a new database on Mysql.
5. Configure the database connection credentials in the .env file of the project.
6. Run the migrations to create the necessary tables in the database:
```sh
php artisan migrate
```
7. Finally, start the development server by running the following command:
```sh
php artisan serve
```
8. The development server will be available at http://localhost:8000.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
