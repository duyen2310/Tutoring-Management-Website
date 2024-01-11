
// using this javascript file to show the user what image he has chosen in sections
let profilePic = document.getElementById("profile-pic");
let inputFile = document.getElementById("profile-image");

inputFile.onchange = ()=>{
    profilePic.src=URL.createObjectURL(inputFile.files[0]);
}