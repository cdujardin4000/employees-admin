<?php

namespace App\Controller\Admin;

use App\EasyAdmin\VotesField;

class DemandsPendingCrudController
{
    public function configureFields(string $pageName): iterable
    {

        yield VotesField::new('votes', 'Total Votes');

    }
}