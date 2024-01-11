document.addEventListener("DOMContentLoaded", function() {
    //hide or show the "back to top" link
    window.onscroll = function() {
        if (window.pageYOffset > 300) {
            document.querySelector('.cd-top').classList.add('cd-is-visible');
            document.querySelector('.cd-top').classList.remove('cd-fade-out');
        } else {
            document.querySelector('.cd-top').classList.remove('cd-is-visible');
        }
        if (window.pageYOffset > 1200) {
            document.querySelector('.cd-top').classList.add('cd-fade-out');
        }
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            document.getElementById("logoname").classList.add("shrink");
            document.getElementById("logosurname").classList.add("hidden");
            
        } else {
            document.getElementById("logoname").classList.remove("shrink");
            document.getElementById("logosurname").classList.remove("hidden");
        }
    };

    //smooth scroll to top
    document.querySelector('.cd-top').addEventListener('click', function(event) {
        event.preventDefault();
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
});
