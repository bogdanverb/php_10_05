let login = document.getElementById("login"),
    password = document.getElementById("password"),
    password_c = document.getElementById("password_c"),
    email = document.getElementById("email"),
    error = document.getElementById("form_error");

const clearMessage = () => {
    // check if error element exists
    if (error !== null) {
        error.innerHTML = "";
    }
}

const enableButton = () => {
    let submit = document.getElementById("submit");
    submit.disabled = false;
}

const checkLogin = () => {
    if (login.value.length < 6) {
        return error.innerHTML = "Логін повинен містити не менше 6 символів!";
    }
    if (login.value.length > 20) {
        return error.innerHTML = "Логін повинен містити не більше 20 символів!";
    }
    if (login.value.match(/\s/g) !== null) {
        return error.innerHTML = "Логін не повинен містити пробілів!";
    }
    // check if login has only letters and numbers
    if (login.value.match(/[^A-Za-z0-9]/g) !== null) {
        return error.innerHTML = "Логін повинен містити тільки літери та цифри!";
    }
    // check if login has at least one letter
    if (login.value.match(/[A-Za-z]/g) === null) {
        return error.innerHTML = "Логін повинен містити хоча б одну літеру!";
    }
    // check if login starts with a letter
    if (login.value.match(/^[A-Za-z]/g) === null) {
        return error.innerHTML = "Логін повинен починатися з літери!";
    }
    clearMessage();
}

const checkEmail = () => {
    if (email.value.match(/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/) === null) {
        return error.innerHTML = "Невірний формат email!";
    }
    clearMessage();
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
    clearMessage();
}

const togglePassword = (element) => {
    if (password.type === "password") {
        password.type = "text";
        if (password_c !== null) {
            password_c.type = "text";
        }
        element.classList.remove("fa-eye-slash");
        element.classList.add("fa-eye");
    } else {
        password.type = "password";
        if (password_c !== null) {
            password_c.type = "password";
        }
        element.classList.remove("fa-eye");
        element.classList.add("fa-eye-slash");
    }
}

