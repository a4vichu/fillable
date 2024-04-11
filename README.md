# Fillable Generator

Fillable Generator is a Laravel package that simplifies the process of generating fillable arrays for Eloquent models based on migration files.

## Installation

You can install the Fillable Generator package via Composer. Run the following command in your Laravel project directory:

```bash
composer require vishnu/fillable-generator
```

## Usage

### Generating Fillable Arrays

To generate fillable arrays for your models, use the `fillable:g` Artisan command provided by the Fillable Generator package.

```bash
php artisan fillable:g
```

Follow the on-screen prompts to enter the path to your migration file and the name of the corresponding model. The command will parse the migration file, extract the column names, and generate a fillable array for the specified model.

### Example

Let's walk through an example of using the Fillable Generator package:

1. Suppose you have a migration file named `create_users_table.php` located in the `database/migrations` directory.

2. Run the `fillable:g` command and enter the path to the migration file when prompted:

   ```bash
   php artisan fillable:g
   ```

   Example prompt:

   ```
   Enter the migration file path (e.g., database/migrations/2024_04_07_183244_create_users_table.php):
   ```

3. Next, enter the name of the corresponding model (e.g., `User`):

   ```
   Enter the model name (e.g., User):
   ```

4. The command will generate a fillable array for the `User` model based on the columns defined in the migration file and update the model file accordingly.

## Support

If you encounter any issues or have questions about using the Fillable Generator package, please [open an issue](https://github.com/vendor/fillable-generator/issues) on GitHub.

## License

The Fillable Generator package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
