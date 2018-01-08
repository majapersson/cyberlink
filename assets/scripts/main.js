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
    date.getFullYear(),
    date.getUTCMonth()+1,
    date.getUTCDay(),
    date.getUTCHours(),
    date.getUTCMinutes(),
  ];

  const formatValues = dateValues.map(value => {
    if (value < 10) {
       return '0' + value;
    } else {
      return `${value}`;
    }
  })

  return `${formatValues[0]}-${formatValues[1]}-${formatValues[2]} ${formatValues[3]}:${formatValues[4]}`;
}

// Prints new comment
const printComment = ((comment, card, before=null) => {
  const new_reply = document.createElement('div');
  const timestamp = toFormatDate(comment.timestamp);
  new_reply.classList.add('card');
  new_reply.classList.add('mt-2');
  new_reply.innerHTML = `<div class="card-body p-2" data-id=${comment.id}>
    <div class="row">
      <div class="col-10">
        <a href="account.php/?id=${comment.user_id}"> ${comment.username}</a>
        <small>${timestamp}</small>
      </div>
      <div class="col-2 text-right">
        <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
        <form class="d-inline" action="/app/auth/comment.php" method="post">
        <input type="hidden" name="comment_id" value="${comment.id}">
        <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
        </form>
      </div>
    </div>
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

const printUserComment = (comment, link, session_id) => {
  const timestamp = toFormatDate(comment.timestamp);
  const comment_card = document.createElement('div');
  comment_card.classList.add('card','mb-2');
  comment_card.innerHTML = `<div class="card-body" data-id=${comment.id}>
    <div class="row">
        <div class="col-8">
            <!-- Comment post title -->
            <a href="/post.php?post=${comment.post_id}#${comment.id}">${comment.title}</a> by <a href="/account.php?id=${comment.author}">${comment.username}</a>
            <!-- Actual comment -->
            <p>${comment.content}</p>
            <small>Submitted on ${timestamp}</small>
        </div>
        <div class="col-4 text-right">
        </div>
      </div>
    </div>`;
  if (session_id === comment.user_id) {
    comment_card.querySelector('.col-4').innerHTML += `<!-- Buttons -->
      <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
      <form class="d-inline" action="/app/auth/comment.php" method="post">
          <input type="hidden" name="comment_id" value="<?php echo $comment['id'] ?>">
          <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
      </form>`;
  }
  link.parentElement.insertBefore(comment_card, link);
}

// Button toggles comment form
const com_buttons = document.querySelectorAll('[name="comment"]');
com_buttons.forEach(button => {
  button.addEventListener('click', () => {
    const card = button.parentElement;
    const post_id = card.getAttribute('id');
    const inner_form = `<input type="hidden" name="post_id" value=${post_id}>
    <textarea class="form-control" name="content" rows="5" cols="80"></textarea>
    <button type="button" class="btn btn-primary mt-2">Comment</button>`;
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
          <button type="button" class="btn btn-primary mt-2">Reply</button>`
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
    const card = button.parentElement.parentElement.parentElement;
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
        <button class="btn btn-primary mt-2" type="button">Save</button>`;
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

  const load_comment = document.querySelector('[name="load_comments"]');
  if (load_comment) {
    let page = 1;
    load_comment.addEventListener('click', (event) => {
      event.preventDefault();
      const urlGet = new URLSearchParams(window.location.search);

      let id;
      fetch('/app/auth/comment.php', {
        credentials: 'include',
      })
      .then (response => {
        return response.json();
      })
      .then(session_id => {
        if (urlGet.has('id')) {
          id = urlGet.get('id');
        }
        else {
          id = session_id;
        }
        fetch('/app/auth/comment.php', {
          method: 'post',
          body: `user_id=${id}&page=${page}`,
          headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
          })
        })
        .then(response => {
          return response.json();
        })
        .then(comments => {
          if (!comments.length) {
            load_comment.remove();
          }
          comments.forEach(comment => {
            printUserComment(comment, load_comment, session_id);
          })
          edit_listener();
          delete_listener();
        })
        page++;
      })
    })
  }

  reply_listener();
  edit_listener();
  delete_listener();
