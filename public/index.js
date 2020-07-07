function copyToClipboard(event){
    event.target.previousElementSibling.select();
    event.target.previousElementSibling.setSelectionRange(0, 99999); /*For mobile devices*/
    document.execCommand("copy");
}