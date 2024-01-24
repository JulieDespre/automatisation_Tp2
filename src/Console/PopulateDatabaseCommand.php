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
        // Un nombre aléatoire entre 1 et 5
        $randomNumber = rand(1, 1);
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
        $company->name = $this->faker->name;
        $company->id = $this->faker->numberBetween(1, 20);
        $company->save();

        // Un nombre aléatoire entre 1 et 10
        $randomNumber = rand(1, 1);
        for ($i = 0; $i < $randomNumber; $i++) {
            // Code à exécuter ds boucle
            $office = $this->createOffice($company);
            $company->offices()->save($office);
        }
        $company->headOffice($office);
        $company->save();
        return $company;
    }
    private function createOffice(Company $company): Office
    {
        $office = new Office();

        $office->id = $this->faker->numberBetween(1, 100);
        $office->name = $this->faker->name;
        $office->address = $this->faker->address;
        $office->city = $this->faker->city;
        $office->zip_code = $this->faker->postcode;
        $office->country = $this->faker->country;
        $office->email = $this->faker->email;
        $office->phone = $this->faker->phoneNumber;
        $office->company()->associate($company);
        $office->save();

        // Un nombre aléatoire entre 1 et 20
        $randomNumber = rand(1, 1);
        for ($i = 0; $i < $randomNumber; $i++) {
            // Code à exécuter ds boucle
            $employee = $this->createEmployee($office);
            $office->employees()->save($employee);
        }

        return $office;
    }

    private function createEmployee(Office $office): Employee
    {
        $employee = new Employee();
        $employee->id = $this->faker->numberBetween(1, 100);
        $employee->first_name = $this->faker->firstName;
        $employee->last_name = $this->faker->lastName;
        $employee->email = $this->faker->email;
        $employee->phone = $this->faker->phoneNumber;
        $employee->job_title = $this->faker->jobTitle;
        $employee->office()->associate($office);
        $employee->save();

        return $employee;
    }
}
