function capitalizeText(input, length) {
    input.value = input.value.toUpperCase();
    if (input.value.length > length) {
        input.value = input.value.slice(0, length);
    }
}


// Start of fade Out Animation of .alert message
var elements = document.getElementsByClassName("alert");
var currentIndex = 0;

function showNextElement() {
  if (currentIndex < elements.length) {
    setTimeout(function() {
      elements[currentIndex].classList.add("active");
      currentIndex++;
      hideCurrentElement();
    }, 0);
  }
}

function hideCurrentElement() {
  if (currentIndex > 0) {
    setTimeout(function() {
      elements[currentIndex - 1].classList.remove("active");
      showNextElement();
    }, 4000);
  }
}

showNextElement();
// End of fade Out Animation of .alert message

