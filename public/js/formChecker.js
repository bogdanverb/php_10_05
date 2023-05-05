let password = document.getElementById("password")
    , password_c = document.getElementById("password_c");
let error = document.getElementById("error");

const checkEmail = () => {
    let email = document.getElementById("email");
    let emailPattern = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return error.innerHTML = !email.value.match(emailPattern) ? "Невірний формат email!" : "";
}

const  checkPassword = () => {
    if (password.value.length < 6) {
        return error.innerHTML = "Пароль повинен містити не менше 8 символів!";
    }
    if (password.length > 20) {
        return error.innerHTML = "Пароль повинен містити не більше 20 символів!";
    }
    if (password.value.match(/\s/g) !== null) {
        return error.innerHTML = "Пароль не повинен містити пробілів!";
    }
    if (password.value.match(/[A-Z]/g) === null) {
        return error.innerHTML = "Пароль повинен містити хоча б одну велику літеру!";
    }
    if (password.value.match(/[a-z]/g) === null) {
        return error.innerHTML = "Пароль повинен містити хоча б одну маленьку літеру!";
    }
    if (password.value.match(/[0-9]/g) === null) {
        return error.innerHTML = "Пароль повинен містити хоча б одну цифру!";
    }
    if (password.value.match(/[^A-Za-z0-9]/g) === null) {
        return error.innerHTML = "Пароль повинен містити хоча б один спеціальний символ!";
    }
    if (password.value !== password_c.value) {
        return error.innerHTML = "Паролі не співпадають!";
    }

    enableButton();
}

const enableButton = () => {
    let submit = document.getElementById("submit");
    submit.disabled = false;
}
