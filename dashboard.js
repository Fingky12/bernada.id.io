const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.addEventListener('click', () => wrapper.classList.add('active'));
loginLink.addEventListener('click', () => wrapper.classList.remove('active'));

const profileBox = document.querySelector('.profile-box');
const avatarCircle = document.querySelector('.avatar-circle');

if (avatarCircle) avatarCircle.addEventListener('click', () => profileBox.classList.toggle('show'));


const alertBox = document.querySelector('.alert-box');

if (alertBox) {
  setTimeout(() => alertBox.classList.add('show'), 50);
  
  setTimeout(() => {
    alertBox.classList.remove('show');
    setTimeout(() => alertBox.classList.remove('show'), 1000);
  }, 3000);
};



