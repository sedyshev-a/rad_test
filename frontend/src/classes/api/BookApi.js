import Api from './Api'
import Book from '@/classes/book/Book'

export default class BookApi extends Api {
  add (book) {
    return this.axios.post('/api/book/add', book)
      .then(response => {
        return response.data
      })
      .then(responseData => {
        if (responseData.error) {
          throw new Error(responseData.error)
        }
        if (responseData.errors) {
          throw new Error(responseData.errors[Object.keys(responseData.errors)[0]][0])
        }
        return new Book(responseData)
      })
  }
  list () {
    return this.axios.get('/api/book/list')
      .then(response => {
        return response.data
      })
      .then(responseData => {
        if (responseData.error) {
          throw new Error(responseData.error)
        }
        return responseData.map(book => new Book(book))
      })
  }
}
