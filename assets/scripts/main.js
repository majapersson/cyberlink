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
  new_reply.classList.add('card', 'mt-2');
  new_reply.innerHTML = `<div class="card-body p-2" data-id=${comment.id}>
    <div class="row">
      <div class="col-10">
        <a href="account.php/?id=${comment.user_id}"><img src="/assets/avatars/thumbnails/${comment.image_url}"> ${comment.username}</a>
        <small>${timestamp}</small>
      </div>
      <div class="col-2 text-right">
        <button class="btn badge badge-info outline" name="edit" type="submit">Edit</button>
        <button class="btn badge badge-danger" data-toggle="modal" data-target="#deleteComment" data-id=${comment.id}>Delete</button>
      </div>
    </div>
    <p>${comment.content}</p>
    <button class="btn badge badge-info" name="reply">Reply</button>
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
      <button class="btn badge badge-info outline" name="edit" type="submit">Edit</button>
      <form class="d-inline" action="/app/auth/comment.php" method="post">
          <input type="hidden" name="comment_id" value="<?php echo $comment['id'] ?>">
          <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
      </form>`;
  }
  link.parentElement.insertBefore(comment_card, link);
}

const printUserPost = (post, link, session_id) => {
  const timestamp = formatDate(post.timestamp);
  const post_card = document.createElement('div');
  post_card.classList.add('card','my-2');
  post_card.innerHTML = `<div class="card-body">
    <div class="row">
      <div class="col-9">
      <!-- Actual post -->
        <a href="${post.url}"><h5>${post.title}</h5></a>
        <p>${post.content}</p>
        <small>Submitted on
        <time>${timestamp}</time>
        </small>
        <a href="/post.php?post=${post.id}"><small class="d-block">${post.comments} comments</small></a>
      </div>
      <div class="col-3 text-right">
      <!-- Edit button -->
      </div>
    </div>
  </div>`;
  if (session_id.toString() === post.user_id) {
    post_card.querySelector('.col-3').innerHTML = `<form action="edit_post.php" method="post" class="d-inline">
        <input type="hidden" name="post_id" value="${post.id}">
        <button class="btn btn-info" type="submit">Edit post</button>
    </form>`;
  }
  link.parentElement.insertBefore(post_card, link);
}

if (window.location.pathname === '/index.php' || window.location.pathname === '/') {
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
}

if (window.location.pathname === '/search.php') {
  const urlGet = new URLSearchParams(window.location.search);
  if (urlGet.has('search')) {
      const search = urlGet.get('search');
      fetch('/../../app/auth/fetch_posts.php', {
          method: 'POST',
          body: `search=${search}`,
          headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded'
          })
      })
      .then (response => {
          return response.json();
      })
      .then (posts => {
        fetch('/../../app/auth/comment.php', {
          credentials: 'include',
        })
        .then (response => {
          return response.json();
        })
        .then (session_id => {
            posts.forEach(post => {
                printPost(post, session_id);
            })
        })
        .then (() => {
          vote_function();
        })
      })
  }
}

// Start listeners
vote_function();
reply_listener();
edit_listener();
delete_listener();
