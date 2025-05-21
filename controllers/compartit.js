 fetch('../includes/header.html')
    .then(response => response.text())
    .then(data => document.querySelector('#cabecera').innerHTML = data);

     fetch('../includes/footer.html')
    .then(response => response.text())
    .then(data => document.querySelector('#footer').innerHTML = data);