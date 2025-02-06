<?php

namespace Fewsh\DomainOriented\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeleteDirectoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain-oriented:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the Domain Oriented structure';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Start removing Domain Oriented structure...');

        $domainName = $this->ask('✔ Domain Oriented name to remove:');
        $rootDomainDefault = 'src/Domain/';
        $rootDomainDirectoryPath = base_path($rootDomainDefault . $domainName);

        if (!$filesystem->exists($rootDomainDirectoryPath)) {
            $this->error('Domain Oriented structure does not exist!');
            return 1;
        }

        if ($this->confirm("Are you sure you want to delete '{$domainName}'?", false)) {
            $filesystem->deleteDirectory($rootDomainDirectoryPath);
            $this->info("✔ Removed domain: {$domainName}");
            $this->info('✔ Domain Oriented structure removal completed!');
        } else {
            $this->info('✔ Operation canceled.');
        }
    }
}