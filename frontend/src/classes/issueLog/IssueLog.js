import _pick from 'lodash/pick'

export default class IssueLog {
  constructor (log) {
    this.id = null
    this.user_id = null
    this.book_id = null
    this.type = null
    this.date = null

    Object.assign(this, _pick(log, Object.keys(this)))
  }
  isIssueType () {
    return this.type === 'issue'
  }
}
