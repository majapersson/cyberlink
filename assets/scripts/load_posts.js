'use strict';

// Function for printing out posts
const printPost = (post, session_id) => {
  const section = document.querySelector('section');
  const timestamp = formatDate(post.timestamp);

    // HTML
    const article = document.createElement('article');
    article.classList.add('card', 'm-1');
    article.setAttribute('id', post.id);
    article.innerHTML += `<div class="card-body">
      <div class="row">
        <div class="col-1 d-flex flex-column align-items-center">
        </div>
        <div class="col-9">
        <!-- Actual post -->
          <a href="${post.url}">
          <h3>${post.title}</h3>
          </a>
          <p>${post.content}</p>
          <small>Submitted by
          <a href="account.php/?id=${post.user_id}">${post.username}</a> on
          <time>${timestamp}</time>
          </small>
          <a href="/post.php?post=${post.id}"><small class="d-block">${post.comments} comments</small></a>
        </div>
        <div class="col-2 text-right">
        <!-- Edit button -->
        </div>
      </div>
    </div>`;

    // Vote icons
    if (!session_id){
      article.querySelector('.col-1').innerHTML =
        `<i class="fas fa-sort-up disabled"></i>
        <span>${post.score}</span>
        <i class="fas fa-sort-down disabled"></i>`;
    } else {
      article.querySelector('.col-1').innerHTML =
        `<i class="fas fa-sort-up" data-id=${post.id} data-vote=1></i>
        <span>${post.score}</span>
        <i class="fas fa-sort-down" data-id=${post.id} data-vote=-1></i>`;
    }

    // Edit button
    if (session_id && post.user_id === session_id) {
      article.querySelector('.col-2').innerHTML =
          `<form action="edit_post.php" method="post" class="d-inline">
              <input type="hidden" name="post_id" value="${post.id}">
              <button class="btn btn-primary" type="submit">Edit post</button>
          </form>`;
    }

    section.appendChild(article);
}

// Writes posts to front page
const loadPage = (page, post_body='', loop=false) => {
  const urlGet = new URLSearchParams(window.location.search);
  post_body += `page=${page}`;

  // Print out multiple pages
  if (loop) {
    for(let i=0; i<=page; i++){
      loadPage(i);
    }
  }
  // Fetch posts for this page
  fetch('/../../app/auth/fetch_posts.php', {
    method: 'POST',
    body: post_body,
    headers: new Headers({
      'Content-Type': 'application/x-www-form-urlencoded'
    }),
  })
  .then (response => {
    return response.json();
  })
  .then (json => {
    // Fetch session_id
    fetch('/../../app/auth/comment.php', {
      credentials: 'include',
    })
    .then (response => {
      return response.text();
    })
    .then (session_id => {
        for(post of json.data) {
          printPost(post, session_id);
        }
    })
    .then (() => {
      vote_function();
    })
  })
}

// Listener for post load on end of page
if (window.location.pathname === '/index.php' || window.location.pathname === '/') {
  fetch('/../../app/auth/fetch_posts.php')
  .then (response => {
    return response.json();
  })
  .then (total_posts => {
    window.addEventListener('scroll', () => {
      // When scrolling reaches bottom of page
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        const post_container = document.querySelector('section');
        const current_posts = post_container.querySelectorAll('.card').length;
        const urlGet = new URLSearchParams(window.location.search);
        let page = 0;
        // If there are more posts in the database
        if (current_posts < total_posts) {
          if (urlGet.has('page')) {
            page = urlGet.get('page');
            loadPage(page);
            window.history.pushState(`${page++}`, null, `?page=${page++}`)
          } else {
            page = 1;
            loadPage(page);
            window.history.pushState(`${page++}`, null, `?page=${page++}`)
          }
        }
      }
    })
  })
}
