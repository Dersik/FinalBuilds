
var modal = document.getElementById('modal');
var modalinner = document.querySelector('.modalcontent');
var tiles = document.querySelectorAll('article');
var closeB = document.querySelector('.closebutton');
var filtericons = document.querySelectorAll('.filter');

var openModal = function(event) {
  var specifictile = event.currentTarget;
  modalinner.innerHTML = specifictile.innerHTML;
  console.log(modalinner.innerHTML);
  modal.style.display = 'block';

};


var closeModal = function(event) {
  modalinner.innerHTML = "";
  modal.style.display = 'none';
};

var ascdec = function(event){
  var temp = filtericons;
  for(var k = 0; k <filtericons.length; k++){
    if(event.currentTarget.id != filtericons[k].id){
      filtericons[k].value = 0;
      filtericons[k].querySelector('p').innerHTML = filtericons[k].id;
    }
    else{
      filtericons[k].value = filtericons[k].value+1;
    }
    console.log("icon"+k+filtericons[k].value)
  }
  if(event.currentTarget.value == 1){
    var filtertext = event.currentTarget.querySelector('p');
    filtertext.innerHTML = event.currentTarget.id+"/\\";

  }
  if(event.currentTarget.value == 2){
    var filtertext = event.currentTarget.querySelector('p');
    filtertext.innerHTML = event.currentTarget.id+"\\/";
  }
  if(event.currentTarget.value == 3){
    var filtertext = event.currentTarget.querySelector('p');
    filtertext.innerHTML = event.currentTarget.id;
    event.currentTarget.value = 0;
  }

};




for(var k = 0; k <filtericons.length; k++){
    filtericons[k].value = 0;
}

for(var j = 0; j <filtericons.length; j++){
  filtericons[j].addEventListener("click", ascdec);
}

for(var i = 0; i < tiles.length; i++){
   tiles[i].addEventListener("click", openModal);
}
closeB.addEventListener("click", closeModal);



// Get the <span> element that closes the modal
//var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
//btn.onclick = function() {
    //modal.style.display = "block";
//}

// When the user clicks on <span> (x), close the modal
//span.onclick = function() {
    //modal.style.display = "none";
//}

// When the user clicks anywhere outside of the modal, close it
//window.onclick = function(event) {
    //if (event.target == modal) {
        //modal.style.display = "none";
    //}
//}
