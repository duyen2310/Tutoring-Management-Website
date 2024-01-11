async function copyUrl(id, userId) {
    try {
        // constructing the URL to be copied
        let url = window.location.origin + "/comp1230/assignments/project/viewLp.php?lpId=" + id + "&userId=" + userId;

        // using the Clipboard API to copy the URL to the clipboard
        await navigator.clipboard.writeText(url);

        // changing button text after copying
        let copyButton = document.getElementById("copyButton_" + id);
        copyButton.innerHTML = "URL Copied!";

        // resetting the button text after 2 seconds to its normal one
        setTimeout(function() {
            copyButton.innerHTML = "Copy URL";
        }, 2000);
    } catch (err) {
        console.error('Unable to copy to clipboard', err);
    }
}
