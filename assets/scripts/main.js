console.log('Hello World!');

const post_delete = document.querySelector('.btn-danger');

if (post_delete) {
  post_delete.addEventListener('click', (event) => {
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

const com_buttons = document.querySelectorAll('button[name="comment"]');
com_buttons.forEach((button) => {
  button.addEventListener('click', (event) => {
    button.classList.add('d-none');
    button.nextElementSibling.classList.add('d-block');
    button.nextElementSibling.classList.remove('d-none');
  })
})

const com_delete = document.querySelectorAll('.badge-danger');
com_delete.forEach((button) => {
  button.addEventListener('click', (event) => {
    event.preventDefault();
    const reply = confirm('Are you sure?');
    if (reply) {
      window.location = `/../../app/auth/comment.php/?id=${button.dataset.id}&del=1`;
    }
  })
})
