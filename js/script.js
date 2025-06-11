const menuToggle = document.querySelector('.menu-toggle'),
navigation = document.querySelector('.navigation');

menuToggle.addEventListener('click', () => {
    navigation.classList.toggle('open');
});

const listItems = document.querySelectorAll('.list-item');
listItems.forEach(item => {
    item.onclick = () => {
        listItems.forEach(item => item.classList.remove('active'));
        item.classList.add('active');
    }
})

window.addEventListener('load', function() {
  const preloader = document.getElementById('preloader');
  setTimeout(function() {
    preloader.style.opacity = '0';
    setTimeout(function() {
      preloader.style.display = 'none';
    }, 200);
  }, 500); // Задержка перед исчезновением (1 секунда)
});