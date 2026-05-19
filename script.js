document.addEventListener('DOMContentLoaded', ()=>{
  // Theme toggle
  const themeBtn = document.getElementById('themeToggle');
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', savedTheme);
  themeBtn.textContent = savedTheme === 'dark' ? '☀️' : '🌙';

  themeBtn.addEventListener('click', ()=>{
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    themeBtn.textContent = next === 'dark' ? '☀️' : '🌙';
  });

  // apply loaded class after 5s to simulate entrance animation
  setTimeout(()=>document.body.classList.add('loaded'),5000);

  // set year in footer
  document.getElementById('year').textContent = new Date().getFullYear();

  const form = document.getElementById('bookingForm');
  const resp = document.getElementById('response');
  const secret = document.getElementById('secretKey').value;

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    resp.textContent = 'Sending...';
    const fd = new FormData(form);
    fd.append('secret', secret);

    try{
      const r = await fetch('send.php', {method:'POST', body:fd});
      const json = await r.json();
      if(json.ok){
        resp.textContent = '✅ Thanks for contacting us — our team will get back to you as soon as possible.';
        form.reset();
      } else {
        resp.textContent = json.error || 'Failed to send — check configuration.';
      }
    }catch(err){
      resp.textContent = 'Network error — could not reach the server.';
    }
  });
});
