particlesJS.load('particles-js1', 'particles.json', function() {
  console.log('callback - particles.js config loaded');
});
particlesJS.load('particles-js2', 'particlesTest.json', function() {
  console.log('callback - particles.js config loaded');
});

var downscroll = document.querySelector(".scrollLink");


var navdown = function (){
  var height = screen.height;
  window.scrollBy({
  top: height, // could be negative value
  left: 0,
  behavior: 'smooth'
});
}

downscroll.addEventListener("click", navdown);
