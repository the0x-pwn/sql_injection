
let password = document.querySelector('#password');
let username = document.querySelector('#username');
let msgPassword = document.querySelector('#password-msg');
let msgUsername = document.querySelector('#username-msg');
let form = document.querySelector('#form');

let isValid = true;

password.addEventListener('input', (event) => {
    if (event.target.value.length > 8) {
        password.classList.add('border-danger');
        msgPassword.classList.remove('d-none')
    } else {
        msgPassword.classList.add('d-none')
        password.classList.remove('border-danger');

    }
});


username.addEventListener('input', (event) => {
    if (event.target.value.length > 8) {
        username.classList.add('border-danger');
        msgUsername.classList.remove('d-none')
        isValid = false;
    } else {
        msgUsername.classList.add('d-none')
        username.classList.remove('border-danger');
        isValid = true;
    }
});


username.addEventListener('keydown', (event) => {
    if (event.target.value.length > 8 && event.key !== 'Backspace') {
        event.preventDefault();
    }
});

password.addEventListener('keydown', (event) => {
    if (event.target.value.length > 8 && event.key !== 'Backspace') {
        event.preventDefault();
    }
});


form.addEventListener('submit', (event) => {
    if (!isValid) {
        event.preventDefault();
    }
});









