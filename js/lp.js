
// exact sane as profile.js but with lp.js
let profilePic = document.getElementById("profile-pic");
let inputFile = document.getElementById("profile-image");
let deleteBtn = document.getElementById("delete-acc-btn");
let deleteSection = document.getElementsByClassName('delete-section')[0];

deleteBtn.addEventListener('click', (e) => {
    e.preventDefault();
    deleteSection.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});


inputFile.onchange = ()=>{
    profilePic.src=URL.createObjectURL(inputFile.files[0]);
}