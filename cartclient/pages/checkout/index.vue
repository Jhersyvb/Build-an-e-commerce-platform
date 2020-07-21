<template>
  <div class="section">
    <div class="container is-fluid">
      <div class="columns">
        <div class="column is-three-quarters">
          {{ form }}
          <ShippingAddress :addresses="addresses" v-model="form.address_id" />

          <article class="message">
            <div class="message-body">
              <h1 class="title is-5">Payment</h1>
            </div>
          </article>

          <article class="message">
            <div class="message-body">
              <h1 class="title is-5">
                Shipping
              </h1>
              <div class="select is-fullwidth">
                <select>
                  <option>
                    Royal Mail 1st Class
                  </option>
                </select>
              </div>
            </div>
          </article>

          <article class="message" v-if="products.length">
            <div class="message-body">
              <h1 class="title is-5">
                Cart summary
              </h1>
              <CartOverview>
                <template slot="rows">
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="has-text-weight-bold">
                      Shipping
                    </td>
                    <td>
                      S/ 0.00
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="has-text-weight-bold">
                      Total
                    </td>
                    <td>
                      {{ total }}
                    </td>
                  </tr>
                </template>
              </CartOverview>
            </div>
          </article>

          <article class="message">
            <div class="message-body">
              <button class="button is-info is-fullwidth is-medium" :disabled="empty">
                Place order
              </button>
            </div>
          </article>
        </div>
        <div class="column is-one-quarter">
          <article class="message">
            <div class="message-body">
              <button class="button is-info is-fullwidth is-medium" :disabled="empty">
                Place order
              </button>
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

import CartOverview from '@/components/cart/CartOverview'
import ShippingAddress from '@/components/checkout/addresses/ShippingAddress'

export default {
  components: {
    CartOverview,
    ShippingAddress
  },

  data() {
    return {
      addresses: [],
      form: {
        address_id: null
      }
    }
  },

  computed: {
    ...mapGetters({
      total: 'cart/total',
      products: 'cart/products',
      empty: 'cart/empty'
    })
  },

  async asyncData({ app }) {
    let addresses = await app.$axios.$get('addresses')

    return {
      addresses: addresses.data
    }
  }
}
</script>
