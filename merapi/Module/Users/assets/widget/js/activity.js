

window.onload = () => {

    document.body.classList.add("loaded");
    window.merapi.http.get("http://localhost/users/activity").then((response, text, xhr) => {

    })

}