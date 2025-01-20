const cards = document.querySelectorAll(".card");
const info = document.querySelector(".info");  // Only need one element, so select the first one

cards.forEach((card, index) => {
  // Single-click event to add the border
  card.addEventListener("click", () => {
    // Remove the border from all other cards
    cards.forEach((c) => (c.style.border = "none"));

    // Add the border to the clicked card
    card.style.border = "double 5px #e78207";

    // Print the card's identifier (e.g., index or textContent) in the info div
    info.textContent = `Card ${index + 1} selected`;  // Update the content of the .info div
  });

  // Double-click event to remove the border
  card.addEventListener("dblclick", () => {
    card.style.border = "none";

    // Log that the card was deselected and update the info div
    info.textContent = `Card ${index + 1} deselected`;  // Update the content of the .info div
  });
});
