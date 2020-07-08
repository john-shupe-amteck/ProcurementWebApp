function toggle(id) {
  var x = document.getElementById(id);
  if (x.style.visibility === "collapse") {
    x.style.visibility = "visible";
  }  else {
    x.style.visibility = "collapse";
  }
}