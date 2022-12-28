var slideIndex = 0;

function Slider(x){
    var slides = document.getElementsByClassName("slider-panel");
    if(x > slides.length-1){ x = 0; }
    if(x < 0){ x = slides.length-1}
    slideIndex = x;
    for(var i = 0; i < slides.length; i++){slides[i].style.display = "none";}
    slides[x].style.display = "block";
}

Slider(0);

function autoSlider(){
    Slider(slideIndex += 1);
}

function plusSlider(y){
    Slider(slideIndex += y);
}

setInterval(autoSlider, 7000);

function navBar(){
    var nav = document.getElementById("navbar");
    if (nav.className === "mainbar") {
        nav.className += " responsive";
    } else {
        nav.className = "mainbar";
  }
}
