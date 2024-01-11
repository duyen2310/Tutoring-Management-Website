// selecting header logout back and section which
const header = document.getElementsByTagName('header')[0]
const logout = document.getElementsByTagName('button')[0];
const back = document.getElementsByTagName('button')[1];
const section = document.getElementsByTagName('section')[0];

// if the page scrolls past 50 units add the headerScrolled class which in css has a bg color instead of the bgcolor being transparent
addEventListener("scroll", (event) => {
    if(window.scrollY >= 50){

        header.classList.add('headerScrolled')
    }
    // if we go back up we remove it
    if(window.scrollY <= 50){
        header.classList.remove('headerScrolled');
    }
});


// popup listener for logging out
logout.addEventListener('click',(e)=>{
    e.preventDefault();
    section.classList.remove('hidden')
    document.body.style.overflow = 'hidden';
})

back.addEventListener('click',(e)=>{
    e.preventDefault();
    section.classList.add('hidden');
    document.body.style.overflow = '';
})
