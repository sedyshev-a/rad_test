import axios from 'axios'

export default class Api {
  constructor () {
    this.axios = axios.create()
  }
}
