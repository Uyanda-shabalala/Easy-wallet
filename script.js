const body=document.getElementById("body");
const heading=document.getElementById("headings");
const form=document.getElementById("container")
  const button = document.getElementById("dark_mode");

 const isDark = heading.style.color === "blue" && body.style.backgroundColor === "grey";

  if (isDark) {
    heading.style.color = "black";
    body.style.backgroundColor = "white";
    button.innerHTML = "Dark Mode: ON";
  } else {
    body.style.backgroundColor = "grey";
    body.style.color = "white";
    heading.style.color = "blue";
    button.textContent = "lightmode";
    button.style.backgroundColor = "#014D4E";
    button.style.color = "white";
  }