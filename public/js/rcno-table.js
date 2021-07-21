document.addEventListener('DOMContentLoaded', function() {
  const link = function (text, url) {
    return gridjs.h('a', {href: url}, text)
  }
  const stars = function (count, empty) {
    if (count >= 5.0) return "★★★★★"
    if (count >= 4.0 && count <= 5.0) return "★★★★☆"
    if (count >= 3.0 && count <= 4.0) return "★★★☆☆"
    if (count >= 2.0 && count <= 3.0) return "★★☆☆☆"
    if (count >= 1.0 && count <= 2.0) return "★☆☆☆☆"
    return ! empty ? "☆☆☆☆☆": ''
  }

  const toBool = function(s){
    const regex = /^\s*(true|1|on)\s*$/i

    return regex.test(s);
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
    return {
      ID: i.ID,
      title: i.title,
      meta: {
        post: i.title,
        isbn: links.includes('isbn') ? link(i.meta.rcno_book_isbn, i.URL) : i.meta.rcno_book_isbn,
        title: links.includes('title') ? link(i.meta.rcno_book_title, i.URL) : i.meta.rcno_book_title,
        link: link(i.URL, i.URL),
        genre: links.includes('genre') ? link(i.terms.genre, i.terms.genre_link) : i.terms.genre,
        author: links.includes('author') ? link(i.terms.author, i.terms.author_link) : i.terms.author,
        publisher: links.includes('publisher') ? link(i.terms.publisher, i.terms.publisher_link) : i.terms.publisher,
        year: i.meta.rcno_book_pub_date,
        rating: stars(parseFloat(i.meta.rcno_admin_rating), false)
      },
    }
  })

  const grid = new gridjs.Grid(args)

  grid.render(document.getElementById('rcno-table'));
});