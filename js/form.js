const inputs = document.querySelectorAll('input:not([type="submit"])');
const form = document.querySelector('form');
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const errors = document.querySelectorAll(".error");
const creationError = document.getElementById('creationError')
const fname = inputs[0];
const lname = inputs[1];
const uname = inputs[2];
const email = inputs[3];
const password = inputs[4];
const passwordbox = [];
const errorbox = [];

// validation for the first name in the frontend (making sure its not a number and displaying the correct response to the frontend)
fname.addEventListener("input", function () {
  if (!isNaN(fname.value)) {
    fname.classList.add("invalid");
    fname.classList.remove("valid");
    errors[0].textContent = "First name can't be a number!";
    errorbox.push(errors[0].textContent);
  } else {
    fname.classList.add("valid");
    fname.classList.remove("invalid");
    errors[0].textContent = "";
    
  }
});
// same idea but for the lastname
lname.addEventListener("input", function () {
  if (!isNaN(lname.value)) {
    lname.classList.add("invalid");
    lname.classList.remove("valid");
    errors[1].textContent = "Last name can't be a number!";
  } else {
    lname.classList.add("valid");
    lname.classList.remove("invalid");
    errors[1].textContent = "";
  }
});
// for the username (cant be less than 3)
uname.addEventListener("input", function () {
  if (uname.value.length < 3) {
    uname.classList.add("invalid");
    uname.classList.remove("valid");
    errors[2].textContent = "Username must be at least 3 characters!";
  } else {
    uname.classList.add("valid");
    uname.classList.remove("invalid");
    errors[2].textContent = "";
  }
});

// for the email (has to pass a regex)
email.addEventListener("input", function () {
  const emailtr = email.value.trim();

  if (!emailRegex.test(emailtr)) {
    email.classList.add("invalid");
    email.classList.remove("valid");
    errors[3].textContent = "Invalid email address";
  } else {
    email.classList.add("valid");
    email.classList.remove("invalid");
    errors[3].textContent = "";
  }
});
// for the password
password.addEventListener("input", function () {
  const passwordValue = password.value;
  // we use this to show all the errors later
  const passwordbox = [];

  // cant be less than 8
  if (passwordValue.length < 8) {
    passwordbox.push("be at least 8 characters");
  }

  // has to contain a uppercase letter
  if (!/[A-Z]/.test(passwordValue)) {
    passwordbox.push("have at least one uppercase character");
  }

  // and a lowercase
  if (!/[a-z]/.test(passwordValue)) {
    passwordbox.push("have at least one lowercase character");
  }

  // and a nubmer
  if (!/[0-9]/.test(passwordValue)) {
    passwordbox.push("have at least one number");
  }

  // and a symbol
  if (!/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(passwordValue)) {
    passwordbox.push("have at least one symbol");
  }

  if (passwordbox.length > 0) {
    password.classList.add("invalid");
    password.classList.remove("valid");
    errors[4].textContent = "Password must: " + passwordbox.join(", ");
  } else {
    password.classList.add("valid");
    password.classList.remove("invalid");
    errors[4].textContent = "";
  }
});
// adding focus eventlisteners so to stop displaying the error messages when they are refocused
fname.addEventListener("focus", function () {
  errors[0].textContent = "";
});

lname.addEventListener("focus", function () {
  errors[1].textContent = "";
});

uname.addEventListener("focus", function () {
  errors[2].textContent = "";
});

email.addEventListener("focus", function () {
  errors[3].textContent = "";
});

password.addEventListener("focus", function () {
  errors[4].textContent = "";
});

// on submit if any of them are empty we show the messages below
form.addEventListener("submit", function (event) {
  creationError.textContent = ""
  if(fname.value === ""){
    errors[0].textContent = "First name can't be empty!"
}
if(lname.value === ""){
    errors[1].textContent = "Last name can't be empty!"
}
if(uname.value === ""){
    errors[2].textContent = "Username must be at least 3 characters!"
}
if(email.value === ""){
    errors[3].textContent = "Email can't be empty!"
}
if(password.value === ""){
    errors[4].textContent = "Password must be at least 8 characters!";
    errors[5].textContent = "";
}
// if there are errors we show all of them and ask the user to fix them before submitting
  for (let i = 0; i < errors.length; i++) {
    if (errors[i].textContent !== "") {
      event.preventDefault();
      creationError.textContent = "Please fix the errors before submitting the form.";
      return;
    }
  }

});

