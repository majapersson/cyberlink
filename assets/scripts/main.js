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
