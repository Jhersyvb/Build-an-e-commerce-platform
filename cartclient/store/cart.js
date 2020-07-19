export const state = () => ({
  products: [],
  empty: true
})

export const getters = {
  products (state) {
    return state.products
  },

  count (state) {
    return state.products.length
  },

  empty (state) {
    return state.empty
  }
}

export const mutations = {
  SET_PRODUCTS (state, products) {
    state.products = products
  },

  SET_EMPTY (state, empty) {
    state.empty = empty
  }
}

export const actions = {
  async getCart ({ commit }) {
    let response = await this.$axios.$get('cart')

    commit('SET_PRODUCTS', response.data.products)
    commit('SET_EMPTY', response.meta.empty)

    return response
  },

  async destroy ({ dispatch }, productId) {
    let response = await this.$axios.$delete(`cart/${productId}`)

    dispatch('getCart')
  },

  async update ({ dispatch }, { productId, quantity }) {
    let response = await this.$axios.$patch(`cart/${productId}`, {
      quantity
    })

    dispatch('getCart')
  }
}
