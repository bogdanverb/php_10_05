<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\AbortException;

final class NewsPresenter extends Nette\Application\UI\Presenter
{
    private string $title = 'News';

    /**
     * @return void
     */
    public function renderIndex(): void // this function is called when the page is loaded
    {
        $this->setView('news');
        $this->getTemplate()->title = $this->title; // set the title
    }


    /**
     * @throws AbortException
     */
    protected function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

}
