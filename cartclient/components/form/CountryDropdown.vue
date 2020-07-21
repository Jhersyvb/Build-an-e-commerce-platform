<template>
  <div class="select is-fullwidth">
    <select @change="changed">
      <option value="">Please select</option>
      <option v-for="country in countries" :key="country.id" :value="country.id">
        {{ country.name }}
      </option>
    </select>
  </div>
</template>

<script>
export default {
  data() {
    return {
      countries: []
    }
  },

  created() {
    this.getCountries()
  },

  methods: {
    async getCountries() {
      let response = await this.$axios.$get('countries')

      this.countries = response.data
    },

    changed(event) {
      this.$emit('input', event.target.value)
    }
  }
}
</script>
