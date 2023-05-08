<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use stdClass;
use JetBrains\PhpStorm\NoReturn;

final class NewsPresenter extends Nette\Application\UI\Presenter
{
    private string $title = 'News';

    public function __construct(
        private readonly Nette\Database\Explorer $database,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function renderIndex(): void // this function is called when the page is loaded
    {
        $this->setView('news');
        $this->getTemplate()->title = $this->title; // set the title

        $this->getTemplate()->news =
            $this->database
            ->table('news')
            ->order('news_id ASC');
    }

    /**
     * @throws BadRequestException
     */
    public function renderShow(int $postId): void
    {
        $post = $this->database
            ->table('news')
            ->get($postId);
        if (!$post) {
            $this->error('Post not found');
        }
        $this->getTemplate()->title = $post->news_title;
        $this->getTemplate()->post = $post;
    }

    public function renderAdd(): void
    {
        $this->setView('add');
        $this->getTemplate()->title = $this->title;
    }

    protected function createComponentAddNewsForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Заголовок:');
        $form->addText('short_title', 'Опис:');
        $form->addTextArea('text', 'Текст:');
        $form->addUpload('file', 'Зображення:');
        $form->addSubmit('send', 'Додати');
        $form->onSuccess[] = [$this, 'addNewsFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $data
     * @return void
     * @throws AbortException
     */
    #[NoReturn] public function addNewsFormSucceeded(Form $form, stdClass $data): void
    {
        $news_user_id = $this->getUser()->getIdentity()->getId();
        $news_user_login = $this->getUser()->getIdentity()->getData()['login'];
        $file = $data->file;
        $file->move('./uploads/news/' . $file->name);
        $image_url = '/uploads/news/'.$file->name;

        $this->database->
            table('news')->
            insert([
                'news_title' => $data->title,
                'news_short_title' => $data->short_title,
                'news_content' => $data->text,
                'image_url' =>  $image_url,
                'news_user_id' => $news_user_id,
                'news_user_login' => $news_user_login,
            ]);

        $this->redirect('News:index');
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
