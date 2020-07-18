<template>
  <tr>
    <td width="120">
      <img src="http://via.placeholder.com/60x60" alt="" />
    </td>
    <td>{{ product.product.name }} / {{ product.name }}</td>
    <td width="160">
      <div class="field">
        <div class="control">
          <div class="select is-fullwidth">
            <select>
              <option value=""></option>
              <option
                v-for="x in product.stock_count"
                :key="x"
                :value="x"
                :selected="x == product.quantity"
              >
                {{ x }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </td>
    <td>{{ product.total }}</td>
    <td>
      <a href="" @click.prevent="destroyProduct(product.id)">Remove</a>
    </td>
  </tr>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  props: {
    product: {
      required: true,
      type: Object
    }
  },

  methods: {
    ...mapActions({
      destroy: 'cart/destroy'
    }),

    destroyProduct(productId) {
      if (confirm('Are you sure?')) {
        this.destroy(productId)
      }
    }
  }
}
</script>
