# User Uploader

User Uploader is a simple PHP script for uploading users from a CSV file into a database.

## Getting Started

In order for this script to work your CSV file will have to be in the following format:
name,surname,email	
joe, blogs, joe@mail.com
This first line of the CSV file will be ignored and should not contain data other than column names.

You will also need a computer capable of running PHP (such as Linux, *BSD or Mac).
### Prerequisites

This software requires PHP > 7.2.x and MySQL > 5.7.

### Installing
To install simply copy into a directory that is in your path (~/bin) and run:
> user_upload.php

Or alternatively run from a directory like so:
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
php user_upload.php --file users.csv -u admin -p password -h localhost -d users -t userlist
```

The program will emit errors to standard output.

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

This project is licensed under the BSD License - see the [LICENSE.md](LICENSE.md) file for details

