console.log('Hello World!');

const danger = document.querySelector('.btn-danger');

if (danger) {
  danger.addEventListener('click', (event) => {
    const reply = confirm('Are you sure?');
    if(!reply) {
      event.preventDefault();
    }
  })
}

const icons = document.querySelectorAll('i');
icons.forEach((icon) => {
  icon.addEventListener('click', () => {
    icon.classList.toggle('far');
    icon.classList.toggle('fas');
    window.location = `/../../app/auth/vote.php?id=${icon.dataset.id}&dir=${icon.dataset.dir}`;
  })
})
