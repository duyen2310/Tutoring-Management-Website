// using this javascript file to select all of the links in the viewLp.php and add an event listener to all of them
// which takes the section_id (specified in the link) and makes a request to update_views.php in order to increment the
// views from there
var links = document.querySelectorAll('.views-link');
links.forEach(function(link) {
    link.addEventListener('click', function(e) {
        let section_id = this.getAttribute('data-section-id');

        let xhr = new XMLHttpRequest();

        xhr.open('POST', 'update_views.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.send('section_id=' + section_id);

    });
});
