'use strict';

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

// Regular comment form
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
        const before = button.nextElementSibling;
        printComment(comment, card, before);
      })
    })
  })
})

// Listener for reply form
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

// Listener for edit form
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

// Listener for loading comments link
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

// Listener for loading posts link
const load_posts = document.querySelector('[name="load_posts"]');
if (load_posts) {
  let page = 1;
  load_posts.addEventListener('click', (event) => {
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
      fetch('/app/auth/fetch_posts.php', {
        method: 'post',
        body: `user_id=${id}&page=${page}`,
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
        })
      })
      .then(response => {
        return response.json();
      })
      .then(posts => {
        if (!posts.length) {
          load_posts.remove();
        }
        posts.forEach(post => {
          printUserPost(post, load_posts, session_id);
        })
        edit_listener();
        delete_listener();
      })
      page++;
    })
  })
}

const search_bar = document.querySelector('[name="search"]');
search_bar.addEventListener('keypress', (event) => {
  if (event.key === 'Enter') {
    const search_value = search_bar.value;
    window.location.replace(`/search.php?search=${search_value}`);
  }
})
