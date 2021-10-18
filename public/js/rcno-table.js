document.addEventListener('DOMContentLoaded', function() {
  function link (text, url) {
    return gridjs.h('a', {href: url}, text)
  }

  function multiLinks (selectedTerm, terms) {
    for (const term of terms) {
      if (term.hasOwnProperty(selectedTerm)) {
        return gridjs.h('div', gridjs.h('a', {href: term['link']}, term[selectedTerm]))
      }
    }

    return ''
  }

  function stars (count, empty) {
    if (count >= 5.0) return "★★★★★"
    if (count >= 4.0 && count <= 5.0) return "★★★★☆"
    if (count >= 3.0 && count <= 4.0) return "★★★☆☆"
    if (count >= 2.0 && count <= 3.0) return "★★☆☆☆"
    if (count >= 1.0 && count <= 2.0) return "★☆☆☆☆"

    return ! empty ? "☆☆☆☆☆": ''
  }

  function toBool(s) {
    const regex = /^\s*(true|1|on)\s*$/i

    return regex.test(s);
  }


  function multipleTerms(selectedTerm, terms, href = false) {
    let out = []

    for (const term of terms) {
      if (term.hasOwnProperty(selectedTerm)) {
        if (href) {
          out.push(link(term[selectedTerm], term['link']))
        } else {
          out.push(term[selectedTerm])
        }
      }
    }

    return out.join(', ')
  }

  const {sort, limit, search, columns, links} = window.rcno.rcnoTableShortcodeOptions
  const args = {}

  args.sort = toBool(sort)
  args.search = toBool(search)

  args.pagination = {}
  args.pagination.limit = parseInt(limit)
  args.pagination.summary = false

  args.columns = []
  for (const col of columns.split(',')) {
    args.columns.push({
      data: function (row) {
        return row.meta[col]
      },
      name: col[0].toUpperCase() + col.slice(1)
    })
  }

  args.data = window.rcno.rcnoTableShortcodeData.map(function(i) {
    console.log(multiLinks('category', i.terms))
    return {
      ID: i.ID,
      title: i.title,
      meta: {
        post: i.title,
        isbn: links.includes('isbn') ? link(i.meta.rcno_book_isbn, i.URL) : i.meta.rcno_book_isbn,
        title: links.includes('title') ? link(i.meta.rcno_book_title, i.URL) : i.meta.rcno_book_title,
        link: link(i.URL, i.URL),
        genre: links.includes('genre') ? link(i.terms.genre, i.terms.genre_link) : multipleTerms('genre', i.terms),
        author: links.includes('author') ? link(i.terms.author, i.terms.author_link) : multipleTerms('author', i.terms),
        publisher: links.includes('publisher') ? link(i.terms.publisher, i.terms.publisher_link) : multipleTerms('publisher', i.terms),
        year: i.meta.rcno_book_pub_date,
        rating: stars(parseFloat(i.meta.rcno_admin_rating), false),
        // TODO: Implement `h()` for multiple tags
        category: links.includes('category') ? multipleTerms('category', i.terms) : multipleTerms('category', i.terms),
        tag: links.includes('tag') ? multipleTerms('category', i.terms) : multipleTerms('post_tag', i.terms),
      },
    }
  })

  const grid = new gridjs.Grid(args)

  grid.render(document.getElementById('rcno-table'));
});