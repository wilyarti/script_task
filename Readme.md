# User Uploader
 [![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)

User Uploader is a simple PHP script for uploading users from a CSV file into a database.

This script performs basic text processing on the user's names and checks if the email is valid.

The database name, table, host, username and password are configurable on the command line.

## Getting Started

In order for this script to work your CSV file will have to be in the following format:

```
name,surname,email	
joe, blogs, joe@mail.com
```

This first line of the CSV file will be ignored and should not contain data other than column names.

You will also need a computer capable of running PHP (such as Linux, *BSD or Mac).

### Prerequisites

This software requires PHP > 7.2.x and MySQL > 5.7. You will also need to install PHP MySQLi.

On Ubuntu you can install it using the following command:
> sudo apt install php-mysql

### Installing
To install simply copy the file into a directory that is in your path (~/bin) and run:
> user_upload.php
or
> /path/to/file/user_upload.php

You will need to make the file executable if you want to run it directly.
> chmod +x user_upload.php

Alternatively run using your PHP interpreter:
> php user_upload.php

You will need to specify the command line directives :
- --file [csv file name] – this is the name of the CSV to be parsed
- --create_table – this will cause the MySQL users table to be built (and no further
- action will be taken)
- --dry_run – this will be used with the --file directive in case we want to run the
script but not insert into the DB. All other functions will be executed, but the
database won't be altered
- -u – MySQL username
- -p – MySQL password
- -h – MySQL host
- -h – MySQL database 
- -t – MySQL table 
- --help – which will output the above list of directives with details.

For example:
```
> php user_upload.php --file users.csv -u admin -p password -h localhost -d users -t userlist
```

The program will emit errors to standard output.

## Issues
Earlier versions of the PHP MySQLi plugin do not support "caching_sha2_password". 

To overcome this either change the authentication method in your MySQL configuration file or add 
as user with native password authentication.
> CREATE USER 'nativeuser'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';


## Built With

* [PHP](https://php.net) - general purpose programming language
* [PHP Storm](https://www.jetbrains.com/phpstorm/) - Integrated Development Environment

## Contributing

Open a pull request. I am open to improvements.

## Versioning

I use [GitHub](https://github.com/) for versioning.

## Authors

* **Wilyarti Howard** - *Initial work* - [Wilyarti](http://wilyarti.com/)

## License

This project is licensed under the BSD License - see the [License.md](License.md) file for details

