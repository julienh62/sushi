console.log("cc filter");
//ecouteur de click
let buttons = document.querySelectorAll(".filter");

for (const button of buttons) {
    button.addEventListener("click", function (e) {
        //eventpreventdefault au cas ou js desactivé
        e.preventDefault();
        console.log("bonjour");
    });
}