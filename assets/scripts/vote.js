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
      icon.classList.add('clicked');
    }
  })
  // If user has already voted
  // if (!icon.classList.contains('clicked')) {
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
        const span = icon.parentElement.querySelector('span');
        if (newVote === post.vote || post.vote === '0') {
          icon.classList.toggle('clicked');
          if (newVote === '1') {
            span.nextElementSibling.classList.remove('clicked');
          } else if (newVote === '-1') {
            span.previousElementSibling.classList.remove('clicked');
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
  // }
})
