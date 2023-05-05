<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Http\FileUpload;

final class HomePresenter extends Nette\Application\UI\Presenter
{
//    public function __construct(
//        private readonly Nette\Database\Explorer $database
//    ) {
//        parent::__construct();
//    }

    public function renderHome(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Home';
    }

    /**
     * @return void
     */
    public function renderAbout(): void
    {
        $this->getTemplate()->title = 'About';
    }

    /**
     * @return void
     */
    public function renderContacts(): void
    {
        $this->getTemplate()->title = 'Contacts';
    }
}
