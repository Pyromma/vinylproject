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

function openAlbumTab(evt, tabid) {
    // Declare all variables
    var i, tabcontent, tablinks;
  
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tracklist");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("switchable-tab-button");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabid).style.display = "block";
    evt.currentTarget.className += " active";
  }

  function toggleGenres(source) {
    checkboxes = document.getElementsByName('genres[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
  }

  function toggleStyles(source) {
    checkboxes = document.getElementsByName('styles[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
  }
  