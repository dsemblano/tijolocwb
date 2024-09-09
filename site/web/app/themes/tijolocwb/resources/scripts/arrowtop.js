document.addEventListener("DOMContentLoaded", function() {
    //hide or show the "back to top" link

    //smooth scroll to top
    document.querySelector('.cd-top').addEventListener('click', function(event) {
        event.preventDefault();
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
});
