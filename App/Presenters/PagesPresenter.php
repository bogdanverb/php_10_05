<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class PagesPresenter extends Nette\Application\UI\Presenter
{
    public function renderParliament(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Parliament'; // set the title
    }

    public function renderPassport(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Passport'; // set the title
    }
}
