'use strict';

// Formats timestamp to date string
const formatDate = (timestamp) => {
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
  const timestamp = formatDate(comment.timestamp);
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

// Prints user comment on account page
const printUserComment = (comment, link, session_id) => {
  const timestamp = formatDate(comment.timestamp);
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

window.onload = () => {
  const urlGet = new URLSearchParams(window.location.search);
  if (urlGet.has('page')) {
    loadPage(urlGet.get('page'), '', true);
  } else if (urlGet.has('post')) {
      const post_body = `post=${urlGet.get('post')}&`;
      loadPage(0, post_body);
  } else {
    loadPage(0);
  }
}

// Start listeners
vote_function();
reply_listener();
edit_listener();
delete_listener();
