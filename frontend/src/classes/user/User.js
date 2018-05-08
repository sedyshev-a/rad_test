import _pick from 'lodash/pick'
import faker from 'faker'

export default class User {
  constructor (user) {
    this.id = null
    this.fio = null
    this.phone = null

    Object.assign(this, _pick(user, Object.keys(this)))
  }

  static random () {
    const fio = faker.name.findName()
    const phone = faker.helpers.replaceSymbolWithNumber('+7##########')
    return new User({fio, phone})
  }
}
