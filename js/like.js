
// t his is used to handle the liking and disliking of the post in a similar way to that in which views are incremented or decremented
// but this not only sends the request but it also dynamically changes the button depending onthe response.Also reloads the page
// with location.reload() once a post has been made
const upvoteBtn = document.querySelector('.upvote-bt');

upvoteBtn.addEventListener('click', () => {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'toggle-like.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = () => {
    if (xhr.status === 200) {
      const liked = JSON.parse(xhr.responseText).liked;
      if (liked) {
        upvoteBtn.innerHTML = '<img src="img/upvote-full.svg">';
      } else {
        upvoteBtn.innerHTML = '<img src="img/upvote.svg">';
      }
      location.reload();
    }
  };
  xhr.send('lpId=' + lpId);
});

// same concept but with disliking 
const downvoteBtn = document.querySelector('.downvote-btn');
console.log(downvoteBtn);
downvoteBtn.addEventListener('click', () => {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'toggle-dislike.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = () => {
    if (xhr.status === 200) {
      const disliked = JSON.parse(xhr.responseText).disliked;
      if (disliked) {
        downvoteBtn.innerHTML = '<img src="img/downvote-full.svg">';
      } else {
        downvoteBtn.innerHTML = '<img src="img/downvote.svg">';
      }
      location.reload();
    }
  };
  xhr.send('lpId=' + lpId);
});