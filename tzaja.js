var elem = document.getElementsByClassName('zastava');
var tecaj = document.getElementsByClassName('tecaj_zvuk');

for (var i = 0; i < elem.length; i++) {

  elem[i].addEventListener('click', function (event) {
    if (event.target.parentNode.childNodes[3].style.visibility === "hidden") {
      event.target.parentNode.childNodes[3].style.visibility = "visible";
    } else {
      event.target.parentNode.childNodes[3].style.visibility = "hidden";
    }
  }, false);
}