<template>
  <div>
    <notifications group="main" position="top right"/>
    <div class="py-5 text-center">
      <h2>Мини-библиотека</h2>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <button class="btn btn-primary" @click="addRandomUser">Сгенерировать читателя</button>
        <button class="btn btn-primary" @click="addRandomBook">Сгенерировать книгу</button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <label for="users">Читатель</label>
            <multiselect
              id="users"
              class="mb-3"
              :loading="loading.users"
              v-model="selectedUser"
              :options="users"
              track-by="id"
              :custom-label="(user) => `${user.fio} (${user.phone})`"
              placeholder="Выберите пользователя"
            />
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-md-6">
            <label>Доступные книги</label>
            <ul class="list-group">
              <li
                class="list-group-item d-flex justify-content-between align-items-center"
                v-for="book in availableBooks"
                :key="book.id">
                {{ book.name }} ({{ book.author_name }})
                <button
                  type="button"
                  class="btn btn-outline-primary btn-sm"
                  @click="takeBook(book)">
                  Выдать
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <label>Выданные книги</label>
            <ul class="list-group">
              <li
                class="list-group-item d-flex justify-content-between align-items-center"
                v-for="book in takenBooks"
                :key="book.id">
                {{ book.name }} ({{ book.author_name }})
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm"
                  @click="returnBook(book)">
                  Вернуть
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-5">
        <ul class="list-group">
          <li
            class="list-group-item d-flex justify-content-between align-items-center"
            v-for="logItem in sortedLog"
            :key="logItem.id">
            <span :class="['badge', logItem.isIssueType() ? 'badge-primary' : 'badge-danger']">
              {{ logItem.isIssueType() ? 'Выдана' : 'Возвращена'}}
            </span>
            <div>{{ getBookNameById(logItem.book_id) }}</div>
            <div>{{ logItem.date }}</div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import Vue from 'vue'
import Notifications from 'vue-notification'
import UserApi from './classes/api/UserApi'
import User from '@/classes/user/User'
import BookApi from '@/classes/api/BookApi'
import Book from '@/classes/book/Book'

Vue.use(Notifications)
const userApi = new UserApi()
const bookApi = new BookApi()
export default {
  name: 'App',
  components: {
    Multiselect
  },
  data () {
    return {
      users: [],
      books: [],
      log: [],
      loading: {
        users: false,
        books: false
      },
      selectedUser: null,
      selectedUserLogItems: []
    }
  },
  computed: {
    availableBooks () {
      if (!this.selectedUser) { return [] }
      return this.books.filter(book => !book.taken_by)
    },
    takenBooks () {
      if (!this.selectedUser) { return [] }
      return this.books.filter(book => book.taken_by === this.selectedUser.id)
    },
    sortedLog () {
      let sorted = this.selectedUserLogItems.slice().sort((a, b) => {
        return new Date(b.date) - new Date(a.date)
      })
      if (sorted.length > 10) { sorted.length = 10 }
      return sorted
    }
  },
  methods: {
    fetchUsers () {
      this.loading.users = true
      return userApi.list()
        .then(users => {
          this.users = users
          this.loading.users = false
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при получении списка читателей',
            text: error.message
          })
          this.loading.users = false
        })
    },
    fetchBooks () {
      this.loading.books = true
      return bookApi.list()
        .then(books => {
          this.books = books
          this.loading.books = false
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при получении списка книг',
            text: error.message
          })
          this.loading.books = false
        })
    },
    fetchLog (user) {
      return userApi.getLog(user)
        .then(items => {
          this.selectedUserLogItems = items
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при получении списка действий',
            text: error.message
          })
        })
    },
    addRandomUser () {
      return userApi.add(User.random())
        .then(addedUser => {
          this.users.push(addedUser)
          this.$notify({
            group: 'main',
            type: 'success',
            title: 'Читатель успешно добавлен',
            text: `Имя: ${addedUser.fio}`
          })
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при добавлении читателя',
            text: error.message
          })
        })
    },
    addRandomBook () {
      return bookApi.add(Book.random())
        .then(addedBook => {
          this.books.push(addedBook)
          this.$notify({
            group: 'main',
            type: 'success',
            title: 'Книга успешно добавлена',
            text: `Название: "${addedBook.name}"`
          })
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при добавлении книги',
            text: error.message
          })
        })
    },
    takeBook (book) {
      userApi.takeBook(this.selectedUser, book)
        .then(logItem => {
          const idx = this.books.findIndex(b => b.id === book.id)
          this.books.splice(idx, 1, new Book({...book, taken_by: this.selectedUser.id}))
          this.selectedUserLogItems.push(logItem)
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при попытке выдать книгу',
            text: error.message
          })
        })
    },
    returnBook (book) {
      userApi.returnBook(this.selectedUser, book)
        .then(logItem => {
          const idx = this.books.findIndex(b => b.id === book.id)
          this.books.splice(idx, 1, new Book({...book, taken_by: null}))
          this.selectedUserLogItems.push(logItem)
        })
        .catch(error => {
          this.$notify({
            group: 'main',
            type: 'error',
            title: 'Ошибка при попытке вернуть книгу',
            text: error.message
          })
        })
    },
    getBookNameById (bookId) {
      const book = this.books.find(book => book.id === bookId)
      return `"${book.name}" (${book.author_name})`
    }
  },
  watch: {
    selectedUser (user) {
      this.fetchLog(user)
    }
  },
  created () {
    this.fetchUsers()
    this.fetchBooks()
  }
}
</script>

<style lang="scss">
  @import '../node_modules/bootstrap/scss/bootstrap.scss';
</style>
