<?php

namespace App\Console;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\Schema;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class PopulateDatabaseCommand extends Command
{
    private App $app;
    private \Faker\Generator $faker;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->faker = \Faker\Factory::create('fr_FR');
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }



    protected function execute(InputInterface $input, OutputInterface $output ): int
    {
        $output->writeln('Populate database...');
        // Un nombre aléatoire entre 1 et 10
        $randomNumber = rand(1, 5);
        for ($i = 0; $i < $randomNumber; $i++) {
            // Code à exécuter ds boucle
            $company = $this->createCompany();
        }


        $output->writeln('Database created successfully');
        return 0;
    }

    private function createCompany(): Company
    {
        //compagny avec le modèle éloquant
        $company = new Company();
        $company->name = $this->faker->company;
        $company->save();

        $randomNumber = rand(1, 10);
        for ($i = 0; $i < $randomNumber; $i++) {
            // Code à exécuter ds boucle
            $office = $this->createOffice();
            $company->offices()->attach($office);
        }
        return $company;
    }
    private function createOffice(): Office
    {
        $office = new Office();
        $office->name = $this->faker->name;
        $office->address = $this->faker->address;
        $office->city = $this->faker->city;
        $office->zip_code = $this->faker->postcode;
        $office->country = $this->faker->country;
        $office->email = $this->faker->email;
        $office->phone = $this->faker->phoneNumber;
        $office->save();

        return $office;
    }
}
