
document.addEventListener("DOMContentLoaded", function(){

    function reveal() {
        let reveals = document.querySelectorAll(".reveal, .reveal-left, .reveal-right");
        reveals.forEach(function(el) {

            let windowHeight = window.innerHeight;
            let elementTop = el.getBoundingClientRect().top;
            let revealPoint = 120;

            if(elementTop < windowHeight - revealPoint){
                el.classList.add("active");
            }else{
                el.classList.remove("active");
            }
        });

    }

    window.addEventListener("scroll", reveal);
    
    window.addEventListener("load", reveal);

    const profileBox = document.querySelector('.profile-box');
    const avatarCircle = document.querySelector('.avatar-circle');

    if (avatarCircle) avatarCircle.addEventListener('click', () => profileBox.classList.toggle('show'));


    if (alertBox) {
        setTimeout(() => alertBox.classList.add('show'), 50);
        
        setTimeout(() => {
            alertBox.classList.remove('show');
            setTimeout(() => alertBox.classList.remove('show'), 1000);
        }, 3000);
    };

});