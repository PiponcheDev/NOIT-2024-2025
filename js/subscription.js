const cards = document.querySelectorAll(".card");
const info = document.getElementById("card-info");
const btn = document.getElementById("buy");
const form = document.querySelector("form[action='subscription.php']");



const cardDescriptions = [
  `Пенсионерска карта:
    • Намаление на цената за пътуване
    • Удобен достъп до градския транспорт
    • Без нужда от ежедневно купуване на билети
    • Валидност за дълъг период от време
    • Спокойствие при пътуване без допълнителни разходи`,

  `Ученическа карта:
    • Намалени цени за ученици
    • Лесен достъп до училище и извънкласни дейности
    • Спестяване на време и пари от билети
    • Валидна през цялата учебна година
    • Стимулира ползването на обществен транспорт`,

  `Нормална карта:
    • Удобство за редовно пътуващи
    • Фиксирани месечни разходи за транспорт
    • Без нужда от купуване на билети всеки ден
    • Валидност за всички линии в града
    • Практично решение за работещи и студенти`,
];

function isCardSelected() {
  return document.querySelector(".card.selected") !== null;
}

function updateUI() {
  if (isCardSelected()) {
    btn.style.display = 'block';
    info.textContent = '';  // Remove existing text
    info.appendChild(btn);  // Ensure only the button is shown
    info.style.justifyContent = 'center';  // Centers the button in the middle
  } else {
    btn.style.display = 'none';
    info.textContent = 'Моля, изберете карта.'; // Add message if no card is selected
    info.style.justifyContent = 'center';
  }
}

btn.style.display = 'none';
info.textContent = 'Моля, изберете карта.';
info.style.justifyContent = 'center';

cards.forEach((card, index) => {
  card.addEventListener("click", () => {
    cards.forEach((c) => {
      c.style.border = "none";
      c.classList.remove("selected");
    });

    card.style.border = "double 5px #e78207";
    card.classList.add("selected");

    console.log("Card selected:", card); // Debugging line
    info.textContent = cardDescriptions[index] || 'Описание не е налично.';
    updateUI();
  });

  card.addEventListener("dblclick", () => {
    card.style.border = "none";
    card.classList.remove("selected");
    info.textContent = `Картата е деселектирана.`;
    updateUI();
  });
});

form.addEventListener("submit", (event) => {
  if (!isCardSelected()) {
    event.preventDefault();
    console.log("No card selected, form submission prevented."); // Debugging line
    alert("Please select a card before proceeding.");
  } else {
    const selectedCard = document.querySelector(".card.selected");
    const cardType = selectedCard.querySelector("p").textContent.trim();
    console.log("Card type selected:", cardType); // Debugging line

    const cardInput = document.createElement("input");
    cardInput.type = "hidden";
    cardInput.name = "card_type";
    cardInput.value = cardType;
    form.appendChild(cardInput);
  }
});
