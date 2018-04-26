var resumetab = document.querySelector("#resume");
var abouttab = document.querySelector("#about");
var resumemodal = document.querySelector("#resumemodal");
var aboutmodal = document.querySelector("#aboutmodal");
var closeabout = document.querySelector("#leftclosing");
var closeresume = document.querySelector("#rightclosing");

console.log(resumetab,abouttab);


console.log("here1");
var slideHoro = function(event){
  var id = event.currentTarget.id;
  if(id=="resume"||id=="rightclosing"){
    resumemodal.style.right = "default";
    resumemodal.style.top = 0;
    resumemodal.style.left = 0;
    resumemodal.style.display = "flex";
    resumemodal.classList.add("Hanimation");

    setTimeout(function(){
      resumemodal.style.width = "100vw";
    }, 1900);

  }else{
    aboutmodal.style.left = "default";
    aboutmodal.style.top = 0;
    aboutmodal.style.right = 0;
    aboutmodal.style.display = "flex";
    aboutmodal.classList.add("Hanimation");

    setTimeout(function(){
      aboutmodal.style.width = "100vw";
    }, 1900);

  }

}
var closeHoro = function(event){
  var id = event.currentTarget.id;
  var direction = "none";
  if(id=="rightclosing"){
    resumemodal.classList.remove("Hanimation");
    resumemodal.classList.add("HCanimation");
    setTimeout(function(){
      resumemodal.style.display = "none";
      resumemodal.classList.remove("HCanimation");
    }, 2000);
  }else{
    aboutmodal.classList.remove("Hanimation");
    aboutmodal.classList.add("HCanimation");
    setTimeout(function(){
      aboutmodal.style.display = "none";
      aboutmodal.classList.remove("HCanimation");
    }, 2000);
  }
}
  console.log("WE HERE");



closeresume.addEventListener("click", closeHoro);
closeabout.addEventListener("click", closeHoro);

resumetab.addEventListener("click", slideHoro);
abouttab.addEventListener("click", slideHoro);

console.log("endoffile");
