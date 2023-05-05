<?php

namespace App\Presenters;

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

        $form->addText('login', "Ім'я користувача:")
            ->addRule(Nette\Forms\Form::FILLED, 'Будь ласка, введіть ваше ім\'я.')
            ->addRule(
                Nette\Forms\Form::PATTERN,
                'Логін має бути від 3 до 20 символів та містити лише латинські літери та цифри.',
                '^[A-z0-9]{3,20}$'
            )// Username must contain %d or more characters.
            ->setRequired('Будь ласка, введіть ваше ім\'я.');

        $form->addPassword('password', 'Пароль:')
            ->addRule(Nette\Forms\Form::FILLED, 'Будь ласка, введіть ваш пароль.')
            ->addRule(
                Nette\Forms\Form::PATTERN,
                'Пароль має бути від 8 до 20 символів та містити 1 цифру, 1 велику, 1 маленьку літеру та 1 спецсимвол.',
                '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).{8,20}$'
            ) // Password must contain %d or more characters.
            ->setRequired('Будь ласка, введіть ваш пароль.');

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
            $this->redirectUrl('/');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError("Неправильний логін чи пароль.");
        }
    }

    /**
     * @return Form
     */
    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;
        // Username
        $form->addText('login', 'Ім\'я користувача:')
            ->addRule(
                Nette\Forms\Form::PATTERN,
                'Логін має бути від 3 до 20 символів та містити лише латинські літери та цифри.',
                '^[A-z0-9]{3,20}$'
            ) // Username must contain %d or more characters.
            ->setRequired('Будь ласка, введіть ваше им\'я.');

        /** Пароль має містити %d або більше символів.
         * Пароль має містити %d або менше символів.
         * Пароль має містити прописні літери, цифри, тире або нижнє підкреслення.
         */
        $form->addPassword('password', 'Пароль:')
            ->addRule(Nette\Forms\Form::FILLED, 'Будь ласка, введіть ваш пароль.')
            ->addRule(
                Nette\Forms\Form::PATTERN,
                'Пароль має бути від 8 до 20 символів та містити 1 цифру, 1 велику, 1 маленьку літеру та 1 спецсимвол.',
                '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).{8,20}$'
            ) // Password must contain %d or more characters.
            ->setRequired('Будь ласка, введіть ваш пароль.');


        // Password confirmation
        $form->addPassword('password_c', 'Повторіть пароль:')
            ->setRequired('Будь ласка, повторіть ваш пароль.')
            ->addRule(
                Nette\Forms\Form::EQUAL,
                'Паролі не співпадають.',
                $form['password']
            );  // порівнюємо з полем password


        // E-mail
        $form->addText('email', 'E-mail:')
            ->addRule(
                Nette\Forms\Form::EMAIL,
                'Неправильний формат e-mail.'
            ) // Invalid e-mail format.
            ->setRequired('Будь ласка, введіть ваш e-mail.');

        // Submit button
        $form->addSubmit('send', 'Зареєструватись');

        // Call method signInFormSucceeded() on success
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
            ->where('login', $data->login)
            ->whereOr([
                'login > ?' => $data->login,
                'email = ?' => $data->email,
            ])
            ->fetch();

        if (!$result) {
            $this->database->table('users')->insert([
                'login' => $data->login,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
                'email' => $data->email,
            ]);

            $this->getUser()->login($data->login, $data->password);
            $this->redirectUrl('Home:home');
        } else {
            $form->addError('Користувач з таким іменем вже існує.');
        }
    }


    /**
     * @throws AbortException
     */
    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->redirect('Sign:in');
    }
}
