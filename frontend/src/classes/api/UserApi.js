import Api from './Api'

export default class UserApi extends Api {
  add (user) {
    return this.axios.post('/api/user/add', user)
  }
}
