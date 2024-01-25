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
        $randomNumber = rand(1, 5);
        for ($i = 0; $i < $randomNumber; $i++) {
            $company = $this->createCompany();

            //créer un nombre aléatoire de bureaux entre 1 et 4
            for ($j = 0; $j < rand(1, 4); $j++) {
                $office = $this->createOffice($company);

                // Si c'est le premier bureau, le définir comme siège social
                if ($j == 0) {
                    $company->head_office_id = $office->id;
                    $company->save();
                }
                //créer un nombre aléatoire d'employés entre 2 et 8
                for ($k = 0; $k < rand(2, 8); $k++) {
                    $this->createEmployee($office);
                }
            }
        }


        $output->writeln('La base de donnée à bien été peuplée');
        return 0;
    }


    private function createCompany(): Company
    {
        //généré les données concernant la table company grace à faker

        $company = new Company();
        $company->name = $this->faker->company;
        $company->save();
        return $company;
    }
    private function createOffice(Company $company): Office
    {
        $office = $company->offices()->make();

        $office->name = $this->faker->company;
        $office->address = $this->faker->address;
        $office->city = $this->faker->city;
        $office->zip_code = $this->faker->postcode;
        $office->country = $this->faker->country;
        $office->email = "contact@$office->name.com";
        $office->phone = $this->faker->phoneNumber;
        $company->website = "https://$office->name.com/";
        //$company->image = $faker->imageUrl;

        $office->save();

        return $office;
    }

    private function createEmployee(Office $office): Employee
    {
        $employee = new Employee();

        $employee = $office->employees()->make();
        $employee->first_name = $this->faker->firstName;
        $employee->last_name = $this->faker->lastName;
        $employee->email = $this->faker->email;
        $employee->phone = $this->faker->phoneNumber;
        $employee->job_title = $this->faker->jobTitle;

        $employee->save();

        return $employee;
    }
}
