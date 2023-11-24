import axios from 'axios';

const loadMore = () => {
  const button = document.querySelector('.load-more');

  if (typeof button !== 'undefined' && button !== null) {
    button.addEventListener('click', (e) => {
      console.log(ajaxurl);

      let current_page = document.querySelector('.blogs').dataset.page;
      let max_pages = document.querySelector('.blogs').dataset.max;

      let params = new URLSearchParams();
      params.append('action', 'load_more_posts');
      params.append('current_page', current_page);
      params.append('max_pages', max_pages);

      axios
        .post(ajaxurl, params)

        .then((res) => {
          console.log({res});
          let blogs = document.querySelector('.blogs');

          blogs.innerHTML += res.data.data;

          if (
            (document.querySelector('.blogs').dataset.page =
              document.querySelector('.blogs').dataset.max)
          ) {
            button.parentNode.removeChild(button);
          }
          document.querySelector('.blogs').dataset.page++;
        });
    });
  }
};

export default loadMore;
