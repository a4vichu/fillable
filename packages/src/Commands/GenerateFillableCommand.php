<?php

namespace Vishnu\FillableGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFillableCommand extends Command
{
    protected $signature = 'fillable:g';

    protected $description = 'Generate fillable array based on migration file for a model';

    public function handle()
    {
        $migrationFile = $this->ask('Enter the migration file path (e.g., database/migrations/2024_04_07_183244_create_users_table.php):');

        if (!File::exists($migrationFile)) {
            $this->error("Migration file not found: $migrationFile");
            return;
        }

        $modelName = $this->ask('Enter the model name (e.g., User):');

        // Parse the migration file to get column names
        $fillable = $this->getFillableColumns($migrationFile);

        // Generate the fillable array code
        $fillableCode = $this->generateFillableCode($fillable);

        // Get the model file path
        $modelFile = $this->getModelFilePath($modelName);

        // Check if the model file exists
        if (!File::exists($modelFile)) {
            $this->error("Model file not found: $modelFile");
            return;
        }

        // Check if the fillable array already exists in the model file
        $existingFillable = $this->getExistingFillable($modelFile);

        if ($existingFillable === false) {
            // If fillable array doesn't exist, append the fillable code to the model file
            $this->appendFillableToModel($modelFile, $fillableCode);
            $this->info("Fillable array generated and added to $modelName model.");
        } else {
            // If fillable array exists, rewrite it only if values are different
            if ($existingFillable !== $fillableCode) {
                $this->rewriteFillableInModel($modelFile, $fillableCode);
                $this->info("Fillable array updated in $modelName model.");
            } else {
                $this->info("Fillable array already exists and matches in $modelName model.");
            }
        }
    }

    private function getFillableColumns($migrationFile)
    {
        $migrationContent = file_get_contents($migrationFile);

        preg_match_all('/\$table->(\w+)\((\'|")(\w+)(\'|").*\);/', $migrationContent, $matches);

        $fillable = [];
        foreach ($matches[3] as $columnName) {
            if ($columnName !== 'id' && $columnName !== 'created_at' && $columnName !== 'updated_at') {
                $fillable[] = $columnName;
            }
        }

        return $fillable;
    }

    private function generateFillableCode($fillable)
    {
        $fillableString = "    protected \$fillable = [\n";
        $fillableString .= "        '" . implode("',\n        '", $fillable) . "',\n";
        $fillableString .= "    ];\n\n";

        return $fillableString;
    }

    private function getModelFilePath($modelName)
    {
        // Construct the model file path based on the model name
        return app_path("Models/{$modelName}.php");
    }

    private function getExistingFillable($modelFile)
    {
        $contents = file_get_contents($modelFile);
        preg_match('/protected \$fillable\s*=\s*\[\s*(.*?)\s*\];/s', $contents, $matches);
        return isset($matches[0]) ? $matches[0] : false;
    }

    private function appendFillableToModel($modelFile, $fillableCode)
    {
        // Read the contents of the model file
        $contents = file_get_contents($modelFile);

        // Find the position to insert the fillable code at the end of the file
        $position = strrpos($contents, '}');

        // Insert the fillable code at the appropriate position
        $newContents = substr_replace($contents, $fillableCode, $position, 0);

        // Write the updated contents back to the model file
        file_put_contents($modelFile, $newContents);
    }

    private function rewriteFillableInModel($modelFile, $fillableCode)
    {
        // Read the contents of the model file
        $contents = file_get_contents($modelFile);

        // Find the position of the existing fillable array
        $startPosition = strpos($contents, 'protected $fillable');
        $endPosition = strpos($contents, ';', $startPosition);

        // Replace the existing fillable array with the new one
        $newContents = substr_replace($contents, $fillableCode, $startPosition, $endPosition - $startPosition + 1);

        // Write the updated contents back to the model file
        file_put_contents($modelFile, $newContents);
    }
}
