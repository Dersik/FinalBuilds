var $fsm = document.querySelectorAll('.box');
var fuldiv = document.querySelector('.ffull');

//var realWidth = $fsm[1].offsetWidth;
//var divtext = document.querySelectorAll(".EventName, .EventLoc, .EventProx, .EventDesc");
//for(var i = 0; i<divtext.length;i++){
	//divtext[i].style.width = realWidth;
//}
//var stupid = 4;


//alert(derek.length);


//var arrowremover = document.querySelectorAll('.exButton');
//alert(arrowremover.length);
//var filters = document.querySelectorAll('.filter');
//alert(filters.length);
//for(var j = 0;j<filters.length;j++){
	   //filters[j].addEventListener("mouseenter", filterExpand);
		 //filters[j].addEventListener("mouseleave", filterDexpand);
//}


//for(var j = 0;j<arrowremover.length;j++){
	//hiddenpls[j].addEventListener("mouseenter", filterExpand);
	//hiddenpls[j].addEventListener("mouseleave", filterDexpand);
//}
//$('.exButton').hide(0);

function filterExpand(event){

	var eventCL = event.currentTarget.classList.value.split(' ');
  var stupid = eventCL[1].toString();
	var derek = document.querySelectorAll('.exButton.'+stupid);
  alert("hey");
  //alert(leaffind.length);
}

function filterDexpand(event){
	 var eventCL = event.currentTarget.classList.value.split(' ');
	 //$('.exButton.'+eventCL[1]).fadeOut(200);
}


var openFSM = function(event) {

	var $this = event.currentTarget;
	//var copy = $this.innerHTML;
  //$this.addEventListener("click", openFSM);
  var classes = $this.classList.value.split(' ');
  var classLetter = classes[1];
  //alert(descriptiondiv.innerHTML);
  //alert($this.innerHTML);
	var fuldiv = document.getElementById("ffull");
  fuldiv.innerHTML = $this.innerHTML;
  var hiddendivs = fuldiv.querySelectorAll(".hidden");

	for(var i = 0;i<hiddendivs.length;i++){
		//hiddendivs[i].style.display = "inline";
		hiddendivs[i].classList.add('inline');
	}
  console.log(fuldiv.innerHTML);

	//alert("here");
	//fuldiv.appendChild(copy);
	//alert(fuldiv.innerHTML);

  //alert(ffull.innerHTML);

    $('.tiles').fadeOut(600);
    setTimeout(function() {
      document.getElementById('ffull').style.zIndex = '1';
    }, 700);
    $('.ffull').fadeIn(600);

    //$fsmActual.innerHTML = $this.innerHTML;
    //var classes = $this.classList.value.split(' ');
    //for (var i = 0; i < classes.length; i++) {
      //$fsmActual.classList.add(classes[i]);
    //}
    //$fsmActual.classList.add('growing');
  //  $fsmActual.style.height = '100vh';
    $this.addEventListener("click", openFSM);
};

var closeFSM = function(event){
  var $this = event.currentTarget;

  $('.ffull').fadeOut(600);
  setTimeout(function() {
    document.getElementById('ffull').style.zIndex = '-1';
  }, 700);

  //document.getElementById('ffull').innerHTML = "<div class = "map"></div>";
  //alert(document.getElementById('ffull').innerHTML);
	$('.tiles').fadeIn(600);

  $this.addEventListener("click", closeFSM);
};

for (var i = 0; i < $fsm.length; i++) {
  	$fsm[i].addEventListener("click", openFSM);
}
document.getElementById('ffull').addEventListener("click", closeFSM);
