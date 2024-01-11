// using this javascript file to show the user what image he has chosen in profile editing and showing the 
// pop up box confirming the user wants to delete his account
let profilePic = document.getElementById("profile-pic");
let deleteBtn = document.getElementById("delete-acc-btn");
let inputFile = document.getElementById("profile-image");
let deleteSection = document.getElementsByClassName('delete-section')[0];

inputFile.onchange = ()=>{
    profilePic.src=URL.createObjectURL(inputFile.files[0]);
}

deleteBtn.addEventListener('click',(e)=>{
    e.preventDefault();
    deleteSection.classList.remove('hidden')
    document.body.style.overflow = 'hidden';
})
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }