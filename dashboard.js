const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const alertBox = document.querySelector('.alert-box');

registerLink.addEventListener('click', () => wrapper.classList.add('active'));
loginLink.addEventListener('click', () => wrapper.classList.remove('active'));

if (alertBox) {
  setTimeout(() => alertBox.classList.add('show'), 50);
  
  setTimeout(() => {
    alertBox.classList.remove('show');
    setTimeout(() => alertBox.classList.remove('show'), 1000);
  }, 3000);
};
