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
            icon.nextElementSibling.classList.remove('fas');
            icon.nextElementSibling.classList.add('far');
          } else if (newVote === '-1') {
            icon.previousElementSibling.classList.remove('fas');
            icon.previousElementSibling.classList.add('far');
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
