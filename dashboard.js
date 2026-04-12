const formBox = document.querySelector('.form-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.addEventListener('click', () => formBox.classList.add('active'));
loginLink.addEventListener('click', () => formBox.classList.remove('active'));
