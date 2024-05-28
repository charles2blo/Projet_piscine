$(document).ready(function(){
    var $carrousel = $('#carrousel'); // on cible le bloc du carrousel
    var $img = $('#carrousel img'); // on cible les images contenues dans le carrousel
    var indexImg = $img.length - 1; // on définit l'index du dernier élément
    var i = 0; // on initialise un compteur
    var $currentImg = $img.eq(i); // enfin, on cible l'image courante, qui possède l'index i (0 pour l'instant)
    $img.css('display', 'none'); // on cache les images
    $currentImg.css('display', 'block'); // on affiche seulement l'image courante

    $('.next').click(function(){ // image suivante
        i++; // on incrémente le compteur
        if (i <= indexImg){
            $img.css('display', 'none'); // on cache les images
            $currentImg = $img.eq(i); // on définit la nouvelle image
            $currentImg.css('display', 'block'); // puis on l'affiche
        }
        else{
            i = indexImg;
        }
    });

    $('.prev').click(function(){ // image précédente
        i--; // on décrémente le compteur, puis on réalise la même chose que pour la fonction "suivante"
        if (i >= 0){
            $img.css('display', 'none');
            $currentImg = $img.eq(i);
            $currentImg.css('display', 'block');
        }
        else{
            i = 0;
        }
    });

    function slideImg(){
        setTimeout(function(){ // on utilise une fonction anonyme
            if (i < indexImg){ // si le compteur est inférieur au dernier index
                i++; // on l'incrémente
            }
            else { // sinon, on le remet à 0 (première image)
                i = 0;
            }
            $img.css('display', 'none');
            $currentImg = $img.eq(i);
            $currentImg.css('display', 'block');
            slideImg(); // on oublie pas de relancer la fonction à la fin
        }, 4000); // on définit l'intervalle à 4000 millisecondes (4s)
    }

    slideImg(); // enfin, on lance la fonction une première fois

    // Gestion de l'affichage des formulaires de connexion et d'inscription
    var modal = document.getElementById("auth-forms");
    var span = document.getElementsByClassName("close")[0];

    $('#login-btn').click(function() {
        $('#signup-form').hide();
        $('#login-form').show();
        modal.style.display = "block";
    });

    $('#signup-btn').click(function() {
        $('#login-form').hide();
        $('#signup-form').show();
        modal.style.display = "block";
    });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
