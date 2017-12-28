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

// This makes it possible to vote without page reloading
const icons = document.querySelectorAll('i');
icons.forEach(icon => {
  const id = icon.dataset.id;
  const vote = icon.dataset.vote;
  // Fetch current vote
  fetch('./app/auth/fetch_vote.php', {
    method: 'POST',
    body: `id=${id}`,
    headers: new Headers({
      'Content-Type': 'application/x-www-form-urlencoded'
    }),
    credentials: 'include',
  })
  .then(response => {
      return response.json();
  })
  .then(post => {
    // If current vote is the same as in database
    if (vote === post.vote) {
      icon.classList.add('fas');
      icon.classList.remove('far');
    }
  })
  // If user has already voted
  if (icon.classList.contains('far')) {
    icon.addEventListener('click', (event) => {
      const newVote = icon.dataset.vote;
      fetch('./app/auth/fetch_vote.php',{
        method: 'POST',
        body: `id=${id}&vote=${newVote}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
        credentials: 'include',
      })
      .then(response => {
        return response.json();
      })
      // Changes icons depending on the new vote
      .then(post => {
        if (newVote === post.vote) {
          icon.classList.toggle('fas');
          icon.classList.toggle('far');
          if (newVote === '1') {
            icon.nextElementSibling.classList.toggle('fas');
            icon.nextElementSibling.classList.toggle('far');
          } else if (newVote === '-1') {
            icon.previousElementSibling.classList.toggle('fas');
            icon.previousElementSibling.classList.toggle('far');
          }
        }
      })
      // Fetch new total vote
      fetch('./app/auth/fetch_vote.php',{
        method: 'POST',
        body: `id=${id}&post=true`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
        credentials: 'include',
      })
      .then(response => {
        return response.json();
      })
      // Updates and prints out vote
      .then(post => {
        icon.parentElement.querySelector('span').innerHTML = post.score;
      })
    })
  }
})


const com_buttons = document.querySelectorAll('button[name="comment"]');
com_buttons.forEach((button) => {
  button.addEventListener('click', (event) => {
    button.classList.add('d-none');
    button.nextElementSibling.classList.add('d-block');
    button.nextElementSibling.classList.remove('d-none');
  })
})

const comment_delete = document.querySelectorAll('.badge-danger');
comment_delete.forEach((button) => {
  button.addEventListener('click', (event) => {
    const reply = confirm('Are you sure?');
    if (reply) {
      const card = button.parentElement;
      const id = card.dataset.id;
      fetch('./app/auth/comment.php', {
        method: 'POST',
        body: `delete=true&comment_id=${id}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
        credentials: 'include',
      })
      card.parentElement.remove();
    }
  })
})

// const com_edit = document.querySelectorAll('.badge-primary [name="edit"]');
// com_edit.forEach((button) => {
//   button.addEventListener('click', (event) => {
//     const card_body = button.parentElement;
//     const com_form = card_body.querySelector('form.comment');
//     com_form.classList.toggle('d-block');
//     com_form.classList.toggle('d-none');
//     com_form.nextElementSibling.classList.toggle('d-none');
//   })
// })

// const com_reply = document.querySelectorAll('[name="reply"]');
// com_reply.forEach((button) => {
//   button.addEventListener('click', (event) => {
//     const card_body = button.parentElement;
//     const com_form = card_body.querySelector('form.reply');
//     com_form.classList.toggle('d-block');
//     com_form.classList.toggle('d-none');
//     com_form.previousElementSibling.classList.toggle('d-none');
//   })
// })


// Button toggles reply form
const reply = document.querySelectorAll('[name="reply"]');
reply.forEach(button => {
  button.addEventListener('click', () => {
    const card = button.parentElement;
    const id = card.dataset.id;
    fetch('/app/auth/fetch_comment.php', {
        method: 'POST',
        body: `id=${id}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
      })
      .then ((response) => {
        return response.json();
      })
      .then (post => {
        const post_id = post.post_id;
        const reply_form = `<form class="reply" action="/app/auth/comment.php" method="post">
        <input name="reply_id" value="${id}" hidden>
        <input name="post_id" value="${post_id}" hidden>
        <textarea class="form-control" name="content" rows="4"  cols="80"></textarea>
        <button class="btn btn-primary" name="reply" type="submit">Reply</button>
        </form>`;
        const div = document.createElement("div");
        div.innerHTML = reply_form;
        card.insertBefore(div, button);
        button.classList.add('d-none');
        // const submit = card.querySelector('[name="reply"]');
        // const form = card.querySelector('.reply');
        // submit.addEventListener('click', (event) => {
        //   console.log(form);
        //   const content = form.querySelector('textarea').value;
        //   const formInput = `reply_id=${id}&post_id=${post_id}&content=${content}`;
        //   fetch('/app/auth/comment.php', {
        //       method: 'POST',
        //       body: formInput,
        //       headers: new Headers({
        //         'Content-Type': 'application/x-www-form-urlencoded'
        //       }),
        //       credentials: 'include',
        //     })
        //   form.innerHTML = '';
        // })
    })
  })
})

// Button toggles edit form
const edit = document.querySelectorAll('[name="edit"]');
edit.forEach(button => {
  button.addEventListener('click', () => {
    const id = button.parentElement.dataset.id;
    const card = button.parentElement;
    fetch('/app/auth/fetch_comment.php', {
        method: 'POST',
        body: `id=${id}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
      })
      .then ((response) => {
        return response.json();
      })
      .then((post) => {
        const edit_form = `
        <textarea class="form-control" name="content" rows="4"  cols="80">${post.content}</textarea>
        <button class="btn btn-primary" name="edit" type="button">Save</button>`;
        const form = document.createElement("form");
        form.innerHTML = edit_form;
        const reply = card.querySelector('[name="reply"]');
        card.insertBefore(form, reply);
        card.querySelector('p').classList.add('d-none');
        button.classList.add('d-none');
      })
      // Submits comment changes and updates current comment
      .then(() => {
      const submit = card.querySelector('[type="button"]');
      submit.addEventListener('click', (event) => {
        const content = card.querySelector('textarea').value;
        const formInput = `comment_id=${id}&content=${content}&edit=true`;
        fetch('/app/auth/comment.php', {
            method: 'POST',
            body: formInput,
            headers: new Headers({
              'Content-Type': 'application/x-www-form-urlencoded'
            }),
            credentials: 'include',
          })
          .then(response => {
            return response.json();
          })
          .then(new_comment => {
            card.querySelector('form').remove();
            card.querySelector('p').textContent = new_comment.content;
            card.querySelector('p').classList.remove('d-none');
            button.classList.remove('d-none');
          })
        })
      })
    })
  })
