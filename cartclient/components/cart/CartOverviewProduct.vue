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
      <a href="">Remove</a>
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    product: {
      required: true,
      type: Object
    }
  }
}
</script>
