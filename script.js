let header = document.querySelector("header");
let menu = document.querySelector("#menu-btn");
let userBtn = document.querySelector("#user-btn");

function fixedNavbar() {
  header.classList.toggle("scroll", window.pageYOffset > 0);
}

window.addEventListener("scroll", fixedNavbar);

menu.addEventListener("click", () => {
  let nav = document.querySelector(".navbar");
  nav.classList.toggle("active");
})

userBtn.addEventListener("click", () => {
  let userBox = document.querySelector(".user-box");
  userBox.classList.toggle("active");
});

// testimonial slider
const leftArrow = document.querySelector(".left-arrow");
const rightArrow = document.querySelector(".right-arrow");
let slides = document.querySelectorAll(".testimonial-item");
let i = 0;

rightArrow.addEventListener("click", () => {
  slides[i].classList.remove("active");
  i = (i + 1) % slides.length;
  slides[i].classList.add("active");
})

leftArrow.addEventListener("click", () => {
  slides[i].classList.remove("active");
  i = (i - 1 + slides.length) % slides.length;
  slides[i].classList.add("active");
});