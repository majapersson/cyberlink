console.log('Hello World!');

const danger = document.querySelector('.btn-danger');

if (danger) {
  danger.addEventListener('click', () => {
    const reply = confirm('Are you sure?');
    if(reply) {
      window.location = '/delete.php';
    }
  })
}
