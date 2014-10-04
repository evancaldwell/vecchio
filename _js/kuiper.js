/*
* Author:      Marco Kuiper (http://www.marcofolio.net/)
*/

// Speed of the automatic slideshow
var slideshowSpeed = 4000;

// Variable to store the images we need to set as background
// which also includes some text and url's.
var photos = [ 
	{ "image" : "slide1.jpg"},
	{ "image" : "slide2.jpg"},
	{ "image" : "slide3.jpg"},
	{ "image" : "slide4.jpg"},
	{ "image" : "slide5.jpg"},
	{ "image" : "slide6.jpg"},
	{ "image" : "slide7.jpg"},
	{ "image" : "slide8.jpg"},
	{ "image" : "slide9.jpg"},
	{ "image" : "slide10.jpg"}	
];

function randOrd(){
return (Math.round(Math.random())-0.5); }

photos.sort(randOrd );

$(document).ready(function() {
			
	var activeContainer = 1;	
	var currentImg = 0;
	var animating = false;
	var navigate = function(direction) {
		// Check if no animation is running. If it is, prevent the action
		if(animating) {
			return;
		}
		
		// Check which current image we need to show
		if(direction == "next") {
			currentImg++;
			if(currentImg == photos.length + 1) {
				currentImg = 1;
			}
		} else {
			currentImg--;
			if(currentImg == 0) {
				currentImg = photos.length;
			}
		}
		
		// Check which container we need to use
		var currentContainer = activeContainer;
		if(activeContainer == 1) {
			activeContainer = 2;
		} else {
			activeContainer = 1;
		}
		
		showImage(photos[currentImg - 1], currentContainer, activeContainer);
		
	};
	
	var currentZindex = -1;
	var showImage = function(photoObject, currentContainer, activeContainer) {
		animating = true;
		
		// Make sure the new container is always on the background
		currentZindex--;
		
		// Set the background image of the new active container
		$("#headerimg" + activeContainer).css({
			"background-image" : "url(http://localhost:8888/vecchiotrees/_images/" + photoObject.image + ")",
			"display" : "block",
			"z-index" : currentZindex
		});
		

		
		
		// Fade out the current container
		// and display the header text when animation is complete
		$("#headerimg" + currentContainer).fadeOut(function() {
			setTimeout(function() {
				$("#headertxt").css({"display" : "block"});
				animating = false;
			}, 500);
		});
	};
	

	
	// We should statically set the first image
	navigate("next");
	
	// Start playing the animation
	interval = setInterval(function() {
		navigate("next");
	}, slideshowSpeed);
	
});