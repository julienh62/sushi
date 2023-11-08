console.log("hello webpack");
//ecouteur de click
let buttons = document.querySelectorAll(".add-cart-button")

for (const button of buttons) {
    button.addEventListener("click", async function(e){
        //eventpreventdefault au cas ou js desactivé
        e.preventDefault();
        console.log("bonjour");
        //recupere id
        //evite de rafraichir la page pour voir le bouton modifier apparaitre
        button.innerHTML="modifier";
        let id = button.dataset.product;
        console.log(id);
        // parent car c'est le bouton juste à coté
        const quantity = button.parentElement.querySelector("input").value;
        console.log(quantity);

        //ecriture concaténation
        //fetch("/add-cart/" + id + "?quantity=" + quantity)
        //ecriture interpolation
        //await permet attendre resultat du fetch avant de poursuivre
        //le code n'est plus asynchrone
        await fetch(`/add-cart/${id}?quantity=${quantity}`)
          

        fetch("cart-size")
            .then( response => response.text() )
            .then( totalQuantity => document.querySelector(".cartQuantity").innerHTML=totalQuantity)
        
    
        });
    } 






//pas besoin de then car on n' a rien à récupérer
/*.then( function(response){
            return response.text()
        }
)
.then( 
    function(data){
        console.log(data);
    } 
)  */