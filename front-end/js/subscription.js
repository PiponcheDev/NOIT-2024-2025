function openCity(button, cityName){
  var i, tabcontent;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  document.getElementById(cityName).style.display = "block";

  var allCircles = document.getElementsByClassName("circle");
  for (i = 0; i < allCircles.length; i++) {
    allCircles[i].classList.remove('active');
  }
  
  const container = button.closest('.container');
  
  const circle = container.querySelector('.circle');
  circle.classList.add('active');
}

