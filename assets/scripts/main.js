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
    window.location = `/../../app/auth/vote.php?id=${icon.dataset.id}&vote=${icon.dataset.vote}`;
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
    const reply = confirm('Are you sure?');
    if (!reply) {
      event.preventDefault();
    }
  })
})

const com_edit = document.querySelectorAll('.badge-primary');
com_edit.forEach((button) => {
  button.addEventListener('click', (event) => {
    const card_body = button.parentElement;
    const com_form = card_body.querySelector('form.comment');
    com_form.classList.toggle('d-block');
    com_form.classList.toggle('d-none');
    com_form.nextElementSibling.classList.toggle('d-none');
  })
})
