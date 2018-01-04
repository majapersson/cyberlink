console.log('Hello World!');

// Delete post
const post_delete = document.querySelector('.btn-danger');
if (post_delete) {
  post_delete.addEventListener('click', (event) => {
    const reply = confirm('Are you sure?');
    if(!reply) {
      event.preventDefault();
    }
  })
}

// Delete comment
const delete_listener = () => {
  const comment_delete = document.querySelectorAll('.badge-danger');
  comment_delete.forEach((button) => {
    button.addEventListener('click', (event) => {
      const reply = confirm('Are you sure?');
      if (!reply) {
        event.preventDefault();
      }
    })
  })
}

// Formats timestamp to date string
const toFormatDate = (timestamp) => {
  const date = new Date(timestamp * 1000);
  const dateValues = [
    date.getUTCFullYear(),
    date.getUTCMonth()+1,
    date.getUTCDate(),
    date.getUTCHours(),
    date.getUTCMinutes(),
  ];
  return `${dateValues[0]}-${dateValues[1]}-${dateValues[2]} ${dateValues[3]}:${dateValues[4]}`;
}

// Prints new comment
const printComment = ((comment, card, before=null) => {
  const new_reply = document.createElement('div');
  const timestamp = toFormatDate(comment.timestamp);
  new_reply.classList.add('card');
  new_reply.classList.add('m-2');
  new_reply.innerHTML = `<div class="card-body" data-id=${comment.id}>
    <a href="account.php/?id=${comment.user_id}"> ${comment.username}</a>
    <small>${timestamp}</small>
    <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
    <form class="d-inline" action="/app/auth/comment.php" method="post">
    <input type="hidden" name="comment_id" value="${comment.id}">
    <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
    </form>
    <p>${comment.content}</p>
    <button class="btn badge badge-primary" name="reply">Reply</button>
  </div>`;
  if (!before) {
    card.appendChild(new_reply);
  } else {
    card.insertBefore(new_reply, before);
  }
  reply_listener();
  edit_listener();
  delete_listener();
})

// Button toggles comment form
const com_buttons = document.querySelectorAll('[name="comment"]');
com_buttons.forEach(button => {
  button.addEventListener('click', () => {
    const card = button.parentElement;
    const post_id = card.parentElement.getAttribute('id');
    const inner_form = `<input type="hidden" name="post_id" value="${post_id}">
    <textarea class="form-control" name="content" rows="5" cols="80"></textarea>
    <button type="button" class="btn btn-primary">Comment</button>`;
    const form = document.createElement('form');
    form.innerHTML = inner_form;
    card.insertBefore(form, button);
    button.classList.add('d-none');
    // Submits form and prints out new comment
    const submit = form.querySelector('button');
    submit.addEventListener('click', () => {
      const formInput = new FormData(form);
      fetch('/app/auth/comment.php', {
        method: 'POST',
        body: formInput,
        credentials: 'include',
      })
      .then (response => {
        return response.json();
      })
      .then(comment => {
        form.remove();
        button.classList.remove('d-none');
        const before = card.querySelector('.card');
        printComment(comment, card, before);
      })
    })
  })
})

const reply_listener = () => {
  // Button toggles reply form
  const reply = document.querySelectorAll('[name="reply"]');
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
          const inner_form = `<input type="hidden" name="post_id" value="${post_id}">
          <input type="hidden" name="reply_id" value="${id}">
          <textarea class="form-control" name="content" rows="4" cols="80"></textarea>
          <button type="button" class="btn btn-primary">Reply</button>`
          const form = document.createElement("form");
          form.innerHTML = inner_form;
          card.insertBefore(form, button);
          button.classList.add('d-none');
          return form;
        })
        .then (form => {
          // Submits reply and changes current comment
          const submit = form.querySelector('button');
          submit.addEventListener('click', (event) => {
            const formInput = new FormData(form);
            fetch('/app/auth/comment.php', {
                method: 'POST',
                body: formInput,
                credentials: 'include',
              })
              .then (response => {
                return response.json();
              })
              .then(comment => {
                form.remove();
                button.classList.remove('d-none');
                printComment(comment, card);
              })
          })
      })
    })
  })
}

const edit_listener = () => {
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
      .then (response => {
        return response.json();
      })
      .then(comment => {
        const inner_form = `<input type="hidden" name="comment_id" value="${comment.id}">
        <textarea class="form-control" name="content" rows="4"  cols="80">${comment.content}</textarea>
        <button class="btn btn-primary" type="button">Save</button>`;
        const form = document.createElement("form");
        form.innerHTML = inner_form;
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
        const formInput = new FormData(form);
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
            form.remove();
            card.querySelector('p').textContent = new_comment.content;
            card.querySelector('p').classList.remove('d-none');
            button.classList.remove('d-none');
          })
        })
      })
    })
  })
  }

  reply_listener();
  edit_listener();
  delete_listener();
