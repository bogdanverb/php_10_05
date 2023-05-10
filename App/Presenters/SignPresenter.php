<?php

namespace App\Presenters;

use JetBrains\PhpStorm\NoReturn;
use Nette;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use stdClass;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private readonly Nette\Database\Explorer $database,
    ) {
        parent::__construct();
    }

    public function renderIn(): void
    {
        $this->setView('in');
        $this->getTemplate()->title = 'Sign in';
    }

    public function renderUp(): void
    {
        $this->setView('up');
        $this->getTemplate()->title = 'Sign up';
    }

    /**
     * @return Form
     */
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;

        $form->addText('login', "Ім'я користувача:");
        $form->addPassword('password', 'Пароль:');
//        $form->addReCaptcha('recaptcha', $label = 'Captcha')
//            ->setMessage('Are you a bot?');
        $form->addSubmit('send', 'Увійти');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $data
     * @return void
     * @throws AbortException
     */
    public function signInFormSucceeded(Form $form, stdClass $data): void
    {
        try {
            $this->getUser()->login($data->login, $data->password);
            $this->redirect('Home:home');
        } catch (Nette\Security\AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'error');
        }
    }

    /**
     * @return Form
     */
    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;

        $form->addText('login', 'Ім\'я користувача:');
        $form->addPassword('password', 'Пароль:');
        $form->addPassword('password_c', 'Повторіть пароль:');
        $form->addText('email', 'E-mail:');

//        $form->addReCaptcha('recaptcha', $label = 'Captcha')
//            ->setMessage('Are you a bot?');

        $form->addSubmit('send', 'Зареєструватись');

        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];
        return $form;
    }

    /**
     * @throws AbortException
     * @throws AuthenticationException
     */
    public function signUpFormSucceeded(Form $form, $data): void
    {
        /**
         * Логін має бути унікальним
         * пошта має бути унікальною
         */
        $result = $this->database->table('users')
            ->whereOr([
                'login = ?' => $data->login,
                'email = ?' => $data->email,
            ])
            ->fetch();

        if (!$result) {
            $this->database->table('users')->insert([
                'login' => $data->login,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
                'email' => $data->email,
            ]);

            $this->flashMessage('Акаунт успішно створено', 'success');
            $this->redirect('Sign:in');
        } else {
            if ($result->login === $data->login) {
                $this->flashMessage("Користувач $result->login вже існує.", 'error');
            }
            if ($result->email === $data->email) {
                $this->flashMessage("Пошта $result->email вже зареєстрована.", 'error');
            }

            foreach ($result as $key => $value) {
                $this->flashMessage("$key => $value", 'error');
            }

            $this->redirect('Sign:up');
        }
    }


    /**
     * @throws AbortException
     */
    #[NoReturn] public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->redirect('Sign:in');
    }
}
