<?php

namespace Fewsh\DomainOriented\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class PublishCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain-oriented:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Domain Oriented structure';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Start publishing Domain Oriented structure...');

        $domainName = $this->ask('✔ Domain Oriented name:');
        $rootDomainDefault = 'src/Domain/';
        $rootDomainDirectoryPath = base_path($rootDomainDefault . $domainName);
        if($filesystem->exists($rootDomainDirectoryPath)) {
            $this->error('Domain Oriented structure already exists!');
            return 1;
        }

        $stubsPath = __DIR__ . '/../Stubs';
        $stubFiles = $filesystem->files($stubsPath);
        $options = [];

        foreach ($stubFiles as $stubFile) {
            if ($stubFile->getExtension() === 'stub') {
                $stubData = Yaml::parseFile($stubFile->getRealPath());

                $key = array_key_first($stubData);
                $name = $stubData[$key]['name'];

                $options[$name] = $this->choice(
                    "Add {$name}? › Yes / No",
                    ['Yes', 'No'],
                    0
                );
            }
        }

        forEach($options as $key => $value) {
            $this->info("✔ Add {$key}? ... $value");
        }

        $filesystem->makeDirectory($rootDomainDirectoryPath, 0777, true);
        foreach ($options as $name => $value) {
            if ($value === 'Yes') {
                $subDirectoryPath = $rootDomainDirectoryPath . '/' . $name;
                $filesystem->makeDirectory($subDirectoryPath, 0777, true);

            }
        }

        $resultString = implode(", ", array_keys(array_filter($options, fn($value) => $value === 'Yes')));

        $this->info("✔ Created domain: {$resultString}");
        $this->info("✔ Created sub domain: {$resultString}");
        $this->info('Publishing Domain Oriented structure completed!');
    }

}
