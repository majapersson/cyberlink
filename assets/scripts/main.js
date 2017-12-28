console.log('Hello World!');

// Post delete button
const post_delete = document.querySelector('.btn-danger');
if (post_delete) {
  post_delete.addEventListener('click', (event) => {
    const reply = confirm('Are you sure?');
    if(!reply) {
      event.preventDefault();
    }
  })
}
// Post comment button
const com_buttons = document.querySelectorAll('button[name="comment"]');
com_buttons.forEach((button) => {
  button.addEventListener('click', (event) => {
    button.classList.add('d-none');
    button.nextElementSibling.classList.add('d-block');
    button.nextElementSibling.classList.remove('d-none');
  })
})

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

// Delete comment
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
      fetch('./app/auth/comment.php', {
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
      .then(comment => {
        console.log(comment);
        card.querySelector('p').textContent = comment.content;
        const deleted = document.createTextNode('[deleted]');
        card.insertBefore(deleted, card.querySelector('a'));
        card.querySelector('a').remove();
      })
    }
  })
})

// Formats timestamp to date string
const toFormatDate = (timestamp) => {
  const date = new Date(timestamp * 1000);
  const dateValues = [
    date.getFullYear(),
    date.getMonth()+1,
    date.getDate(),
    date.getHours()-1,
    date.getMinutes(),
  ];
  return `${dateValues[0]}-${dateValues[1]}-${dateValues[2]} ${dateValues[3]}:${dateValues[4]}`;
}

// Prints new comment
const printComment = ((comment, card) => {
  const new_reply = document.createElement('div');
  const timestamp = toFormatDate(comment.timestamp);
  new_reply.classList.add('card');
  new_reply.classList.add('m-2');
  new_reply.innerHTML = `<div class="card-body">
    <a href="account.php/?id=${comment.user_id}"> ${comment.username}</a>
    <small>${timestamp}</small>
    <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
    <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
    <p>${comment.content}</p>
    <button class="btn badge badge-primary" name="reply">Reply</button>
  </div>`;
  card.appendChild(new_reply);
})

// Button toggles reply form
const reply = Array.prototype.slice.call(document.querySelectorAll('[name="reply"]'));
reply.forEach(button => {
  button.addEventListener('click', () => {
    const card = button.parentElement;
    const id = card.dataset.id;
    fetch('/app/auth/comment.php', {
        method: 'POST',
        body: `id=${id}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
      })
      .then (response => {
        return response.json();
      })
      .then (post => {
        const post_id = post.post_id;
        const reply_form = `<input name="reply_id" value="${id}" hidden>
        <input name="post_id" value="${post_id}" hidden>
        <textarea class="form-control" name="content" rows="4"  cols="80"></textarea>
        <button class="btn btn-primary" name="reply" type="button">Reply</button>`;
        const form = document.createElement("form");
        form.setAttribute('name', 'reply_form');
        form.innerHTML = reply_form;
        card.insertBefore(form, button);
        button.classList.add('d-none');

        // Submits reply and changes current comment
        const submit = form.querySelector('button');
        submit.addEventListener('click', (event) => {
          const formInput = new FormData(form);
          fetch('/app/auth/comment.php', {
              method: 'POST',
              body: formInput,
              credentials: 'include',
            })
          form.innerHTML = '';
          button.classList.remove('d-none');
          // FUNKAR INTE, HÄMTAR FLERA REPLIES //
          fetch('/app/auth/comment.php', {
              method: 'POST',
              body: `reply_id=${post.id}`,
              headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
              }),
              credentials: 'include',
            })
            .then(response => {
              return response.json();
            })
            .then(comments => {
              comments.forEach(comment => {
                printComment(comment, card);
              })
            })
        })
    })
  })
})

// Button toggles edit form
const edit = document.querySelectorAll('[name="edit"]');
edit.forEach(button => {
  button.addEventListener('click', () => {
    const card = button.parentElement;
    const id = card.dataset.id;
    fetch('/app/auth/comment.php', {
        method: 'POST',
        body: `id=${id}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        }),
      })
      .then ((response) => {
        return response.json();
      })
      .then((comment) => {
        const edit_form = `<input type="hidden" name="comment_id" value="${comment.id}">
        <textarea class="form-control" name="content" rows="4"  cols="80">${comment.content}</textarea>
        <button class="btn btn-primary" name="edit" type="button">Save</button>`;
        const form = document.createElement("form");
        form.setAttribute('name', 'edit_comment');
        form.innerHTML = edit_form;
        const reply = card.querySelector('[name="reply"]');
        card.insertBefore(form, reply);
        card.querySelector('p').classList.add('d-none');
        button.classList.add('d-none');
        return form;
      })
      // Submits comment changes and updates current comment
      .then((form) => {
      const submit = form.querySelector('button');
      submit.addEventListener('click', (event) => {
        const edit_form = document.forms.namedItem('edit_comment');
        const formInput = new FormData(edit_form);
        formInput.append('edit', 'true');
        fetch('/app/auth/comment.php', {
            method: 'POST',
            body: formInput,
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
