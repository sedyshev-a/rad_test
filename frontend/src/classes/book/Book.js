import _pick from 'lodash/pick'
import faker from 'faker'

export default class Book {
  constructor (book) {
    this.id = null
    this.author_name = null
    this.name = null
    this.taken_by = null

    Object.assign(this, _pick(book, Object.keys(this)))
  }

  static random () {
    const authorName = faker.name.findName()
    const name = faker.company.catchPhrase()
    return new Book({author_name: authorName, name})
  }
}
