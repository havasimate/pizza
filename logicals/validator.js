const form = document.querySelector("form");
const messageInput = document.querySelector("#hir");

form.addEventListener("submit", function (event) {
  event.preventDefault();

  const messageValue = messageInput.value.trim();
  if (messageValue.length > 300) {
    alert("Az üzenet mező nem lehet hosszabb, mint 300 karakter!");
    messageInput.focus();
    return;
  }

  form.submit();
});
