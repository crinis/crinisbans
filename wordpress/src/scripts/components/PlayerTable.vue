<template>
  <table class="table">
    <thead>
      <tr>
        <th v-for="column in columns"
            :key="column.key"
            @click="sortBy(column.key)">{{ column.text }}</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th v-for="column in columns"
            :key="column.key"
            @click="sortBy(column.key)">{{ column.text }}</th>
      </tr>
    </tfoot>
    <tbody>
      <tr v-for="player in filteredPlayers"
          :key="player.steamID64">
        <td>{{ player.nickName }}</td>
        <td>{{ player.score }}</td>
        <td>{{ player.steamID64 }}</td>
        <td>
          <Button target="_blank"
                  :href="player.steamProfileUrl"
                  :title="text.steamProfile"
                  icon="fa-steam-square"></Button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import Button from './Button.vue'
export default {
  props: ['ajaxPlayers', 'text'],
  data () {
    let sortOrders = {}
    let columns = [
        { key: 'nickName', text: this.text.nickName },
        { key: 'score', text: this.text.score },
        { key: 'steamID64', text: this.text.steamID64 },
        { key: 'steamProfileUrl', text: this.text.steamProfile }
    ]

    columns.forEach(function (column, key) { sortOrders[column.key] = -1 })

    return {
      sortKey: 'score',
      sortOrders: sortOrders,
      columns: columns
    }
  },
  components: {
    Button: Button
  },
  computed: {
    filteredPlayers: function () {
      let sortKey = this.sortKey
      let order = this.sortOrders[sortKey] || 1
      let players = []
      this.ajaxPlayers.forEach(function (ajaxPlayer, key) {
        players[key] = {
          nickName: ajaxPlayer.nickName,
          score: ajaxPlayer.score,
          steamID64: ajaxPlayer.steamID64,
          steamProfileUrl: ajaxPlayer.steamProfileUrl
        }
      })
      if (sortKey) {
        players = players.slice().sort(function (a, b) {
          a = a[sortKey]
          b = b[sortKey]
          return (a === b ? 0 : a > b ? 1 : -1) * order
        })
      }
      return players
    }
  },
  filters: {
    capitalize: function (str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    }
  },
  methods: {
    sortBy: function (key) {
      this.sortKey = key
      this.sortOrders[key] = this.sortOrders[key] * -1
    }
  }
}
</script>

<style scoped lang="scss">

</style>
