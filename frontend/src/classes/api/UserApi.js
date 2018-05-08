import Api from './Api'
import User from '@/classes/user/User'
import IssueLog from '@/classes/issueLog/IssueLog'

export default class UserApi extends Api {
  add (user) {
    return this.axios.post('/api/user/add', user)
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
        return new User(responseData)
      })
  }
  list () {
    return this.axios.get('/api/user/list')
      .then(response => {
        return response.data
      })
      .then(responseData => {
        if (responseData.error) {
          throw new Error(responseData.error)
        }
        return responseData.map(user => new User(user))
      })
  }
  takeBook (user, book) {
    return this.axios.post('/api/user/take-book', {
      userId: user.id,
      bookId: book.id
    })
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
        return new IssueLog(responseData)
      })
  }
  returnBook (user, book) {
    return this.axios.post('/api/user/return-book', {
      userId: user.id,
      bookId: book.id
    })
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
        return new IssueLog(responseData)
      })
  }
  getLog (user) {
    return this.axios.get('/api/user/get-log', {
      params: {userId: user.id}
    })
      .then(response => {
        return response.data
      })
      .then(responseData => {
        if (responseData.error) {
          throw new Error(responseData.error)
        }
        return responseData.map(log => new IssueLog(log))
      })
  }
}
