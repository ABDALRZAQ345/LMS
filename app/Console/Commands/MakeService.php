<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name : The name of the service (can include subdirectories like Admin/UserService)}';

    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $serviceName = $this->getServiceName($name);
        $path = $this->getPath($serviceName);

        if (File::exists($path)) {
            $this->error("Service {$serviceName} already exists!");
            return;
        }

        $this->makeDirectory($path);
        File::put($path, $this->buildClass($serviceName));

        $this->info("Service {$serviceName} created successfully.");
    }

    protected function getServiceName($name)
    {
        $name = str_replace('/', '\\', $name);
        return str_ends_with($name, 'Service') ? $name : $name . 'Service';
    }

    protected function getPath($name)
    {
        $name = str_replace('\\', '/', $name);
        return app_path('Services/' . $name . '.php');
    }

    protected function makeDirectory($path)
    {
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }
    }

    protected function buildClass($name)
    {
        $namespace = 'App\\Services';


        if (str_contains($name, '\\')) {
            $subNamespace = str_replace(class_basename($name), '', $name);
            $subNamespace = trim($subNamespace, '\\');
            $namespace .= '\\' . $subNamespace;
        }

        $className = class_basename($name);

        return "<?php\n\nnamespace {$namespace};\n\nclass {$className}\n{\n    // Add your service methods here\n}\n";
    }
}
